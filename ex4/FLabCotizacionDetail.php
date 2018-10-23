<?php
require_once "../../../../koolreport/autoload.php";


class FLabCotizacionDetail extends \koolreport\KoolReport {
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
                      i.insumo_codigo::TEXT,
                      i.insumo_descripcion::TEXT,
                      to_char(cd.cotizacion_detalle_cantidad,'9990.99') as cotizacion_detalle_cantidad,
                      to_char(cd.cotizacion_detalle_precio,'999990.99') as cotizacion_detalle_precio,
                      to_char((cd.cotizacion_detalle_total*(igv.igv_valor/100.00)),'999990.99') as igv,
                      to_char(cd.cotizacion_detalle_total,'999990.99') as cotizacion_detalle_total,
                      to_char(cd.cotizacion_detalle_total+(cd.cotizacion_detalle_total*(igv.igv_valor/100.00)),'999999.99') as total_item,
                      m.moneda_simbolo::TEXT,
                      igv.igv_valor::FLOAT
                    FROM tb_cotizacion c
                    inner join tb_cotizacion_detalle cd on cd.cotizacion_id = c.cotizacion_id
                    inner join tb_insumo i on i.insumo_id = cd.insumo_id
                    inner join tb_moneda m on m.moneda_codigo = c.moneda_codigo
                    left join tb_igv igv on c.cotizacion_fecha between igv.fecha_desde and igv.fecha_hasta
                    where c.cotizacion_id = 4";

        $this->src('db_clabs')->query($sqlQry)->pipe($this->dataStore('sql_cotizacion_detail'));
    }
}


