<?php

#use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\koolphp\TableEx;
use \koolreport\widgets\google\BarChart;
use \koolreport\datagrid\DataTables;

?>

<html>
<link rel="stylesheet" href="../../../../koolreport/clients/bootstrap/css/bootstrap.min.css"/>

<style>
    .reppage-container {
        /*position: absolute;
        left: 50%;
        transform: translateX(-50%);*/
        /*width: 21cm;
        height: 29.7cm;*/
        border-style: outset;
        overflow: auto;
    }

    .koolphp-table table {
        font-size: 11px;
    }

    .koolphp-table table > tbody > tr > td {
        line-height: normal;
        border-top: 0px;
        padding: 2px;
    }

    .krPivotMatrix table {
        font-size: 11px;
    }

    .krPivotMatrix td {
        height: 20px;
    }

    .krpmField.krpmRowField.btn, .krpmField.krpmColumnField.btn, .krpmField.krpmDataField.btn {
        font-size: 12px;
    }

    .krPivotMatrix table > tbody > tr > td {
        line-height: normal;
        padding: 2px;
    }

    /* Para exportar a pdf*/

    .always-show {
        display: block;
    }

    @media screen {
        .only-print {
            display: none;
        }
    }

    @media print {
        .only-print {
            display: block;
        }

    }


</style>

<!--<style>
    tbody tr:nth-child(even) {
        background-color: #fff;
    }

    tbody tr:nth-child(odd) {
        background-color: #e5e5e5;
    }

    table {
        font-size: 11px;
    }
</style>-->

<body>

<div class="reppage-container">

    <div class="page-header" style='height:100px'>
        <span class="only-print">Pagina :{pageNum}/{numPages}</span>
        <span class="always-show" style="text-align: center;">
            <h1>Importaciones x Marca</h1>
            <h4><?php echo $this->dataStore("Veritrade")->get(0, "MARCA"); ?></h4>
        </span>
    </div>


    <?php
    #BarChart::create(array(
    #    "dataStore"=>$this->dataStore('Veritrade'),
    #    "width"=>"100%",
    #    "height"=>"500px",
    #    "columns"=>array("MARCA","CONTADOR"),
    #    "options"=>array(
    #        "title"=>"Sales By Customer"
    #    )
    #));
    ?>
    <?php
    TableEx::create([
        "name" => "ptable",
        "dataStore" => $this->dataStore('Veritrade'),
        "showFooter" => "bottom",
        "columns" => [
            "MARCA",
            "MODELO" => [
                "footer" => "count",
                "footerText" => "Total: @value"
            ],
            "ANO" => ["cssStyle" => "text-align:right;color:blue;"],
            "CONTADOR" => [
                "footer" => "sum",
                "footerText" => "Total: @value",
                "cssStyle" => "text-align:right;color:blue;"
            ],
            "CC" => "#"
        ],
        "paging" => [
            "pageSize" => 40,
            "align" => "center",
            "pageIndex" => 0,
        ],
        "removeDuplicate22" => [
            "fields" => [
                "MARCA",
                "MODELO"
            ]
        ],
        "removeDuplicate" => [
            "fields" => [
                "MARCA" => [
                    "agg" => [
                        "sum" => [
                            "CONTADOR" => [
                                "formatValue" => "$ @value",
                                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                            ],
                            "SUMADOR"
                        ],
                        "max" => [
                            "ANO" => [
                                "formatValue"=>function($value) {return "[".$value."]";},
                                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                            ]
                        ],
                        "dcount" => ["MODELO" => ["cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"]]
                    ],
                    "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:moccasin;",
                    "totalText" => "Total @fname :",
                    "totalCss" => "text-align:left;font-weight: bold;background-color:moccasin;"
                ],
                //"MARCA",
                "MODELO" => [
                    "agg" => [
                        "avg" => ["CONTADOR"],
                        "count" => ["ANO"=> [
                            "formatValue"=>function($value) {return "<".$value.">";},
                            "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:yellow;"
                        ]]
                    ],
                    "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:papayawhip;",
                    "totalText" => "Total @fname :",
                    "totalCss" => "text-align:left;font-weight: bold;background-color:papayawhip;"
                ],
                // "MODELO",
                /*"ANO"=>["agg" => array("sum" => ["CONTADOR"]),
                        "cssStyle" => "text-align:right;color:red;font-weight: bold;",
                        "totalCss"=>"text-align:left;font-weight: bold;background-color:moccasin;"

                ]*/
                // "ANO"
            ],
            // Top solo fiunciona cuando los campos de grupo van de izquierda a derecha, de lo contrario usar
            // bottom.
            "options" => [
                "showfooter" => "bottom",
                "style" => "one_line"
            ]
            // one line funca en bottom
        ],
        "cssClass" => [
            "tr" => "testTrClass",
            "td" => "testTDClass"
        ]
    ]);
    ?>
    <?php
    #Table::create(array(
    #    "dataStore"=>$this->dataStore('Veritrade'),
    #    "showFooter"=>"bottom",
    #    "columns"=>array("MARCA",
    #                     "ANO_REPORTE",
    #                     "CONTADOR"=>array(
    #                         "footer"=>"sum",
    #                         "footerText"=>"Total: @value",
    #                         "cssStyle"=>"text-align:right"
    #                     )),
    #"paging"=>array(
    #    "pageSize"=>20,
    #    "pageIndex"=>0,
    #    "align"=>"center"
    #)
    #));
    ?>
</div>

</body>
</html>