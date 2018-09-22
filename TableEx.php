<?php
/**
 * This file contains Table widget
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

// "columns"=>array(
// 	"type",
// 	"{others}"=>array(
// 		"type"=>"number",
// 		""=>"" //Expression or function
// 	)
// )


namespace koolreport\widgets\koolphp;

use \koolreport\core\Widget;
use \koolreport\core\Utility;
use \koolreport\core\DataStore;
use \koolreport\core\Process;
use \koolreport\core\DataSource;

class TableEx extends Widget {
    protected $name;
    protected $columns;
    protected $cssClass;
    protected $removeDuplicate;
    protected $excludedColumns;
    protected $formatFunction;

    protected $showFooter;
    protected $showHeader;
    protected $footer;

    protected $paging;

    protected $clientEvents;

    protected $headers;
    protected $responsive;

    protected function resourceSettings() {
        return [
            "library" => ["jQuery"],
            "folder" => "table",
            "js" => ["table.js"],
            "css" => ["table.css"],
        ];
    }

    protected function onInit() {
        $this->useLanguage();
        $this->useDataSource();
        $this->useAutoName("ktable");
        $this->clientEvents = Utility::get($this->params, "clientEvents");
        $this->columns = Utility::get($this->params, "columns", []);

        if ($this->dataStore == null) {
            $data = Utility::get($this->params, "data");
            if (is_array($data)) {
                if (count($data) > 0) {
                    $this->dataStore = new DataStore;
                    $this->dataStore->data($data);
                    $row = $data[0];
                    $meta = ["columns" => []];
                    foreach ($row as $cKey => $cValue) {
                        $meta["columns"][$cKey] = [
                            "type" => Utility::guessType($cValue),
                        ];
                    }
                    $this->dataStore->meta($meta);
                } else {
                    $this->dataStore = new DataStore;
                    $this->dataStore->data([]);
                    $metaColumns = [];
                    foreach ($this->columns as $cKey => $cValue) {
                        if (gettype($cValue) == "array") {
                            $metaColumns[$cKey] = $cValue;
                        } else {
                            $metaColumns[$cValue] = [];
                        }
                    }
                    $this->dataStore->meta(["columns" => $metaColumns]);
                }
            }
            if ($this->dataStore == null) {
                throw new \Exception("dataSource is required for Table");

                return;
            }
        }

        if ($this->dataStore->countData() == 0 && count($this->dataStore->meta()["columns"]) == 0) {
            $meta = ["columns" => []];
            foreach ($this->columns as $cKey => $cValue) {
                if (gettype($cValue) == "array") {
                    $meta["columns"][$cKey] = $cValue;
                } else {
                    $meta["columns"][$cValue] = [];
                }
            }
            $this->dataStore->meta($meta);
        }

        $this->removeDuplicate = Utility::get($this->params, "removeDuplicate", []);
        $this->cssClass = Utility::get($this->params, "cssClass", []);
        $this->excludedColumns = Utility::get($this->params, "excludedColumns", []);

        $this->showFooter = Utility::get($this->params, "showFooter");
        $this->showHeader = Utility::get($this->params, "showHeader", true);


        $this->paging = Utility::get($this->params, "paging");
        if ($this->paging !== null) {
            $this->paging = [
                "pageSize" => Utility::get($this->paging, "pageSize", 10),
                "pageIndex" => Utility::get($this->paging, "pageIndex", 0),
                "align" => Utility::get($this->paging, "align", "left"),
            ];
            $this->paging["itemCount"] = $this->dataStore->countData();
            $this->paging["pageCount"] = ceil($this->paging["itemCount"] / $this->paging["pageSize"]);
        }

        //Header Group
        $this->headers = Utility::get($this->params, "headers", []);
        $this->responsive = Utility::get($this->params, "responsive", false);
    }

    protected function formatValue($value, $format, $row = null) {
        $formatValue = Utility::get($format, "formatValue", null);

        if (is_string($formatValue)) {
            eval('$fv="'.str_replace('@value', '$value', $formatValue).'";');

            return $fv;
        } else {
            if (is_callable($formatValue)) {
                return $formatValue($value, $row);
            } else {
                return Utility::format($value, $format);
            }
        }
    }


    protected function processGroups($showColumnKeys) {

        $existAggr = false;
        $levelsAgg = [];

        $groups = Utility::get($this->params, "removeDuplicate");

        if (isset($groups)) {
            $fieldList = $groups["fields"];
            foreach ($fieldList as $fieldKey => $field) {
                if (isset($field[0])) {
                    $groupColumns[] = $field;
                } else {
                    $groupColumns[] = $fieldKey;

                    if (isset($field["cssStyle"])) {
                        $groupColumnsCss[$fieldKey] = $field["cssStyle"];
                    }

                    if (isset($field["totalText"])) {
                        $groupTotalText[$fieldKey] = $field["totalText"];

                    }
                    if (isset($field["totalCss"])) {
                        $groupTotalCss[$fieldKey] = $field["totalCss"];
                    }
                }
            }

            // Check if exist aggregates associated for no innecessary processing
            foreach ($showColumnKeys as $cKey) {
                if (isset($groups["fields"][$cKey]) && is_array($groups["fields"][$cKey])) {
                    foreach ($groups["fields"][$cKey] as $aggr) {
                        $existAggr = true;
                        break;
                    }
                }
            }
        }

        // If aggregates exist
        if (isset($groupColumns) && $existAggr) {

            $this->dataStore->popStart();

            while ($record = $this->dataStore->pop()) {
                $lastValue = "";

                foreach ($groupColumns as $cKey) {

                    if (strlen($lastValue) == 0) {
                        $fieldTest = $record[$cKey];
                    } else {
                        $fieldTest = $lastValue.'_'.$record[$cKey];
                    }

                    if (isset($groups["fields"][$cKey]) && is_array($groups["fields"][$cKey])) {
                        foreach ($groups["fields"][$cKey] as $aggr) {
                            if (is_array($aggr)) {
                                foreach ($aggr as $operator => $opFields) {
                                    $opFieldKeys = array_keys($opFields);
                                    $opFieldsValues = array_values($opFields);
                                    foreach ($opFieldsValues as $i => $v) {
                                        if (is_array($v)) {
                                            $opFieldName = $opFieldKeys[$i];
                                        } else {
                                            $opFieldName = $v;
                                        }
                                        // The field to operate with, exist in record?
                                        // If not do nothing , otherwise consume memory and is uselless
                                        if (isset($record[$opFieldName])) {
                                            if (!isset($fieldTest, $levelsAgg[$fieldTest][$opFieldName])) {
                                                switch ($operator) {
                                                    case "sum":
                                                    case "count":
                                                        $levelsAgg[$fieldTest][$opFieldName] = 0;
                                                        break;

                                                    case "avg":
                                                        $levelsAgg[$fieldTest][$opFieldName] = [
                                                            0,
                                                            0
                                                        ];
                                                        break;

                                                    case "min":
                                                        $levelsAgg[$fieldTest][$opFieldName] = INF;
                                                        break;

                                                    case "max":
                                                        $levelsAgg[$fieldTest][$opFieldName] = -INF;
                                                        break;
                                                }
                                            }

                                            switch ($operator) {
                                                case "sum":
                                                    $levelsAgg[$fieldTest][$opFieldName] += $record[$opFieldName];
                                                    break;

                                                case "count":
                                                    $levelsAgg[$fieldTest][$opFieldName] += 1;
                                                    break;

                                                case "dcount":
                                                    $levelsAgg[$fieldTest][$opFieldName][$record[$opFieldName]] = null;
                                                    break;

                                                case "avg":
                                                    $levelsAgg[$fieldTest][$opFieldName][0] += $record[$opFieldName];
                                                    $levelsAgg[$fieldTest][$opFieldName][1] += 1;
                                                    break;

                                                case "min":
                                                    if ($levelsAgg[$fieldTest][$opFieldName] > $record[$opField]) {
                                                        $levelsAgg[$fieldTest][$opFieldName] = $record[$opField];
                                                    }
                                                    break;

                                                case "max":
                                                    if ($levelsAgg[$fieldTest][$opFieldName] < $record[$opFieldName]) {
                                                        $levelsAgg[$fieldTest][$opFieldName] = $record[$opFieldName];
                                                    }
                                                    break;

                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $lastValue = $fieldTest;
                }
            }

            // Treat the avg case , is detected when the value assignbed for an aggregate
            // field is an array  and his first element is not null , if is null is a
            // dcount (distinct count) case.
            foreach ($levelsAgg as $opField => $aggFields) {
                foreach ($aggFields as $aggField => $fldValue) {
                    if (is_array($fldValue)) {
                        if (!isset($fldValue[0])) {
                            $levelsAgg[$opField][$aggField] = count($fldValue);
                        } else {
                            $levelsAgg[$opField][$aggField] = $fldValue[0] / $fldValue[1];
                        }
                    }
                }
            }
        }

        if (!$existAggr) {
            $footerStyle = "bottom";
            $groupStyle = "one_line";
        } else {
            $groupStyle = isset($groups["options"]["style"]) ? $groups["options"]["style"] : "";
            $footerStyle = isset($groups["options"]["showfooter"]) ? $groups["options"]["showfooter"] : "bottom";

            // Get the ordered field for each aggregate associated , this will simplify the process during rendering
            // because are preordered, the reason of this  , is because the user can put the aggregates fields in any
            // order but need to be renderened in the defined order in columns.
            $orderedAggFields = [];
            foreach ($groupColumns as $cKey) {
                if (isset($groups["fields"][$cKey]) && is_array($groups["fields"][$cKey])) {
                    foreach ($groups["fields"][$cKey] as $aggr) {
                        if (is_array($aggr)) {
                            $orderedFields = [];
                            foreach ($aggr as $operator => $opFields) {
                                $opFieldKeys = array_keys($opFields);
                                $opFieldsValues = array_values($opFields);
                                foreach ($opFieldsValues as $i => $v) {
                                    if (is_array($v)) {
                                        $opFieldName = $opFieldKeys[$i];
                                    } else {
                                        $opFieldName = $v;
                                    }

                                    $idx = array_search($opFieldName, $showColumnKeys);
                                    if ($idx !== false) {
                                        $orderedFields[$idx] = $opFieldName;

                                        if (is_array($v)) {
                                            $aggFieldOptions[$cKey][$opFieldName]["type"] = (isset($v["type"]) ? $v["type"] : null);
                                            $aggFieldOptions[$cKey][$opFieldName]["formatValue"] = (isset($v["formatValue"]) ? $v["formatValue"] : null);
                                            $aggFieldOptions[$cKey][$opFieldName]["cssStyle"] = (isset($v["cssStyle"]) ? $v["cssStyle"] : null);
                                        }
                                    }
                                }
                            }
                            ksort($orderedFields);
                            $orderedAggFields[$cKey] = $orderedFields;
                        }


                    }
                }
            }
        }

        $ret = [
            "levelsAgg" => $levelsAgg,
            "groupColumns" => $groupColumns,
            "groupColumnsCss" => $groupColumnsCss,
            "groupStyle" => $groupStyle,
            "footerStyle" => $footerStyle,
            "groupTotalText" => $groupTotalText,
            "groupTotalCss" => $groupTotalCss,
            "orderedAggFields" => $orderedAggFields,
            "aggFieldOptions" => $aggFieldOptions
        ];

        return $ret;
    }


    public function onRender() {

        $meta = $this->dataStore->meta();
        $showColumnKeys = [];

        if ($this->columns == []) {
            $this->dataStore->popStart();
            $row = $this->dataStore->pop();
            if ($row) {
                $showColumnKeys = array_keys($row);
            } else {
                if (count($meta["columns"]) > 0) {
                    $showColumnKeys = array_keys($meta["columns"]);
                }
            }
        } else {
            foreach ($this->columns as $cKey => $cValue) {

                if ($cKey === "{others}") {
                    $this->dataStore->popStart();
                    $row = $this->dataStore->pop();
                    $allKeys = array_keys($row);
                    foreach ($allKeys as $k) {
                        if (!in_array($k, $showColumnKeys)) {
                            $meta["columns"][$k] = array_merge($meta["columns"][$k], $cValue);
                            array_push($showColumnKeys, $k);
                        }
                    }
                } else {
                    if (gettype($cValue) == "array") {
                        if ($cKey === "#") {
                            $meta["columns"][$cKey] = [
                                "type" => "number",
                                "label" => "#",
                                "start" => 1,
                            ];
                        }

                        $meta["columns"][$cKey] = array_merge($meta["columns"][$cKey], $cValue);
                        if (!in_array($cKey, $showColumnKeys)) {
                            array_push($showColumnKeys, $cKey);
                        }
                    } else {
                        if ($cValue === "#") {
                            $meta["columns"][$cValue] = [
                                "type" => "number",
                                "label" => "#",
                                "start" => 1,
                            ];
                        }
                        if (!in_array($cValue, $showColumnKeys)) {
                            array_push($showColumnKeys, $cValue);
                        }
                    }

                }
            }
        }

        $cleanColumnKeys = [];
        foreach ($showColumnKeys as $key) {
            if (!in_array($key, $this->excludedColumns)) {
                array_push($cleanColumnKeys, $key);
            }
        }

        $time_start = microtime(true);
        // New Group Process
        $processData = $this->processGroups($showColumnKeys);

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        echo '<b>Total Execution Time:</b> '.$execution_time.' Secconds';


        if ($this->showFooter) {
            $this->footer = [];
            foreach ($showColumnKeys as $cKey) {
                $storage[$cKey] = null;
            }

            $this->dataStore->popStart();
            while ($row = $this->dataStore->pop()) {
                foreach ($showColumnKeys as $cKey) {
                    $method = Utility::get($meta["columns"][$cKey], "footer");
                    if ($method !== null) {
                        switch (strtolower($method)) {
                            case "sum":
                            case "avg":
                                if ($storage[$cKey] === null) {
                                    $storage[$cKey] = 0;
                                }
                                $storage[$cKey] += $row[$cKey];
                                break;
                            case "min":
                                if ($storage[$cKey] === null) {
                                    $storage[$cKey] = INF;
                                }
                                if ($storage[$cKey] > $row[$cKey]) {
                                    $storage[$cKey] = $row[$cKey];
                                }
                                break;
                            case "max":
                                if ($storage[$cKey] === null) {
                                    $storage[$cKey] = -INF;
                                }
                                if ($storage[$cKey] < $row[$cKey]) {
                                    $storage[$cKey] = $row[$cKey];
                                }
                                break;
                        }
                    }
                }
            }
            foreach ($showColumnKeys as $cKey) {
                $method = Utility::get($meta["columns"][$cKey], "footer");
                switch (strtolower($method)) {
                    case "sum":
                    case "min":
                    case "max":
                        $this->footer[$cKey] = $storage[$cKey];
                        break;
                    case "avg":
                        $this->footer[$cKey] = $storage[$cKey] / $this->dataStore->countData();
                        break;
                    case "count":
                        $this->footer[$cKey] = $this->dataStore->countData();
                        break;
                }
            }
        }


        //Prepare data
        $this->template("TableEx", [
            "showColumnKeys" => $showColumnKeys,
            "meta" => $meta,
            "groupColumns" => $processData["groupColumns"],
            "groupColumnsCss" => $processData["groupColumnsCss"],
            "groupTotalText" => $processData["groupTotalText"],
            "groupTotalCss" => $processData["groupTotalCss"],
            "footerStyle" => $processData["footerStyle"],
            "groupStyle" => $processData["groupStyle"],
            "levelsAgg" => $processData["levelsAgg"],
            "orderedAggFields" => $processData["orderedAggFields"],
            "aggFieldOptions" => $processData["aggFieldOptions"]

        ]);
    }

}