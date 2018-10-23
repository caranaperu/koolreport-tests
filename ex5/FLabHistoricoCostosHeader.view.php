
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
        font-size: 12px;
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

    <div class="page-header" style='height:100px'>
        <span>Pagina :{pageNum}/{numPages}</span>
        <span style="text-align: center;">
            <h5>Historico De Costos</h5>
            <h5>Entre <?php echo $this->dataStore("sql_historico_costos_header")->get(0,"from_date");?> al <?php echo $this->dataStore("sql_historico_costos_header")->get(0,"to_date");?></h5>
            <h5><?php echo $this->dataStore("sql_historico_costos_header")->get(0,"insumo_descripcion");?></h5>
        </span>
    </div>

    <table width="100%" class="cot_header">
        <tr style="padding:20px;">
            <td colspan="3" width="70%">Codigo: <?php echo $this->dataStore("sql_historico_costos_header")->get(0,"insumo_codigo"); ?></td>
            <td align="right">Tipo: <?php echo $this->dataStore("sql_historico_costos_header")->get(0,"tinsumo_descripcion"); ?></td>
        </tr>
    </table>
    <br/>


