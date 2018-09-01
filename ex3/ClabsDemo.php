<?php
require_once "../../../../koolreport/autoload.php";


class ClabsDemo extends \koolreport\KoolReport {
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
        $sqlQry = "
        select 
       insumo_codigo::TEXT,
       insumo_descripcion::TEXT,
       to_char(insumo_history_fecha,'YYYY-MM-DD') as insumo_history_fecha,
       insumo_history_id,
       tinsumo_descripcion::TEXT,
       tcostos_descripcion::TEXT,
       unidad_medida_descripcion::TEXT,
       insumo_merma::NUMERIC,
       insumo_costo::NUMERIC,
       moneda_costo_descripcion::TEXT,
       insumo_precio_mercado::NUMERIC
from sp_get_historico_costos_for_insumo(1, '1999-01-01', '2091-01-01', null, null)";

        $this->src('db_clabs')->query($sqlQry)->pipe($this->dataStore('sql_insumos'));
    }
}


