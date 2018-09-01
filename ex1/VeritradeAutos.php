<?php
require_once "../../../../koolreport/autoload.php";
use \koolreport\processes\Group;
use \koolreport\processes\Sort;
use \koolreport\processes\Limit;


class VeritradeAutos extends \koolreport\KoolReport
{
    use \koolreport\export\Exportable;

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
            ->query("SELECT MARCA,MODELO,ANO_REPORTE,count(*) as CONTADOR FROM veritrade group by MARCA,MODELO,ANO_REPORTE order by MARCA,MODELO,ANO_REPORTE")
            #->query("SELECT MARCA,MODELO,ANO_REPORTE,count(*) as CONTADOR FROM veritrade WHERE MARCA=:MARCA group by MARCA,MODELO,ANO_REPORTE order by MARCA,MODELO,ANO_REPORTE")
            #->params(array(":MARCA"=>$this->params["MARCA"]))
            #->pipe(new Limit(array(1000)))
            #->pipe(new \koolreport\processes\AggregatedColumn(array("total"=>array("sum","CONTADOR"))))
            ->pipe($this->dataStore('Veritrade'));
    }
}