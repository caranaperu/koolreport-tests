<?php
use \koolreport\futurelabs\TableEx;
?>

<html>
<link rel="stylesheet" href="../../../../koolreport/clients/bootstrap/css/bootstrap.min.css"/>
<style>

.table {
    font-size: 11px;
}

.table > tbody > tr > td {
    line-height: normal;
    border-top: 0px;
    padding: 2px;
}

</style>

<body>
<div class="reppage-container">

    <?php
    TableEx::create([
        "dataStore" => $this->dataStore('Veritrade'),
        "columns" => [
            "MARCA","MODELO","ANO","CONTADOR","CC" => "#"
        ],
        "removeDuplicate22" => [
            "fields" => [
                "MARCA",
                "MODELO"
            ],
            "options" => [
                "showfooter" => "top",
                "style" => "one_linse"
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
                            ]
                        ],
                        "max" => [
                            "ANO" => [
                                "formatValue" => function ($value) {
                                    return "[".$value."]";
                                },
                                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                            ]
                        ],
                        "dcount" => [
                                "MODELO" => [
                                        "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                                ]
                        ]
                    ],
                    "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:moccasin;",
                    "totalText" => "Total @fname :",
                    "totalCss" => "text-align:left;font-weight: bold;background-color:moccasin;"
                ],
                "MODELO" => [
                    "agg" => [
                        "avg" => ["CONTADOR"],
                        "count" => [
                            "ANO" => [
                                "formatValue" => function ($value) {
                                    return "<".$value.">";
                                },
                                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:yellow;"
                            ]
                        ]
                    ],
                    "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:papayawhip;",
                    "totalText" => "Total @fname :",
                    "totalCss" => "text-align:left;font-weight: bold;background-color:papayawhip;"
                ]
            ],
            "options" => [
                "showfooter" => "top",
                //"style" => "one_line"
            ]
        ]
    ]);
    ?>

</div>

</body>
</html>