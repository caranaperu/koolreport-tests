
<link rel="stylesheet" href="../../../../koolreport/clients/bootstrap/css/bootstrap.min.css" />

<style>
    .reppage-container {
        position:absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 21cm;
        height: 29.7cm;
    }

    .cot_header {
        border-style:outset;
        font-size: 12px;
    }

    .cot_header tr>td {
        padding-top:1px;
        padding-left:10px;
        padding-right:10px;
    }

    .koolphp-table table  {
        font-size: 12px;
    }

    .koolphp-table table>tbody>tr>td {
        line-height: normal;
        border-top: 0px;
        padding: 2px;
    }

    .koolphp-table table>tfoot>tr>td {
        padding: 0px;
    }


</style>

<div class="reppage-container">

<div class="text-center">
    <h2>Cotizacion Nro. <?php echo $this->dataStore("sql_cotizacion_header")->get(0,"cotizacion_numero"); ?></h2>
</div>
    <table width="100%" class="cot_header">
        <tr style="padding:20px;">
            <td colspan="3" width="70%"><?php echo $this->dataStore("sql_cotizacion_header")->get(0,"empresa_razon_social"); ?></td>
            <td align="right"><?php echo $this->dataStore("sql_cotizacion_header")->get(0,"cotizacion_fecha"); ?></td>
        </tr>
        <tr>
            <td colspan="4"><?php echo $this->dataStore("sql_cotizacion_header")->get(0,"empresa_direccion"); ?></td>
        </tr>
        <tr>
            <td colspan="4">RUC: <?php echo $this->dataStore("sql_cotizacion_header")->get(0,"empresa_ruc"); ?></td>
        </tr>
        <tr>
            <td colspan="4">Tlfs: <?php echo $this->dataStore("sql_cotizacion_header")->get(0,"empresa_telefonos"); ?></td>
        </tr>
    </table>
    <br/>
    <table width="100%" class="cot_header">
        <tr style="padding:20px;">
            <td colspan="3" width="70%">Cliente : <?php echo $this->dataStore("sql_cotizacion_header")->get(0,"cliente_razon_social"); ?></td>
            <td align="right">RUC : <?php echo $this->dataStore("sql_cotizacion_header")->get(0,"cliente_ruc"); ?></td>
        </tr>
        <tr>
            <td colspan="4"><?php echo $this->dataStore("sql_cotizacion_header")->get(0,"cliente_direccion"); ?></td>
        </tr>
    </table>
    <br/>


