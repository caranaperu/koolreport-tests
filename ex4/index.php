<?php
require_once "FLabCotizacionHeader.php";
require_once "FLabCotizacionDetail.php";

$flabDemoHeader = new FLabHistoricoCostosHeader();
$flabDemoHeader->run()->render();

$flabDemo = new FLabHistoricoCostosDetail();
$flabDemo->run()->render();
