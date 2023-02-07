<?php

$mainData    = reset($data);
$data        = [];
$exemplaires = [];

for ($i = 1; $i <= 3; $i++) {
    $exemplaire = $mainData['exemplaire' . $i] ?? '0';
    if ($exemplaire !== '0') {
        $exemplaires[$i] = $exemplaire;
    }
    unset($mainData['exemplaire' . $i]);
}

foreach ($exemplaires as $exemplaire) {
    $newExemplaire               = $mainData;
    $newExemplaire['exemplaire'] = $exemplaire;
    $data[]                      = $newExemplaire;
}

return $data;