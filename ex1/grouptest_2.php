<?php

$data = array(
    array("MARCA"=>"Toyota","XX"=>"XX01","MODELO"=>"Corolla","YY"=>"XX07","AÑO"=>2010,"CONTADOR"=>10),
    array("MARCA"=>"Toyota","XX"=>"XX01","MODELO"=>"Corolla","YY"=>"XX07","AÑO"=>2010,"CONTADOR"=>20),
    array("MARCA"=>"Toyota","XX"=>"XX02","MODELO"=>"Corolla","YY"=>"XX07","AÑO"=>2011,"CONTADOR"=>15),
    array("MARCA"=>"Toyota","XX"=>"XX03","MODELO"=>"Audax","YY"=>"XX07","AÑO"=>2011,"CONTADOR"=>20),
    array("MARCA"=>"Toyota","XX"=>"XX04","MODELO"=>"Auris","YY"=>"XX07","AÑO"=>2012,"CONTADOR"=>30),
    array("MARCA"=>"Toyota","XX"=>"XX05","MODELO"=>"Auris","YY"=>"XX07","AÑO"=>2013,"CONTADOR"=>10),
    array("MARCA"=>"Nissan","XX"=>"XX06","MODELO"=>"Agensa","YY"=>"XX07","AÑO"=>2019,"CONTADOR"=>11),
    array("MARCA"=>"Nissan","XX"=>"XX07","MODELO"=>"March","YY"=>"XX07","AÑO"=>2019,"CONTADOR"=>11),
    array("MARCA"=>"Bmw","XX"=>"XX08","MODELO"=>"550I","YY"=>"XX07","AÑO"=>2018,"CONTADOR"=>20),
    array("MARCA"=>"Bmw","XX"=>"XX08","MODELO"=>"550I","YY"=>"XX07","AÑO"=>2019,"CONTADOR"=>20),
    array("MARCA"=>"Bmw","XX"=>"XX08","MODELO"=>"550Z","YY"=>"XX07","AÑO"=>2018,"CONTADOR"=>20),
    array("MARCA"=>"Bmw","XX"=>"XX08","MODELO"=>"550Z","YY"=>"XX07","AÑO"=>2018,"CONTADOR"=>22),
);

$fields = array(
    "MARCA","MODELO","XX","AÑO","YY","CONTADOR"
);


$groups = array(
  "MARCA","MODELO"
);


$idx = 0;
$currentLevelsData = array();

foreach ($data as $record) {
    if ($idx == 0) {
        $fkeys = array_keys($record);
    }

    for ($i=0 ; $i < count($record); $i++) {
        if (in_array($fkeys[$i],$fields)) {
            // El nombre del campo esta en los grupos?
            if (in_array($fkeys[$i], $groups)) {
                if (!isset($currentLevelsData[$fkeys[$i]]) || $currentLevelsData[$fkeys[$i]] != $record[$fkeys[$i]]) {
                    echo $record[$fkeys[$i]];
                    $currentLevelsData[$fkeys[$i]] = $record[$fkeys[$i]];
                } else {
                    echo "*\t";
                }
            } else {
                echo $record[$fkeys[$i]].",";
            }
        }
    }
    echo "\n";
    $idx++;
}
