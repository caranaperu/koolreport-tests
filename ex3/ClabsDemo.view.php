
<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\datagrid\DataTables;

?>

<?php
Table::create(array(
    "dataStore"=>$this->dataStore('sql_insumos'),
    "columns"=>array(
                     "insumo_codigo"=> array("label"=>"Codigo Insumo"),
                     "insumo_descripcion"=> array("label"=>"Descripcion"),
                     "insumo_history_fecha"=> array("label"=>"Fecha"),
                     #"insumo_history_id",
                     "tinsumo_descripcion"=> array("label"=>"Tipo Insumo"),
                     "tcostos_descripcion"=> array("label"=>"Tipo Costo"),
                     "insumo_merma"=> array("label"=>"Merma","cssStyle"=>"text-align:right"),
                     "unidad_medida_descripcion"=> array("label"=>"U.Medida"),
                     "insumo_costo"=> array("label"=>"Costo","cssStyle"=>"text-align:right"),
                     "insumo_precio_mercado"=> array("type"=>"number","label"=>"Precio Mercado","cssStyle"=>"text-align:right"),
                     "moneda_costo_descripcion"=> array("label"=>"Moneda")

    ),
    "rowGroup"=>true,
    "removeDuplicate"=>array("insumo_codigo","insumo_descripcion","tinsumo_descripcion","tcostos_descripcion",
                             "unidad_medida_descripcion"), # NOT SUPPORTED
    'paging' => array(
        'size' => 20,
        'maxDisplayedPages' => 5
    )
));
?>
</div>
