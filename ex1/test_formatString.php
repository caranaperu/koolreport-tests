<?php
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 10/09/18
 * Time: 11:40 PM
 */

function formatString($str, $data) {
    return preg_replace_callback('#{{(\w+?)(\.(\w+?))?}}#', function($m) use ($data){
        return count($m) === 2 ? $data[$m[1]] : $data[$m[1]][$m[3]];
    }, $str);
}

$str = "This is {{name}}, I am {{age}} years old, I have a cat called {{pets.dog}}.";
$dict = [
    'name' => 'Jim',
    'age' => 20,
    'pets' => ['cat' => 'huang', 'dog' => 'bai']
];
echo formatString($str, $dict);


$arr = [12,13];

$templ = "I m a [0] and [1] person";

$r = array_walk($arr,function($i,$k) use(&$templ){
    $templ = str_replace("[$k]",$i,$templ);
} );

var_dump($templ);


$setup = [
    "name" => "ptable",
    //        "dataStore" => $this->dataStore('Veritrade'),
    "showFooter" => "bottom",
    "columns" => [
        "MARCA",
        "MODELO" => [
            "footer" => "sum",
            "footerText" => "Total: @value"
        ],
        "ANO"=>array("cssStyle"=>"text-align:right"),
        "CONTADOR" => [
            "footer" => "sum",
            "footerText" => "Total: @value",
            "cssStyle" => "text-align:right"
        ]
    ],
    "paging"=>array(
        "pageSize"=>20,
        "align"=>"center",
        "pageIndex"=>0,
    ),
    "fixedHeader" => true,

    "options" => [
        #"searching"=>true,
        #"paging"=>true,
        "fixedHeader" => true,
        "rowGroup" => true,
        "processing" => true,
        #"rowGroup"=>array('dataSrc'=> 0),
        #'scrollY' => 400  # WORKS> con o sin pagin (mejor no en paging)
        #"serverSide"=> true,
    ],
    #"deferRender"=> true, # NO WORK
    #"deferLoading"=>true, #No Work
    #"ordering" => true, # WORLS
    /*"removeDuplicate" => [
        "MARCA",
        "MODELO"
    ],*/
    "removeDuplicate2" => [
        "fields"=>  ["MARCA","MODELO","ANO" ],
        "options"=> ["showfooter"=>"bottom","style"=>"one_line"] // bottom en este caso es el correcto , top por ahora no funca
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
            "ANO"
            # "AÑO" => ["agg" => array("sum" => ["CONTADOR"])]
        ],
        "options"=> ["showfooter"=>"bottom","style"=>"one_line"] // one line funca en bottom
    ]
];

$showColumnKeys = ["MARCA","MODELO","ANO" ];

$groups = $setup["removeDuplicate"];

$fieldList = $groups["fields"];
foreach ($fieldList as $fieldKey => $field) {
    if (isset($field[0])) {
        $groupColumns[] = $field;
    } else {
        $groupColumns[] = $fieldKey;
    }
}

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
            print_r($orderedFields);
            $orderedAggFields[$fieldName] = $orderedFields;
        }
    }
}
print_r($orderedAggFields);