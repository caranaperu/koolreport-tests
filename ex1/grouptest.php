<?php
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 19/08/18
 * Time: 05:02 AM
 */
require_once "../../../../koolreport/autoload.php";
use \koolreport\core\Utility;

class VeritradeAutosTest extends \koolreport\KoolReport
{
    use \koolreport\export\Exportable;

    public function settings()
    {
        return array(
            "dataSources"=>array(
                "sqlserver"=>array(
                    'host' => '192.168.0.5',
                    'username' => 'sa',
                    'password' => 'Melivane100',
                    'dbname' => 'veritrade',
                    'class' => "\koolreport\datasources\SQLSRVDataSource"
                ),
            )
        );
    }

    public function setup()
    {
        $this->src('sqlserver')
            ->query("SELECT MARCA,MODELO,ANO_REPORTE as ANO,count(*) as CONTADOR,count(*)*2 as SUMADOR FROM veritrade group by MARCA,MODELO,ANO_REPORTE order by MARCA,MODELO,ANO_REPORTE")
            #->query("SELECT MARCA,MODELO,ANO_REPORTE,count(*) as CONTADOR FROM veritrade WHERE MARCA=:MARCA group by MARCA,MODELO,ANO_REPORTE order by MARCA,MODELO,ANO_REPORTE")
            #->params(array(":MARCA"=>$this->params["MARCA"]))
            #->pipe(new Limit(array(1000)))
            #->pipe(new \koolreport\processes\AggregatedColumn(array("total"=>array("sum","CONTADOR"))))
            ->pipe($this->dataStore('Veritrade'));
    }
}




$veritradeAutos = new VeritradeAutosTest();
$veritradeAutos->run();

$setup = [
    "name" => "ptable",
    //"dataStore" => $this->dataStore('Veritrade'),
    "showFooter" => "bottom",
    "columns" => [
        "MARCA",
        "MODELO" => [
            "footer" => "sum",
            "footerText" => "Total: @value"
        ],
        "ANO_REPORTE"=>array("cssStyle"=>"text-align:right"),
        "CONTADOR" => [
            "footer" => "sum",
            "footerText" => "Total: @value",
            "cssStyle" => "text-align:right"
        ]
    ],
    "paging"=>array(
        "pageSize"=>40,
        "align"=>"center",
        "pageIndex"=>0,
    ),
    "fixedHeader" => true,

    "options" => [
        "fixedHeader" => true,
        "rowGroup" => true,
        "processing" => true,
    ],
    "removeDuplicate2" => [
        "fields"=>  ["MARCA","MODELO","ANO" ],
        "options"=> ["showfooter"=>"bottom","style"=>"one_line"]
    ],
    "removeDuplicate" => [
        "fields"=>  [
            "MARCA" => [
                "agg" => array(
                    "sum" => [
                        "CONTADOR",
                        "SUMADOR"
                    ],
                    "max" => ["ANO"],
                    "count" => ["MODELO"]
                )
            ],
            "MODELO" => [
                "agg" => array(
                    "avg" => ["CONTADOR"],
                    "count" => ["ANO"]
                )
            ],
            "ANO" => ["agg" => array("sum" => ["CONTADOR"])]
        ],
        "options"=> ["showfooter"=>"bottom","style"=>"one_line"]
    ]
];


$showColumnsKeys = ["MARCA","MODELO","ANO","CONTADOR","SUMADOR"];


//return;

$groupColumns;
$groups;

function processGroups() {
    global $showColumnsKeys;
    global $veritradeAutos;
    global $setup;
    global $groupColumns;
    global $groups;

    $levelsAgg = [];

    $groups = Utility::get($setup,"removeDuplicate");

    if (isset($groups)) {
        $fieldList = $groups["fields"];
        if (isset($fieldList[0])) {
            $groupColumns = $fieldList;
        } else {
            $groupColumns = array_keys($fieldList);
        }
    }

    if (isset($groupColumns)) {

        $veritradeAutos->dataStore("Veritrade")->popStart();

        while ($record = $veritradeAutos->dataStore("Veritrade")->pop()) {
            $lastValue = "";

            foreach ($showColumnsKeys as $cKey) {

                // El nombre del campo esta en los grupos?
                if (in_array($cKey, $groupColumns)) {
                    if (strlen($lastValue) == 0) {
                        $fieldTest = $record[$cKey];
                    } else {
                        $fieldTest = $lastValue.'_'.$record[$cKey];
                    }


                    foreach ($groups["fields"][$cKey] as $aggr) {
                        foreach ($aggr as $operator => $opFields) {
                            foreach ($opFields as $opField) {
                                if (!isset($fieldTest, $levelsAgg[$fieldTest][$opField])) {
                                    switch ($operator) {
                                        case "sum":
                                        case "count":
                                            $levelsAgg[$fieldTest][$opField] = 0;
                                            break;

                                        case "avg":
                                            $levelsAgg[$fieldTest][$opField] = [
                                                0,
                                                0
                                            ];
                                            break;

                                        case "min":
                                            $levelsAgg[$fieldTest][$opField] = INF;
                                            break;

                                        case "max":
                                            $levelsAgg[$fieldTest][$opField] = -INF;
                                            break;
                                    }
                                }

                                switch ($operator) {
                                    case "sum":
                                        $levelsAgg[$fieldTest][$opField] += $record[$opField];
                                        break;

                                    case "count":
                                        $levelsAgg[$fieldTest][$opField] += 1;
                                        break;

                                    case "dcount":
                                        $levelsAgg[$fieldTest][$opField][$record[$opField]] = null;
                                        break;

                                    case "avg":
                                        $levelsAgg[$fieldTest][$opField][0] += $record[$opField];
                                        $levelsAgg[$fieldTest][$opField][1] += 1;
                                        break;

                                    case "min":
                                        $levelsAgg[$fieldTest][$opField] = min($levelsAgg[$fieldTest][$opField], $record[$opField]);
                                        break;

                                    case "max":
                                        $levelsAgg[$fieldTest][$opField] = max($levelsAgg[$fieldTest][$opField], $record[$opField]);
                                        break;

                                }
                            }

                        }
                    }

                    $lastValue = $fieldTest;
                }
            }
        }

        // Treat the avg case , is detected when the value assignbed for an aggregate
        // field is an array  and his first element is not null , if is null is a
        // dcount (distinct count) case.
        foreach ($levelsAgg as $opField => $aggFields) {
            foreach ($aggFields as $aggField => $fldValue) {
                if (gettype($fldValue) == "array") {
                    if ($fldValue[0] == null) {
                        $levelsAgg[$opField][$aggField] = count($fldValue);
                    } else {
                        $levelsAgg[$opField][$aggField] = $fldValue[0] / $fldValue[1];
                    }
                }
            }
        }
    }

    return $levelsAgg;
}

//print_r($levelsAgg);
$levelsAgg = processGroups();

//return;
#unset($levelsAgg);
$currLevel = 0;
$lastLevels = [];
$totals = [];
$groupStyle = isset($groups["options"]["style"]) ? $groups["options"]["style"] : "";
$footerStyle = isset($groups["options"]["showfooter"]) ? $groups["options"]["showfooter"] : "bottom";

echo "<table border='1'  width='90%' style='font-size: small'>";

$numFields = count($showColumnsKeys);
$veritradeAutos->dataStore("Veritrade")->popStart();
while ($row = $veritradeAutos->dataStore("Veritrade")->pop()) {
    //foreach ($data as $row) {
    $currLevel = 0;
    $lastValue = "";
    $rowout = "";
    $ptotal = "";

    for ($i = 0; $i < $numFields; $i++) {

        $cKey = $showColumnsKeys[$i];

        // El nombre del campo esta en los grupos?
        if (isset($groupColumns) && in_array($cKey, $groupColumns)) {

            if (strlen($lastValue) == 0) {
                $fieldTest = $row[$cKey];
            } else {
                $fieldTest = $lastValue.'_'.$row[$cKey];
            }

            if (strlen($lastValue) < strlen($fieldTest)) {
                $currLevel++;
            } else {
                $currLevel--;

            }

            // Cambio De Greupo
            $lastValue = $fieldTest;
            if (!isset($lastLevels[$currLevel]) || $lastLevels[$currLevel] != $lastValue) {

                // Print the normal row if footer is at bottom
                if ($footerStyle == "bottom") {
                    if ($groupStyle != "one_line" && $currLevel < count($groupColumns)) {
                        $rowout .= "<td>".$row[$cKey]."</td></tr><tr style='background-color: aqua'>";
                        for ($j = 0; $j <= $i; $j++) {
                            $rowout .= "<td></td>";
                        }
                    } else {
                        $rowout .= "<td>".$row[$cKey]."</td>";
                    }

                    // Prepare the total string to be printed
                    if (isset($levelsAgg) && count($levelsAgg) > 0) {
                        if (isset($lastLevels[$currLevel]) && isset($totals[$lastLevels[$currLevel]])) {
                            $ptotal = $totals[$lastLevels[$currLevel]].$ptotal;
                            unset($totals[$lastLevels[$currLevel]]);
                        }

                        // assembly the total row
                        //$totals[$lastValue] = "<tr style='background-color: lightgray'>";
                        $totals[$lastValue] = "<tr style='background-color: aqua'>";
                        if ($i > 0) {
                            $totals[$lastValue] .= "<td colspan='".$i."'></td>";
                        }
                        $totals[$lastValue] .= "<td>Total ".$row[$cKey]." =></td>";

                        // The totals value
                        $orderedFields = [];
                        foreach ($groups["fields"][$cKey] as $aggr) {
                            foreach ($aggr as $operator => $opFields) {
                                foreach ($opFields as $fld) {
                                    $idx = array_search($fld, $showColumnKeys);
                                    if ($idx !== false) {
                                        $orderedFields[$idx] = $fld;
                                    }
                                }
                            }
                            ksort($orderedFields);
                            for ($j = $i + 1; $j < count($showColumnKeys); $j++) {
                                if (!isset($orderedFields[$j])) {
                                    $totals[$lastValue] .= "<td></td>";
                                } else {
                                    $totals[$lastValue] .= "<td>".$levelsAgg[$lastValue][$orderedFields[$j]]."</td>";
                                }
                            }
                        }
                        $totals[$lastValue] .= "</tr>";
                    }
                } else {


                    // Print the normal row if footer is at bottom
                    if (isset($levelsAgg) && count($levelsAgg) > 0) {
                        if ($i > 0) {
                            #$ptotal .= "<tr style='background-color: lightgray' ><td colspan='".($i)."'></td>";
                            $ptotal .= "<tr style='background-color: aqua'><td colspan='".($i)."'></td>";
                        }
                        $ptotal .= "<td>".$row[$cKey]."</td>";

                        // The totals value
                        $orderedFields = [];
                        foreach ($groups["fields"][$cKey] as $aggr) {
                            foreach ($aggr as $operator => $opFields) {
                                foreach ($opFields as $fld) {
                                    $idx = array_search($fld, $showColumnKeys);
                                    if ($idx !== false) {
                                        $orderedFields[$idx] = $fld;
                                    }
                                }
                            }
                            ksort($orderedFields);

                            for ($j = $i + 1; $j < count($showColumnKeys); $j++) {
                                if (!isset($orderedFields[$j])) {
                                    $ptotal .= "<td></td>";
                                } else {
                                    $ptotal .= "<td>".$levelsAgg[$lastValue][$orderedFields[$j]]."</td>";
                                }
                            }
                        }

                        $ptotal .= "</tr>";
                        $rowout .= "<td></td>";
                    }
                }
            } else {
                $rowout .= "<td></td>";
            }

            $lastLevels[$currLevel] = $lastValue;

        } else {
            $rowout .= "<td>".$row[$cKey]."</td>";
        }
    }

    if (strlen($ptotal)) {
        echo $ptotal;
    }
    if (strlen($rowout)) {
        echo $rowout;
    }
    echo "</tr>";
}

// Elk ultimo total aun no esta pintado y creeado , solo el ultimo
// subtotal , por ende hay que completrarlo.
// lastvalue contiene el ultimo grupo de proceso aun no pintado , por ende
// a partir de alli generamos el total faltante.
if ($footerStyle == "bottom") {
    $ptotal = "";
    foreach ($totals as $total) {
        $ptotal = $total.$ptotal;
    }
    echo $ptotal;
}

echo "<table>";

