
<?php
use \koolreport\widgets\koolphp\Table;
?>

<?php
Table::create(array(
    "dataStore"=>$this->dataStore('sql_historico_costos_detail'),
    "showFooter" => "bottom",
    "columns"=>array(

                     "insumo_history_fecha"=> array("label"=>"Fecha"),
                     "insumo_descripcion"=> array("label"=>"Descripcion"),
                     "tcostos_descripcion"=> array("label"=>"T.Costo"),
                     "insumo_merma"=> array("label"=>"Merma",
                                                         "cssStyle" => "text-align:right"),
                     "insumo_costo"=> array("label"=>"Costo",
                                                        "cssStyle" => "text-align:right"),
                     "insumo_precio_mercado"=> array("label"=>"Precio Mercado",
                                          "type"=>"number",
                                          "decimals"=>"2",
                                          "cssStyle" => "text-align:right"
                         ),
                     "moneda_costo_descripcion"=> array("label"=>"Moneda Costo"),
    )
));
?>
</div>
