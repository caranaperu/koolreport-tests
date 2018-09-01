<?php
require_once "../../../../koolreport/autoload.php";


class ClabsDemoHeader extends \koolreport\KoolReport
{
    public function settings()
    {
        return array(
            "dataSources"=>array(
                "db_clabs"=>array(
                    'host' => '192.168.0.5',
                    'username' => 'postgres',
                    'password' => 'postgres',
                    'dbname' => 'db_clabs',
                    'class' => "\koolreport\datasources\PostgreSQLDataSource"
                ),
            )
        );
    }

    public function setup()
    {
        $sqlQry = "select empresa_razon_social 
                      from tb_insumo i
                    inner join tb_empresa e on e.empresa_id = i.empresa_id where insumo_id=1";

        $this->src('db_clabs')
            ->query($sqlQry)
            ->pipe($this->dataStore('sql_get_empresa_name'));
    }
}


