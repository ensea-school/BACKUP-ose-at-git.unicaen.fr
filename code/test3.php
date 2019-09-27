<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Interop\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

use Application\Service\ModeleContratService;
use Unicaen\OpenDocument\Document;


$d = [
    [
        'nom'    => 'Lécluse',
        'prenom' => 'Laurent',
        'varFoot' => 'test footer ok!!!',
        'sec1'   => false,

        'dpl_libelle@table:table-row' => [
            [
                'dpl_libelle'             => 'MASTER',
                'dpl_annee'               => '2016/2017',
                'prof_nom@text:list-item' => [
                    [
                        'prof_nom'    => 'Gaultier',
                        'prof_prenom' => 'Jean-paul',
                    ],
                    [
                        'prof_nom'    => 'Stingwitch',
                        'prof_prenom' => 'Serge',
                    ],
                ],
            ],
            [
                'dpl_libelle'             => 'Licence',
                'dpl_annee'               => '2013/2014',
                'prof_nom@text:list-item' => [
                    [
                        'prof_nom'    => 'Diouf',
                        'prof_prenom' => 'Mouss',
                    ],
                    [
                        'prof_nom'    => 'Poeld',
                        'prof_prenom' => 'Thony',
                    ],
                ],
            ],
        ],
    ],

    [
        'nom'                         => 'Fery',
        'prenom'                      => 'Karin',
        'varFoot' => 'test footer 2 bizarre!!!',
        'sec1@text:section'           => [
            ['secvar' => 'Varsec1',],
            ['secvar' => 'Varsec2',],
            ['secvar' => 'Varsec3',],
            ['secvar' => 'Varsec4',],
            ['secvar' => 'Varsec5',],
        ],
        'dpl_libelle@table:table-row' => [
            [
                'dpl_libelle'             => 'Ingé',
                'annee'                   => '2011/2012',
                'prof_nom@text:list-item' => [
                    [
                        'prof_nom'    => 'Gaul1',
                        'prof_prenom' => 'Jean-paul2',
                    ],
                    [
                        'prof_nom'    => 'Stingwitch2',
                        'prof_prenom' => 'Serge2',
                    ],
                ],
            ],
            [
                'dpl_libelle'             => 'Licence3',
                'annee'                   => '2010/2011',
                'prof_nom@text:list-item' => [
                    [
                        'prof_nom'    => 'Diouf2',
                        'prof_prenom' => 'Mouss2
poto',
                    ],
                    [
                        'prof_nom'    => 'Poeld2',
                        'prof_prenom' => 'Thony2',
                    ],
                ],
            ],
        ],
    ],
];


///** @var ModeleContrat $contrat */
$contrat = $container->get(ModeleContratService::class)->get(13);

$document = new Document();
$document->setTmpDir('/home/laurent/UnicaenCode');
//$document->loadFromData($contrat->getFichier());
$document->loadFromFile('/home/laurent/UnicaenCode/srcodt.odt');
$p = $document->publish($d);

//xmlDump($document->getContent());
$document->setPdfOutput(true);
//$document->saveToFile('/home/laurent/UnicaenCode/odtExport.pdf');

//xmlDump($document->getPublisher()->getOutContent());
//xmlDump($document->getStyles());
//var_dump($document->getStylist()->getVariables());


$document->download('exp.pdf');






