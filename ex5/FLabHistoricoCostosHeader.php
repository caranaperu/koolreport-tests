<?php
require_once "../../../../koolreport/autoload.php";


class FLabHistoricoCostosHeader extends \koolreport\KoolReport {
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
                           i.insumo_descripcion,
                           case when i.insumo_tipo != 'PR' then
                                ti.tinsumo_descripcion
                           else 'Producto'
                           end as tinsumo_descripcion,
                           :from_date as from_date,
                           :to_date as to_date
                    from tb_insumo i
                    inner join tb_tinsumo ti on ti.tinsumo_codigo = i.tinsumo_codigo
                    where i.insumo_id = :insumo_id";
        $this->src('db_clabs')->query($sqlQry)
            ->params(array(
                ":insumo_id"=>$this->params["insumo_id"],
                ":from_date"=>$this->params["from_date"],
                ":to_date"=>$this->params["to_date"]
            ))
            ->pipe($this->dataStore('sql_historico_costos_header'));
    }
}


