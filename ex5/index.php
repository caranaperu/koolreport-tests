<?php
require_once "FLabHistoricoCostosHeader.php";
require_once "FLabHistoricoCostosDetail.php";

$params = array(
    "from_date"=>"2001-01-01",
    "to_date"=>"2019-01-01",
    "insumo_id"=>1
        );

$flabDemoHeader = new FLabHistoricoCostosHeader($params);
$flabDemoHeader->run()->render();

$flabDemo = new FLabHistoricoCostosDetail($params);
$flabDemo->run()->render();
