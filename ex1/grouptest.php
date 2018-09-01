<?php
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 19/08/18
 * Time: 05:02 AM
 */

use \koolreport\core\Utility;

$data2 = array(
    array("MARCA"=>"Toyota","XX"=>"XX01","MODELO"=>"Corolla","AÑO"=>2010,"CONTADOR"=>10,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","XX"=>"XX01","MODELO"=>"Corolla","AÑO"=>2010,"CONTADOR"=>20,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","XX"=>"XX02","MODELO"=>"Corolla","AÑO"=>2011,"CONTADOR"=>15,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","XX"=>"XX01","MODELO"=>"Corolla","AÑO"=>2010,"CONTADOR"=>20,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","XX"=>"XX02","MODELO"=>"Corolla","AÑO"=>2011,"CONTADOR"=>15,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","XX"=>"XX01","MODELO"=>"Corolla","AÑO"=>2010,"CONTADOR"=>20,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","XX"=>"XX02","MODELO"=>"Corolla","AÑO"=>2011,"CONTADOR"=>15,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","XX"=>"XX03","MODELO"=>"Audax","AÑO"=>2011,"CONTADOR"=>20,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","XX"=>"XX04","MODELO"=>"Auris","AÑO"=>2012,"CONTADOR"=>30,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","XX"=>"XX05","MODELO"=>"Auris","AÑO"=>2013,"CONTADOR"=>10,"SUMADOR"=>10),
    array("MARCA"=>"Nissan","XX"=>"XX06","MODELO"=>"Agensa","AÑO"=>2019,"CONTADOR"=>11,"SUMADOR"=>10),
    array("MARCA"=>"Nissan","XX"=>"XX07","MODELO"=>"March","AÑO"=>2019,"CONTADOR"=>11,"SUMADOR"=>10),
    array("MARCA"=>"Bmw","XX"=>"XX08","MODELO"=>"550I","AÑO"=>2018,"CONTADOR"=>20,"SUMADOR"=>10),
    array("MARCA"=>"Bmw","XX"=>"XX08","MODELO"=>"550I","AÑO"=>2019,"CONTADOR"=>20,"SUMADOR"=>10),
    array("MARCA"=>"Bmw","XX"=>"XX08","MODELO"=>"550Z","AÑO"=>2018,"CONTADOR"=>20,"SUMADOR"=>10),
    array("MARCA"=>"Bmw","XX"=>"XX08","MODELO"=>"550Z","AÑO"=>2018,"CONTADOR"=>22,"SUMADOR"=>10),
);

$data = array(
    array("MARCA"=>"Toyota","MODELO"=>"Corolla","AÑO"=>2010,"CONTADOR"=>10,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","MODELO"=>"Corolla","AÑO"=>2010,"CONTADOR"=>20,"SUMADOR"=>20),
    array("MARCA"=>"Toyota","MODELO"=>"Corolla","AÑO"=>2010,"CONTADOR"=>30,"SUMADOR"=>30),
    array("MARCA"=>"Toyota","MODELO"=>"Corolla","AÑO"=>2010,"CONTADOR"=>40,"SUMADOR"=>40),
    array("MARCA"=>"Toyota","MODELO"=>"Corolla","AÑO"=>2011,"CONTADOR"=>15,"SUMADOR"=>50),
    array("MARCA"=>"Toyota","MODELO"=>"Corolla","AÑO"=>2011,"CONTADOR"=>20,"SUMADOR"=>60),
    array("MARCA"=>"Toyota","MODELO"=>"Audax","AÑO"=>2011,"CONTADOR"=>20,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","MODELO"=>"Audax","AÑO"=>2011,"CONTADOR"=>40,"SUMADOR"=>30),
    array("MARCA"=>"Toyota","MODELO"=>"Audax","AÑO"=>2011,"CONTADOR"=>45,"SUMADOR"=>30),
    array("MARCA"=>"Toyota","MODELO"=>"Auris","AÑO"=>2012,"CONTADOR"=>30,"SUMADOR"=>10),
    array("MARCA"=>"Toyota","MODELO"=>"Auris","AÑO"=>2013,"CONTADOR"=>10,"SUMADOR"=>10),
    array("MARCA"=>"Nissan","MODELO"=>"Agensa","AÑO"=>2019,"CONTADOR"=>11,"SUMADOR"=>10),
    array("MARCA"=>"Nissan","MODELO"=>"March","AÑO"=>2019,"CONTADOR"=>11,"SUMADOR"=>10),
    array("MARCA"=>"Bmw","MODELO"=>"550I","AÑO"=>2018,"CONTADOR"=>20,"SUMADOR"=>10),
    array("MARCA"=>"Bmw","MODELO"=>"550I","AÑO"=>2019,"CONTADOR"=>20,"SUMADOR"=>10),
    array("MARCA"=>"Bmw","MODELO"=>"550Z","AÑO"=>2018,"CONTADOR"=>20,"SUMADOR"=>10),
    array("MARCA"=>"Bmw","MODELO"=>"550Z","AÑO"=>2018,"CONTADOR"=>22,"SUMADOR"=>10),
    array("MARCA"=>"Bmw","MODELO"=>"550Z","AÑO"=>2018,"CONTADOR"=>22,"SUMADOR"=>20),
);


$fields2 = array(
    "MARCA","MODELO","XX","AÑO","CONTADOR","SUMADOR"
);


$groups = array(
    "fields"=>  array(
        "MARCA"=> array("agg" => array("sum"=>["CONTADOR","SUMADOR"],"max"=>["AÑO"],"count"=>["MODELO"])),
        "MODELO"=>array("agg" => array("avg"=>["CONTADOR"],"count"=>["AÑO"])),
        "AÑO"=> array("agg" => array("sum"=>["CONTADOR"]))
    ),
    "options"=> array("showfooter"=>"bottom","style"=>"one_line")
);

$fields = [
    "MARCA","MODELO","AÑO","CONTADOR","SUMADOR"
];

#print_r($groups);
/*foreach($fieldsxx as $field) {
    print ($field);

    echo ("*************\n");
    foreach ($groups["fields"][$field] as $aggr ) {
        #print_r($aggr);
        foreach ($aggr as $operator=> $flist) {
            print_r($operator."\n");
            foreach($flist as $f) {
                print("--->".$f."\n");
            }

        }
    }
}*/



//return;
$idx = 0;
$c = "cnt";
$levelsAgg = [];

$groupKeys = array_keys($groups["fields"]);
foreach ($data as $record) {
    if ($idx == 0) {
        $fkeys = array_keys($record);
    }

    $lastValue = "";
    $numFields = count($record);

    for ($i = 0; $i < $numFields; $i++) {
        $curField = $fkeys[$i];

        // El nombre del campo esta en los grupos?
        if (in_array($curField, $groupKeys)) {
            #if (in_array($idx,$levelsAgg[$fields[$i]])) {
            if (strlen($lastValue) == 0) {
                $fieldTest = $record[$curField];
            } else {
                $fieldTest = $lastValue.'_'.$record[$curField];
            }

            //$operator = $groups["fields"][$fkeys[$i]]["agg"]["op"];
            #$opField = $groups["fields"][$fkeys[$i]]["agg"]["field"];

            foreach ($groups["fields"][$curField] as $aggr ) {
                foreach ($aggr as $operator=> $opFields) {
                    foreach($opFields as $opField) {
                        if (!isset($fieldTest, $levelsAgg[$fieldTest][$opField])) {
                            switch ($operator) {
                                case "sum":
                                case "count":
                                    $levelsAgg[$fieldTest][$opField] = 0;
                                    break;

                                case "avg":
                                    $levelsAgg[$fieldTest][$opField] = [0,0];
                                    break;

                                case "min":
                                    $levelsAgg[$fieldTest][$opField]  = INF;
                                    break;

                                case "max":
                                    $levelsAgg[$fieldTest][$opField]  = -INF;
                                    break;
                            }
                        }

                        switch ($operator) {
                            case "sum":
                                $levelsAgg[$fieldTest][$opField]  += $record[$opField];
                                break;

                            case "count":
                                $levelsAgg[$fieldTest][$opField]  += 1;
                                break;

                            case "dcount":
                                $levelsAgg[$fieldTest][$opField][$record[$opField]]  = null;
                                break;

                            case "avg":
                                $levelsAgg[$fieldTest][$opField][0]  += $record[$opField];
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
    $idx++;
}
// Treat the avg case , is detected when the value assignbed for an aggregate
// field is an array  and his first element is not null , if is null is a
// dcount (distinct count) case.
foreach($levelsAgg as $opField => $aggFields) {
    foreach($aggFields as $aggField => $fldValue)
    if (gettype($fldValue) == "array") {
        if ($fldValue[0] == null) {
            $levelsAgg[$opField][$aggField] = count($fldValue);
        } else {
            $levelsAgg[$opField][$aggField] = $fldValue[0] / $fldValue[1];
        }
    };
}
//print_r($levelsAgg);

//return;
#unset($levelsAgg);
$currLevel = 0;
$lastLevels = [];
$totals = [];
$groupStyle = isset($groups["options"]["style"]) ? $groups["options"]["style"]  : "";
$footerStyle = isset($groups["options"]["showfooter"]) ? $groups["options"]["showfooter"]  : "bottom";

echo "<table border='0'  width='50%' style='font-size: small'>";

foreach ($data as $record) {
    $currLevel = 0;
    $lastValue = "";
    $row = "";
    $ptotal = "";
    if (!isset($numFields)) {
        $numFields = count($record);
    }

    for ($i = 0; $i < $numFields; $i++) {
        $curField = $fkeys[$i];

        if (in_array($curField, $fields)) {
            // El nombre del campo esta en los grupos?
            if (in_array($curField, $groupKeys)) {

                if (strlen($lastValue) == 0) {
                    $fieldTest = $record[$curField];
                } else {
                    $fieldTest = $lastValue.'_'.$record[$curField];
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
                        if ($groupStyle != "one_line" && $currLevel < count($groupKeys)) {
                            $row .= "<td>".$record[$curField]."</td></tr><tr>";
                            for ($j = 0; $j <= $i; $j++) {
                                $row .= "<td></td>";
                            }
                        } else {
                            $row .= "<td>".$record[$curField]."</td>";
                        }

                        // Prepare the total string to be printed
                        if (isset($levelsAgg) && count($levelsAgg) > 0) {
                            if (isset($lastLevels[$currLevel]) && isset($totals[$lastLevels[$currLevel]])) {
                                $ptotal = $totals[$lastLevels[$currLevel]].$ptotal;
                                unset($totals[$lastLevels[$currLevel]]);
                            }

                            // assembly the total row
                            $totals[$lastValue] = "<tr style='background-color: lightgray'>";
                            if ($i > 0) {
                                $totals[$lastValue] .= "<td colspan='".$i."'></td>";
                            }
                            $totals[$lastValue] .= "<td>Total ".$record[$curField]." =></td>";

                            // The totals value
                            $orderedFields=array();
                            foreach ($groups["fields"][$curField] as $aggr ) {
                                foreach ($aggr as $operator=> $opFields) {
                                    foreach ($opFields as $fld) {
                                        $idx = array_search($fld, $fields);
                                        if ($idx !== false) {
                                            $orderedFields[$idx] = $fld;
                                        }
                                    }
                                }
                                ksort($orderedFields);
                                for ($j=$i+1 ; $j < count($fields); $j++ ) {
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
                                $ptotal .= "<tr style='background-color: lightgray' ><td colspan='".($i)."'></td>";
                            }
                            $ptotal .= "<td>".$record[$curField]."</td>";

                            // The totals value
                            $orderedFields=array();
                            foreach ($groups["fields"][$curField] as $aggr ) {
                                foreach ($aggr as $operator=> $opFields) {
                                    foreach ($opFields as $fld) {
                                        $idx = array_search($fld, $fields);
                                        if ($idx !== false) {
                                            $orderedFields[$idx] = $fld;
                                        }
                                    }
                                }
                                ksort($orderedFields);

                                for ($j=$i+1 ; $j < count($fields); $j++ ) {
                                    if (!isset($orderedFields[$j])) {
                                        $ptotal .= "<td></td>";
                                    } else {
                                        $ptotal .= "<td>".$levelsAgg[$lastValue][$orderedFields[$j]]."</td>";
                                    }
                                }
                            }

                            $ptotal .= "</tr>";
                            $row .= "<td></td>";
                        }
                    }
                } else {
                    $row .= "<td></td>";
                }

                $lastLevels[$currLevel] = $lastValue;

            } else {
                $row .= "<td>".$record[$curField]."</td>";
            }
        }
    }

    if (strlen($ptotal)) {
        echo $ptotal;
    }
    if (strlen($row)) {
        echo $row;
    }
    echo "</tr>";
}

// Elk ultimo total aun no esta pintado y creeado , solo el ultimo
// subtotal , por ende hay que completrarlo.
// lastvalue contiene el ultimo grupo de proceso aun no pintado , por ende
// a partir de alli generamos el total faltante.
if ($footerStyle == "bottom") {
    $ptotal = "";
    foreach($totals as $total) {
        $ptotal = $total.$ptotal;
    }
    echo $ptotal;
}

echo "<table>";

