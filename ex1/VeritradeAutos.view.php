<?php

use \koolreport\widgets\koolphp\Table;
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
    Table::create([
        "name" => "ptable",
        "dataStore" => $this->dataStore('Veritrade'),
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
        "removeDuplicate" => [
            "MARCA",
            "MODELO"
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