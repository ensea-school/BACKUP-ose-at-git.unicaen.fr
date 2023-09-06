<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$sap = new \Paiement\Tbl\Process\Sub\ServiceAPayer();
$sap->fromArray(getData1());

$r = new \Paiement\Tbl\Process\Sub\Rapprocheur();
$r->setRegle('prorata');
//$r->setRegle('ordre-saisie');
$r->rapprocher($sap);

arrayDump($sap->toArray());


function getData1()
{

    return [
        'key'                   => 'e-121007207-6',
        'annee'                 => 2022,
        'intervenant'           => 667613,
        'structure'             => 385,
        'service'               => 222531,
        'referentiel'           => NULL,
        'mission'               => NULL,
        'formuleResService'     => 121007207,
        'formuleResServiceRef'  => NULL,
        'typeHeures'            => 6,
        'defDomaineFonctionnel' => 14,
        'defCentreCout'         => 209,
        'tauxCongesPayes'       => 1.0,
        'heures'                => 4.75,
        'lignesAPayer'          => [
            '1-42.86' => [
                'tauxRemu'        => 1,
                'tauxValeur'      => 42.86,
                'pourcAA'         => 0.4,
                'heures'          => 4.75,
                'heuresAA'        => 1.9,
                'heuresAC'        => 2.85,
                'misesEnPaiement' => [
                ],
            ],
        ],
        'misesEnPaiement'       => [
            212277 => [
                'id'                 => 212277,
                'heures'             => 4.75,
                'date'               => '2022-12-31',
                'periodePaiement'    => 4,
                'centreCout'         => 209,
                'domaineFonctionnel' => 14,
            ],
        ],
    ];
}

function getData2()
{
    return [
        'key'                   => 'e-113957505-8',
        'annee'                 => 2017,
        'intervenant'           => 20970,
        'structure'             => 229,
        'service'               => 89226,
        'referentiel'           => NULL,
        'mission'               => NULL,
        'formuleResService'     => 113957505,
        'formuleResServiceRef'  => NULL,
        'typeHeures'            => 8,
        'defDomaineFonctionnel' => 1,
        'defCentreCout'         => 362,
        'tauxCongesPayes'       => 1.0,
        'heures'                => 10.37,
        'lignesAPayer'          => [
            '1-41.41' => [
                'tauxRemu'        => 1,
                'tauxValeur'      => 41.41,
                'pourcAA'         => 0.4,
                'heures'          => 0.0,
                'heuresAA'        => 2.14,
                'heuresAC'        => 3.23,
                'misesEnPaiement' => [
                ],
            ],
            '1-42.86' => [
                'tauxRemu'        => 1,
                'tauxValeur'      => 42.86,
                'pourcAA'         => 0.4,
                'heures'          => 0.0,
                'heuresAA'        => 2.00,
                'heuresAC'        => 3.00,
                'misesEnPaiement' => [
                ],
            ],
        ],
        'misesEnPaiement'       => [
            76199 => [
                'id'                 => 76199,
                'heures'             => 1.62,
                'date'               => '2018-05-31',
                'periodePaiement'    => 8,
                'centreCout'         => 362,
                'domaineFonctionnel' => 1,
            ],
            77200 => [
                'id'                 => 77200,
                'heures'             => 1.07,
                'date'               => '2018-06-30',
                'periodePaiement'    => 9,
                'centreCout'         => 362,
                'domaineFonctionnel' => 1,
            ],
            78457 => [
                'id'                 => 78457,
                'heures'             => 2.69,
                'date'               => '2018-07-31',
                'periodePaiement'    => 10,
                'centreCout'         => 362,
                'domaineFonctionnel' => 1,
            ],
            80592 => [
                'id'                 => 80592,
                'heures'             => 2.29,
                'date'               => '2018-08-31',
                'periodePaiement'    => 11,
                'centreCout'         => 362,
                'domaineFonctionnel' => 1,
            ],
            93625 => [
                'id'                 => 93625,
                'heures'             => 2.7,
                'date'               => '2018-10-31',
                'periodePaiement'    => 18,
                'centreCout'         => 362,
                'domaineFonctionnel' => 1,
            ],
        ],
    ];
}
