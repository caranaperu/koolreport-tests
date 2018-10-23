
<?php
use \koolreport\widgets\koolphp\Table;
?>

<?php
Table::create(array(
    "dataStore"=>$this->dataStore('sql_cotizacion_detail'),
    "showFooter" => "bottom",
    "columns"=>array(
                     "insumo_codigo"=> array("label"=>"Codigo Insumo"),
                     "insumo_descripcion"=> array("label"=>"Descripcion"),
                     "cotizacion_detalle_cantidad"=> array("label"=>"Cantidad",
                                                           "cssStyle" => "text-align:right"),
                     "cotizacion_detalle_precio"=> array("label"=>"P.Unitario",
                                                         "cssStyle" => "text-align:right"),
                     "cotizacion_detalle_total"=> array("label"=>"Total",
                                                        "cssStyle" => "text-align:right"),
                     "igv"=> array("label"=>"IGV",
                                   "cssStyle" => "text-align:right"),
                     "total_item"=> array("label"=>"Total",
                                          "type"=>"number",
                                          "decimals"=>"2",
                                          "footer" => "sum",
                                          "footerText" => "Total: @value",
                                          "cssStyle" => "text-align:right",
                         )
    )
));
?>
</div>
