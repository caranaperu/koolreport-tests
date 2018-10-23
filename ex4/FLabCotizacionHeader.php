<?php
require_once "../../../../koolreport/autoload.php";


class FLabCotizacionHeader extends \koolreport\KoolReport {
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
        $sqlQry = "SELECT
              c.cotizacion_numero::INTEGER,
              to_char(c.cotizacion_fecha,'DD-MM-YYYY') as cotizacion_fecha,
              e.empresa_razon_social,
              e.empresa_direccion,
              e.empresa_ruc,
              e.empresa_telefonos,
              case when c.cotizacion_es_cliente_real = TRUE
              then
                   cl.cliente_razon_social
              else
                   e2.empresa_razon_social
              end as cliente_razon_social,
              case when c.cotizacion_es_cliente_real = TRUE
              then
                   cl.cliente_direccion
              else
                   e2.empresa_direccion
              end as cliente_direccion,
              case when c.cotizacion_es_cliente_real = TRUE
              then
                   cl.cliente_ruc
              else
                   e2.empresa_ruc
              end as cliente_ruc,
              case when c.cotizacion_es_cliente_real = TRUE
              then
                   cl.cliente_telefonos
              else
                   e2.empresa_telefonos
              end as cliente_telefono
            FROM tb_cotizacion c
            inner join tb_empresa e on e.empresa_id = c.empresa_id
            left join  tb_cliente cl on cl.cliente_id = c.cliente_id
            left join  tb_empresa e2 on e2.empresa_id= c.cliente_id
            where c.cotizacion_id = 4";

        $this->src('db_clabs')->query($sqlQry)->pipe($this->dataStore('sql_cotizacion_header'));
    }
}


