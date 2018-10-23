<?php
require_once "../../../../koolreport/autoload.php";


class FLabHistoricoCostosDetail extends \koolreport\KoolReport {
    public function settings() {
        return [
            "dataSources" => [
                "db_clabs" => [
                    'host' => '192.168.0.5',
                    'username' => 'postgres',
                    'password' => 'postgres',
                    'dbname' => 'db_clabs',
                    'class' => "\koolreport\datasources\PostgreSQLDataSource"
                ],
            ]
        ];
    }

    public function setup() {
        $sqlQry = "select i.insumo_codigo,
               i.insumo_descripcion::TEXT,
               to_char(insumo_history_fecha,'DD-MM-YYYY hh24:mi:ss') as insumo_history_fecha,
               case when ih.insumo_tipo != 'PR' then
                    ti.tinsumo_descripcion
               else 'Producto'
               end as tinsumo_descripcion,
               case when ih.insumo_tipo != 'PR' then
                    tc.tcostos_descripcion::TEXT
               else NULL
               end as tcostos_descripcion,
               um.unidad_medida_descripcion,
               case when tc.tcostos_indirecto = TRUE then
                    null
               else to_char(ih.insumo_merma,'9990.9999')
               end as insumo_merma,
               to_char(ih.insumo_costo,'999999990.9999') as insumo_costo,
               mo.moneda_descripcion::TEXT as moneda_costo_descripcion,
               case when  tc.tcostos_indirecto = TRUE then
                    null
               else to_char(ih.insumo_precio_mercado,'9999990.99')
               end as insumo_precio_mercado
        from tb_insumo_history ih
        inner join tb_insumo i on i.insumo_id = ih.insumo_id
        inner join tb_tinsumo ti on ti.tinsumo_codigo = ih.tinsumo_codigo
        inner join tb_tcostos tc on tc.tcostos_codigo = ih.tcostos_codigo
        inner join tb_unidad_medida um on um.unidad_medida_codigo = ih.unidad_medida_codigo_costo
        inner join tb_moneda mo on mo.moneda_codigo = ih.moneda_codigo_costo
        where ih.insumo_id = :insumo_id and insumo_history_fecha between  :from_date  and  :to_date
        order by insumo_history_fecha";

        $this->src('db_clabs')->query($sqlQry)
            ->params(array(
                ":insumo_id"=>$this->params["insumo_id"],
                ":from_date"=>$this->params["from_date"],
                ":to_date"=>$this->params["to_date"]
            ))
            ->pipe($this->dataStore('sql_historico_costos_detail'));
    }
}


