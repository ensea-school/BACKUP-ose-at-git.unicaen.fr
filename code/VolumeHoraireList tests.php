<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

include_once __DIR__ . '/../tests/OSETest/VolumeHoraireListeTest.php';

use OSETest\VolumeHoraireListeTest;

/*
// Pour pouvoir créer un début de jeu de test à partir de données réelles
$vhlt     = new VolumeHoraireListeTest();
$scenario = $vhlt->createScenarioFromService(
    24519,
    'REALISE',
    'S1',
    'TD'
);
phpDump($scenario);

return;
*/

$seulScenario = null;
//$seulScenario = 20;

$scenarios = [
    1  => [
        'input'   => [
            1 => [
                'horaireDebut'     => '27/09/1980 14:00',
                'motifNonPaiement' => null,
                'HistoCreation'    => '14/08/2018 11:00',
                'heures'           => 5,
            ],
            2 => [
                'horaireDebut'     => '27/09/1980 14:00',
                'motifNonPaiement' => null,
                'HistoCreation'    => '14/08/2018 11:00',
                'heures'           => 4,
            ],
            3 => [
                'horaireDebut'     => '27/09/1980 14:00',
                'motifNonPaiement' => 1,
                'HistoCreation'    => '14/08/2018 11:00',
                'heures'           => 4,
            ],
            4 => [
                'horaireDebut'     => '27/09/1980 14:00',
                'motifNonPaiement' => 1,
                'HistoCreation'    => '14/08/2018 11:01',
                'heures'           => 4,
            ],
            5 => [
                'horaireDebut'     => '27/09/1980 14:00',
                'motifNonPaiement' => 1,
                'HistoCreation'    => null,
                'heures'           => 3,
            ],
        ],
        'actions' => [
            ['setHeures', 15],
        ],
        'output'  => [
            1 => ['removed' => true],
        ],
    ],
    2  => [
        'input'   => [10, -2],
        'output'  => [10, ['removed' => true]],
        'actions' => [['setHeures', 10]],
    ],
    3  => [
        'input'   => [50, -10, -2],
        'output'  => [58, ['removed' => true], ['removed' => true]],
        'actions' => [['setHeures', 58]],
    ],
    4  => [
        'input'   => [
            30216 => [
                'heures' => 16.0,
                'source' => 'ADE',
            ],
            30263 => ['heures' => 12.0],
            30264 => [
                'motifNonPaiement' => 'HC payées par ENSCCF',
                'heures'           => 5.0,
            ],
            30187 => [
                'heures' => 3.0,
                'valide' => true,
            ],
        ],
        'output'  => [
            30263 => ['heures' => -2],
            30264 => ['removed' => true],
        ],
        'actions' => [
            ['setHeures', 17],
        ],
    ],
    5  => [
        'input'   => [
            30216 => [
                'heures' => 16.0,
                'source' => 'ADE',
            ],
            30263 => ['heures' => 12.0],
            30264 => [
                'motifNonPaiement' => 'HC payées par ENSCCF',
                'heures'           => 4.0,
            ],
            30187 => [
                'heures' => 3.0,
                'valide' => true,
            ],
        ],
        'output'  => [
            30263 => ['heures' => -18],
            30264 => ['removed' => true],
        ],
        'actions' => [
            ['setHeures', 1],
        ],
    ],
    6  => [
        'input'   => [
            30216 => [
                'heures' => 16.0,
                'source' => 'ADE',
            ],
            30264 => [
                'motifNonPaiement' => 'HC payées par ENSCCF',
                'heures'           => 5.0,
            ],
            30265 => [
                'heures' => -8.0,
            ],
            30187 => [
                'heures' => 3.0,
                'valide' => true,
            ],
        ],
        'output'  => [
            30264 => ['heures' => 2],
            'n1'  => [
                'motifNonPaiement' => 'Essai-1',
                'heures'           => 3,
            ],
        ],
        'actions' => [
            ['setHeuresWithMotifNonPaiement', 3, 'HC payées par ENSCCF', 'Essai-1'],
        ],
    ],
    7  => [
        'input'   => [
            30216 => [
                'heures' => 16.0,
                'source' => 'ADE',
            ],
            30264 => [
                'motifNonPaiement' => 'HC payées par ENSCCF',
                'heures'           => 5.0,
            ],
            30265 => [
                'heures' => -8.0,
            ],
            30187 => [
                'heures' => 3.0,
                'valide' => true,
            ],
        ],
        'output'  => [
            30264 => ['removed' => true],
            30265 => [
                'heures' => -19,
            ],
            'n1'  => [
                'heures'           => 50,
                'motifNonPaiement' => 'Essai-1',
            ],
        ],
        'actions' => [
            ['setHeuresWithMotifNonPaiement', 50, false, 'Essai-1'],
        ],
    ],
    8  => [
        'input'   => [
            30216 => [
                'heures' => 16.0,
                'source' => 'ADE',
            ],
            30264 => [
                'motifNonPaiement' => 'HC payées par ENSCCF',
                'heures'           => 5.0,
            ],
            30265 => [
                'heures' => -8.0,
            ],
            30187 => [
                'heures' => 3.0,
                'valide' => true,
            ],
        ],
        'output'  => [
            30264 => ['heures' => 2],
            'n1'  => [
                'heures'           => 3,
                'motifNonPaiement' => 'Essai-1',
            ],
        ],
        'actions' => [
            ['setHeuresWithMotifNonPaiement', 3, 'HC payées par ENSCCF', 'Essai-1'],
        ],
    ],
    9  => [
        'input'   => [
            30216 => [
                'heures' => 16.0,
                'source' => 'ADE',
            ],
            30264 => [
                'motifNonPaiement' => 'HC payées par ENSCCF',
                'heures'           => 5.0,
            ],
            30265 => [
                'heures' => -8.0,
            ],
            30187 => [
                'heures' => 3.0,
                'valide' => true,
            ],
        ],
        'output'  => [
            30264 => [
                'motifNonPaiement' => 'Essai-1',
            ],
        ],
        'actions' => [
            ['setHeuresWithMotifNonPaiement', 5, 'HC payées par ENSCCF', 'Essai-1'],
        ],
    ],
    10 => [
        'input'   => [-2, 2, 5],
        'output'  => [['removed' => true], 2, 7],
        'actions' => [['setHeures', 9]],
    ],
    11 => [
        'input'   =>
            [
                30266 => ['heures' => 5.0,],
                30267 => ['heures' => 10.0,],
                30240 => ['heures' => 1.0,],
                30245 => ['heures' => 9.0,],
                30270 => ['heures' => 1.0,],
                30271 => ['heures' => 2.0,],
            ],
        'output'  => [
            30267 => ['removed' => true],
            30240 => ['removed' => true],
            30245 => ['removed' => true],
            30270 => ['removed' => true],
            30271 => ['removed' => true],
        ],
        'actions' => [
            ['setHeures', 5],
        ],
    ],
    20 => [
        'input'   => [5, 2, -2],
        'output'  => [3, ['removed' => true], -2],
        'actions' => [['setHeures', 1]],
    ],
    30 => [
        'input'   => [0],
        'output'  => [1],
        'actions' => [['setHeures', 1]],
    ],
    40 => [
        'input'   => [2, 0],
        'output'  => [2, 1],
        'actions' => [['setHeures', 3]],
    ],
    50 => [
        'input'   => [-2, 10, 5],
        'output'  => [-2, 10, 4],
        'actions' => [['setHeures', 12]],
    ],
];

if ($seulScenario){
    $scenarios = [$seulScenario => $scenarios[$seulScenario]];
}

$vhlt = new VolumeHoraireListeTest();
foreach ($scenarios as $id => $scenario) {
    echo '<h1>Scénario ' . $id . '</h1>';
    $vhlt->calc($scenario);
    $vhlt->displayResult();
}
