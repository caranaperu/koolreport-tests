<?php
require_once "ClabsDemoHeader.php";
require_once "ClabsDemo.php";

$clabsDemoHeader = new ClabsDemoHeader();
$clabsDemoHeader->run()->render();

$clabsDemo = new ClabsDemo();
$clabsDemo->run()->render();

#$clabsDemo = new ClabsDemo();
#$clabsDemo->run()->render();