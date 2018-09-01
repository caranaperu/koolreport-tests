
<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\datagrid\DataTables;

?>

<link rel="stylesheet" href="../../../../koolreport/clients/bootstrap/css/bootstrap.min.css" />

<style>
    .reppage-container {
        position:absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 21cm;
        height: 29.7cm;
        border-style:outset;
    }

    .koolphp-table table  {
        font-size: 11px;
    }

    .koolphp-table table>tbody>tr>td {
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

    .krpmField.krpmRowField.btn , .krpmField.krpmColumnField.btn , .krpmField.krpmDataField.btn
    {
        font-size: 12px;
    }


    .krPivotMatrix table>tbody>tr>td {
        line-height: normal;
        padding: 2px;
    }

</style>

<div class="reppage-container">

<div class="text-center">
    <h3>Historico Costo x Insumo</h3>
    <h4>
        <?php echo $this->dataStore("sql_get_empresa_name")->get(0,"empresa_razon_social"); ?>
    </h4>
</div>
<hr/>

