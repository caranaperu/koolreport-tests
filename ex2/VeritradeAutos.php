<?php
require_once "../../../../koolreport/autoload.php";
use \koolreport\processes\Group;
use \koolreport\processes\Sort;
use \koolreport\processes\Limit;
use \koolreport\pivot\processes\Pivot;



class VeritradeAutos extends \koolreport\KoolReport
{
    public function settings()
    {
        return array(
            "dataSources"=>array(
                "sqlserver"=>array(
                    'host' => '192.168.0.5',
                    'username' => 'sa',
                    'password' => 'Melivane100',
                    'dbname' => 'veritrade',
                    'class' => "\koolreport\datasources\SQLSRVDataSource"
                ),
            )
        );
    }

    public function setup()
    {
        $this->src('sqlserver')
            ->query("select top 1000 * from (SELECT TOP 1000000 MARCA,MODELO,ANO_REPORTE,MES_REPORTE,count(*) as CONTADOR FROM veritrade where MARCA is not null group by MARCA,MODELO,ANO_REPORTE,MES_REPORTE order by marca) xx")
            ->pipe(new Pivot(array(
                "dimensions" => array(
                    "column" => "ANO_REPORTE, MES_REPORTE",
                    "row" => "MARCA,MODELO"
                ),
                "aggregates"=>array(
                    "sum" => "CONTADOR"
                )
            )))
            ->pipe($this->dataStore('Veritrade'));
    }
}


