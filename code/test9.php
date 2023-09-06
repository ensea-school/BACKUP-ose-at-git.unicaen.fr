<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$data = [
    1 => [ // mep = lap
        'lap' => [
            ['aa' => 5, 'ac' => 4],
        ],
        'mep' => [
            ['h' => 9],
        ],
    ],
    2 => [ // mep < lap
        'lap' => [
            ['aa' => 5, 'ac' => 4],
        ],
        'mep' => [
            ['h' => 6],
        ],
    ],
    3 => [ // 3 mep
        'lap' => [
            ['aa' => 5, 'ac' => 4],
        ],
        'mep' => [
            ['h' => 2],
            ['h' => 3],
            ['h' => 1],
        ],
    ],
    4 => [ // n mep, = lap
        'lap' => [
            ['aa' => 5, 'ac' => 4],
            ['aa' => 3, 'ac' => 21],
        ],
        'mep' => [
            ['h' => 33],
        ],
    ],
    5 => [ // n mep, =  2 lap
        'lap' => [
            ['aa' => 5, 'ac' => 4],
            ['aa' => 3, 'ac' => 21],
        ],
        'mep' => [
            ['h' => 18],
            ['h' => 15],
        ],
    ],
    6 => [ // n mep, <  2 lap
        'lap' => [
            ['aa' => 5, 'ac' => 4],
            ['aa' => 3, 'ac' => 21],
        ],
        'mep' => [
            ['h' => 3],
            ['h' => 10],
            ['h' => 31],
        ],
    ],
];

//unset($data[1]);
//unset($data[2]);
//unset($data[3]);
//unset($data[4]);
//unset($data[5]);
//unset($data[6]);


function traitementProrata(array &$d)
{
    $laps = &$d['lap'];
    $meps = &$d['mep'];

    foreach ($laps as $lid => $null) {
        $lap = &$laps[$lid];
        $lap['mep'] = [];

        /* ALGO */
        $npAA = $lap['aa'];
        $npAC = $lap['ac'];
        /* FIN ALGO */

        foreach ($meps as $mid => $null) {
            $mep = &$meps[$mid];

            if ($npAA + $npAC == 0) break;

            /* ALGO */
            $nmep = ['aa' => 0, 'ac' => 0];

            $heures = min($mep['h'], $npAA+$npAC);

            $aaHeures = min((int)round($heures * $npAA / ($npAA+$npAC)), $npAA);
            if ($aaHeures > 0) {
                $nmep['aa'] += $aaHeures;
                $mep['h'] -= $aaHeures;
                $npAA -= $aaHeures;
            }

            $acHeures = $heures - $aaHeures;
            if ($acHeures > 0) {
                $nmep['ac'] += $acHeures;
                $mep['h'] -= $acHeures;
                $npAC -= $acHeures;
            }

            if ($nmep['aa'] + $nmep['ac'] > 0) {
                $lap['mep'][$mid] = $nmep;
                if ($mep['h'] == 0){
                    unset($meps[$mid]);
                }
            }
            /* FIN ALGO */
        }
    }

}


function traitementOrdre(array &$d)
{
    $laps = &$d['lap'];
    $meps = &$d['mep'];

    foreach ($laps as $lid => $null) {
        $lap = &$laps[$lid];
        $lap['mep'] = [];

        /* ALGO */
        $npAA = $lap['aa'];
        $npAC = $lap['ac'];
        /* FIN ALGO */

        foreach ($meps as $mid => $null) {
            $mep = &$meps[$mid];

            /* ALGO */
            $nmep = ['aa' => 0, 'ac' => 0];

            $heures = min($mep['h'], $npAA);
            if ($heures > 0) {
                $nmep['aa'] += $heures;
                $mep['h'] -= $heures;
                $npAA -= $heures;
            }

            $heures = min($mep['h'], $npAC);
            if ($heures > 0) {
                $nmep['ac'] += $heures;
                $mep['h'] -= $heures;
                $npAC -= $heures;
            }

            if ($nmep['aa'] + $nmep['ac'] > 0) {
                $lap['mep'][$mid] = $nmep;
                if ($mep['h'] == 0){
                    unset($meps[$mid]);
                }
            }
            /* FIN ALGO */
        }
    }

}


foreach ($data as $index => $d) {
    echo "================== Cas " . $index . " ==================\n";
    echo "----- Avant ----------\n";
    arrayDump($d);
    echo "----- Traitement -----\n";
    //traitementOrdre($d);
    traitementProrata($d);
    echo "----- Après ----------\n";
    arrayDump($d);
    echo "\n";
}