<?php
require_once "VeritradeAutos.php";


$params = array("MARCA"=>"MAZDA");

$veritradeAutos = new VeritradeAutos($params);
//$veritradeAutos->run()->export()->pdf(array(
//    "format"=>"A4",
//    "orientation"=>"portrait",
//   "margin"=>"10px"
//   # "header"=> array("contents"=> "<h5>Page {pageNum}/{numPages}</h5>","height"=>"11px")
//))->toBrowser("myfile.pdf");

$veritradeAutos->run()->render();
