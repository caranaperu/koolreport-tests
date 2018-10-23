<?php
require_once "ClabsDemoHeader.php";
require_once "ClabsDemo.php";

$clabsDemoHeader = new FLabHistoricoCostosHeader();
$clabsDemoHeader->run()->render();

$clabsDemo = new FLabHistoricoCostosDetail();
$clabsDemo->run()->render();

#$clabsDemo = new FLabHistoricoCostosDetail();
#$clabsDemo->run()->render();