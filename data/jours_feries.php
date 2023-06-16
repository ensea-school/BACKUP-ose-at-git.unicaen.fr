<?php

$dates = [
    '01-01' => 'Jour de l\'an',
    '05-01' => 'Fête du travail',
    '05-08' => 'Victoire 1945',
    '07-14' => 'Fête nationale',
    '15-08' => 'Assomption',
    '11-01' => 'Toussaint',
    '11-11' => 'Armistice 1918',
    '12-25' => 'Noël',
];

$d = new \DateTime();
for($a = 1970; $a <= 2037; $a++){
    // Date de pâques
    $d->setTimestamp(easter_date($a));

    $d->modify('+1 day');// lundi de pâques
    $dates[$d->format('Y-m-d')] = 'Lundi de Pâques';

    $d->modify('+38 day');// 38 jours après le lundi de pâques
    $dates[$d->format('Y-m-d')] = 'Ascension';

    $d->modify('+11 day');// 11 jours après l'ascension'
    $dates[$d->format('Y-m-d')] = 'Lundi de Pentecôte';
}

return $dates;