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

class TableEx extends Widget
{
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

	protected function resourceSettings()
	{
		return array(
			"library"=>array("jQuery"),
			"folder"=>"table",
			"js"=>array("table.js"),
			"css"=>array("table.css"),
		);
	}

	protected function onInit()
	{	
		$this->useLanguage();
		$this->useDataSource();
		$this->useAutoName("ktable");
		$this->clientEvents = Utility::get($this->params,"clientEvents");
		$this->columns = Utility::get($this->params,"columns",array());
		
		if($this->dataStore==null)
		{
			$data = Utility::get($this->params,"data");
			if(is_array($data))
			{
				if(count($data)>0)
				{
					$this->dataStore = new DataStore;
					$this->dataStore->data($data);
					$row = $data[0];
					$meta = array("columns"=>array());
					foreach($row as $cKey=>$cValue)
					{
						$meta["columns"][$cKey] = array(
							"type"=>Utility::guessType($cValue),
						);
					}
					$this->dataStore->meta($meta);	
				}
				else
				{
					$this->dataStore = new DataStore;
					$this->dataStore->data(array());
					$metaColumns = array();
					foreach($this->columns as $cKey=>$cValue)
					{
						if(gettype($cValue)=="array")
						{
							$metaColumns[$cKey] = $cValue;
						}
						else
						{
							$metaColumns[$cValue] = array();
						}
					}
					$this->dataStore->meta(array("columns"=>$metaColumns));
				}	
			}
			if($this->dataStore==null)
			{
				throw new \Exception("dataSource is required for Table");
				return;	
			}
		}

		if($this->dataStore->countData()==0 && count($this->dataStore->meta()["columns"])==0)
		{
			$meta = array("columns"=>array());
			foreach($this->columns as $cKey=>$cValue)
			{
				if(gettype($cValue)=="array")
				{
					$meta["columns"][$cKey] = $cValue;
				}
				else
				{
					$meta["columns"][$cValue] = array();
				}
			}
			$this->dataStore->meta($meta);
		}

		$this->removeDuplicate = Utility::get($this->params,"removeDuplicate",array());
		$this->cssClass = Utility::get($this->params,"cssClass",array());
		$this->excludedColumns = Utility::get($this->params,"excludedColumns",array());

		$this->showFooter = Utility::get($this->params,"showFooter");
		$this->showHeader = Utility::get($this->params,"showHeader",true);
		

		$this->paging = Utility::get($this->params,"paging");
		if($this->paging!==null)
		{
			$this->paging = array(
				"pageSize"=>Utility::get($this->paging,"pageSize",10),
				"pageIndex"=>Utility::get($this->paging,"pageIndex",0),
				"align"=>Utility::get($this->paging,"align","left"),
			);
			$this->paging["itemCount"]=$this->dataStore->countData();
			$this->paging["pageCount"]=ceil($this->paging["itemCount"]/$this->paging["pageSize"]);
		}

		//Header Group
		$this->headers = Utility::get($this->params,"headers",array());
		$this->responsive = Utility::get($this->params,"responsive",false);
	}

	protected function formatValue($value,$format,$row=null)
	{
        $formatValue = Utility::get($format,"formatValue",null);

        if(is_string($formatValue))
        {
            eval('$fv="'.str_replace('@value','$value',$formatValue).'";');
            return $fv;
        }
        else if(is_callable($formatValue))
        {
            return $formatValue($value,$row);
        }
		else
		{
			return Utility::format($value,$format);
		}
	}

	protected function getShowColumnKeys() {
        $meta = $this->dataStore->meta();
        $showColumnKeys = array();

        if($this->columns==array())
        {
            $this->dataStore->popStart();
            $row = $this->dataStore->pop();
            if($row)
            {
                $showColumnKeys = array_keys($row);
            }
            else if(count($meta["columns"])>0)
            {
                $showColumnKeys = array_keys($meta["columns"]);
            }
        }
        else
        {
            foreach($this->columns as $cKey=>$cValue)
            {

                if($cKey==="{others}")
                {
                    $this->dataStore->popStart();
                    $row = $this->dataStore->pop();
                    $allKeys = array_keys($row);
                    foreach($allKeys as $k)
                    {
                        if(!in_array($k,$showColumnKeys))
                        {
                            $meta["columns"][$k] = array_merge($meta["columns"][$k],$cValue);
                            array_push($showColumnKeys,$k);
                        }
                    }
                }
                else
                {
                    if(gettype($cValue)=="array")
                    {
                        if($cKey==="#")
                        {
                            $meta["columns"][$cKey] = array(
                                "type"=>"number",
                                "label"=>"#",
                                "start"=>1,
                            );
                        }

                        $meta["columns"][$cKey] =  array_merge($meta["columns"][$cKey],$cValue);
                        if(!in_array($cKey,$showColumnKeys))
                        {
                            array_push($showColumnKeys,$cKey);
                        }
                    }
                    else
                    {
                        if($cValue==="#")
                        {
                            $meta["columns"][$cValue] = array(
                                "type"=>"number",
                                "label"=>"#",
                                "start"=>1,
                            );
                        }
                        if(!in_array($cValue,$showColumnKeys))
                        {
                            array_push($showColumnKeys,$cValue);
                        }
                    }

                }
            }
        }

        $cleanColumnKeys = array();
        foreach($showColumnKeys as $key)
        {
            if(!in_array($key,$this->excludedColumns))
            {
                array_push($cleanColumnKeys,$key);
            }
        }
        return $cleanColumnKeys;

    }

    protected  function processGroups($showColumnKeys) {

        $levelsAgg = [];

        $groups = Utility::get($this->params,"removeDuplicate");

        if (isset($groups)) {
            $groupStyle = isset($groups["options"]["style"]) ? $groups["options"]["style"] : "";
            $footerStyle = isset($groups["options"]["showfooter"]) ? $groups["options"]["showfooter"] : "bottom";

            $fieldList = $groups["fields"];
            foreach ($fieldList as $fieldKey => $field) {
                if (isset($field[0])) {
                    $groupColumns[] = $field;
                } else {
                    $groupColumns[] = $fieldKey;

                    if (isset($field["cssStyle"])) {
                        $groupColumnsCss[$fieldKey] = $field["cssStyle"];
                    }
                }
            }
        }

        if (isset($groupColumns)) {

            $this->dataStore->popStart();

            while ($record = $this->dataStore->pop()) {
                $lastValue = "";

                foreach ($showColumnKeys as $cKey) {

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
                                            if ($levelsAgg[$fieldTest][$opField] > $record[$opField]) {
                                                $levelsAgg[$fieldTest][$opField] = $record[$opField];
                                            }
                                            break;

                                        case "max":
                                            if ($levelsAgg[$fieldTest][$opField] < $record[$opField]) {
                                                $levelsAgg[$fieldTest][$opField] = $record[$opField];
                                            }
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

        $groupStyle = isset($groups["options"]["style"]) ? $groups["options"]["style"] : "";
        $footerStyle = isset($groups["options"]["showfooter"]) ? $groups["options"]["showfooter"] : "bottom";

        // Get the ordered field for each aggregate associated , this will simplify the process during rendering
        // because are preordered, the reason of this  , is because the user can put the aggregates fields in any
        // order but need to be renderened in the defined order in columns.
        foreach($groups as $field) {
            foreach ($field as $fieldName => $fieldaggrs) {
                $orderedFields = [];
                foreach ($fieldaggrs as $aggr) {
                    foreach ($aggr as $operator => $opFields) {
                        foreach ($opFields as $fld) {
                            $idx = array_search($fld, $showColumnKeys);
                            if ($idx !== false) {
                                $orderedFields[$idx] = $fld;
                            }
                        }
                    }
                    ksort($orderedFields);
                    $orderedAggFields[$fieldName] = $orderedFields;
                }
            }
        }


        $ret = ["levelsAgg"=>$levelsAgg,"groupColumns"=>$groupColumns,"groupColumnsCss"=>$groupColumnsCss,"groupStyle"=>$groupStyle,"footerStyle"=>$footerStyle,"orderedAggFields"=>$orderedAggFields];
        return $ret;
    }


    public function onRender()
	{

        $meta = $this->dataStore->meta();
        $showColumnKeys = array();

        if($this->columns==array())
        {
            $this->dataStore->popStart();
            $row = $this->dataStore->pop();
            if($row)
            {
                $showColumnKeys = array_keys($row);
            }
            else if(count($meta["columns"])>0)
            {
                $showColumnKeys = array_keys($meta["columns"]);
            }
        }
        else
        {
            foreach($this->columns as $cKey=>$cValue)
            {

                if($cKey==="{others}")
                {
                    $this->dataStore->popStart();
                    $row = $this->dataStore->pop();
                    $allKeys = array_keys($row);
                    foreach($allKeys as $k)
                    {
                        if(!in_array($k,$showColumnKeys))
                        {
                            $meta["columns"][$k] = array_merge($meta["columns"][$k],$cValue);
                            array_push($showColumnKeys,$k);
                        }
                    }
                }
                else
                {
                    if(gettype($cValue)=="array")
                    {
                        if($cKey==="#")
                        {
                            $meta["columns"][$cKey] = array(
                                "type"=>"number",
                                "label"=>"#",
                                "start"=>1,
                            );
                        }

                        $meta["columns"][$cKey] =  array_merge($meta["columns"][$cKey],$cValue);
                        if(!in_array($cKey,$showColumnKeys))
                        {
                            array_push($showColumnKeys,$cKey);
                        }
                    }
                    else
                    {
                        if($cValue==="#")
                        {
                            $meta["columns"][$cValue] = array(
                                "type"=>"number",
                                "label"=>"#",
                                "start"=>1,
                            );
                        }
                        if(!in_array($cValue,$showColumnKeys))
                        {
                            array_push($showColumnKeys,$cValue);
                        }
                    }

                }
            }
        }

        $cleanColumnKeys = array();
        foreach($showColumnKeys as $key)
        {
            if(!in_array($key,$this->excludedColumns))
            {
                array_push($cleanColumnKeys,$key);
            }
        }

        $time_start = microtime(true);
        // New Group Process
        $processData = $this->processGroups($showColumnKeys);

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        echo '<b>Total Execution Time:</b> '.$execution_time.' Secconds';


        if($this->showFooter)
		{
			$this->footer = array();
			foreach($showColumnKeys as $cKey)
			{
				$storage[$cKey]=null;
			}
			
			$this->dataStore->popStart();
			while($row = $this->dataStore->pop())
			{
				foreach($showColumnKeys as $cKey)
				{
					$method = Utility::get($meta["columns"][$cKey],"footer");
					if($method!==null)
					{
						switch(strtolower($method))
						{
							case "sum":
							case "avg":
								if($storage[$cKey]===null)
								{
									$storage[$cKey] = 0;
								}
								$storage[$cKey]+=$row[$cKey];
							break;
							case "min":
								if($storage[$cKey]===null)
								{
									$storage[$cKey] = INF;
								}
								if($storage[$cKey]>$row[$cKey])
								{
									$storage[$cKey]=$row[$cKey];
								}
							break;
							case "max":
								if($storage[$cKey]===null)
								{
									$storage[$cKey] = -INF;
								}
								if($storage[$cKey]<$row[$cKey])
								{
									$storage[$cKey]=$row[$cKey];
								}
							break;
						}
					}
				}
			}
			foreach($showColumnKeys as $cKey)
			{
				$method = Utility::get($meta["columns"][$cKey],"footer");
				switch(strtolower($method))
				{
					case "sum":
					case "min":
					case "max":
						$this->footer[$cKey] = $storage[$cKey];	
					break;
					case "avg":
						$this->footer[$cKey] = $storage[$cKey]/$this->dataStore->countData();
					break;
					case "count":
						$this->footer[$cKey] = $this->dataStore->countData();
					break;
				}
			}
		}
		
		
		//Prepare data
		$this->template("TableEx",array(
			"showColumnKeys"=>$showColumnKeys,
			"meta"=>$meta,
            "groupColumns"=>$processData["groupColumns"],
            "groupColumnsCss"=>$processData["groupColumnsCss"],
            "footerStyle"=>$processData["footerStyle"],
            "groupStyle"=>$processData["groupStyle"],
            "levelsAgg"=>$processData["levelsAgg"],
            "orderedAggFields"=>$processData["orderedAggFields"]
		));
	}	

}