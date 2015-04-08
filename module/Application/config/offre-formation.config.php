<?php

namespace Application;

use Application\Acl\Role;
use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\DirecteurComposanteRole;
use Application\Acl\GestionnaireComposanteRole;
use Application\Acl\ResponsableComposanteRole;
use Application\Acl\SuperviseurComposanteRole;
use Application\Acl\ResponsableRechercheLaboRole;
use Application\Acl\DrhRole;
use Application\Acl\GestionnaireDrhRole;
use Application\Acl\ResponsableDrhRole;
use Application\Acl\EtablissementRole;
use Application\Acl\SuperviseurEtablissementRole;
use Application\Acl\IntervenantRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantExterieurRole;
use Application\Acl\FoadRole;
use Application\Acl\ResponsableFoadRole;

return [
    'router' => [
        'routes' => [
            'of' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/offre-de-formation',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'OffreFormation',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action[/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'element' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/element',
                            'defaults' => [
                                '__NAMESPACE__' => 'Application\Controller\OffreFormation',
                                'controller'    => 'ElementPedagogique',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'default' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/:action[/:id]',
                                    'constraints' => [
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]*',
                                    ],
                                ],
                            ],
                            'voir' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/voir/:id',
                                    'constraints' => [ 'id' => '[0-9]*' ],
                                    'defaults' => [ 'action' => 'voir' ],
                                ],
                            ],
                            'apercevoir' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/apercevoir/:id',
                                    'constraints' => [ 'id' => '[0-9]*' ],
                                    'defaults' => [ 'action' => 'apercevoir' ],
                                ],
                            ],
                            'ajouter' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/ajouter/:etape',
                                    'constraints' => [ 'etape' => '[0-9]*' ],
                                    'defaults' => [ 'action' => 'ajouter' ],
                                ],
                            ],
                            'modifier' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/modifier/:etape/:id',
                                    'constraints' => [
                                        'etape' => '[0-9]*',
                                        'id'    => '[0-9]*',
                                    ],
                                    'defaults' => [ 'action' => 'modifier' ],
                                ],
                            ],
                            'supprimer' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/supprimer/:id',
                                    'constraints' => [ 'id' => '[0-9]*' ],
                                    'defaults' => [ 'action' => 'supprimer' ],
                                ],
                            ],
                            'get-periode' => [
                                'type'  => 'Segment',
                                'options' => [
                                    'route' => '/get-periode/:elementPedagogique',
                                    'constraints' => [ 'elementPedagogique' => '[0-9]*' ],
                                    'defaults' => [ 'action' => 'getPeriode' ],
                                ],
                            ],
                        ],
                    ],
                    'etape' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/etape',
                            'defaults' => [
                                '__NAMESPACE__' => 'Application\Controller\OffreFormation',
                                'controller'    => 'Etape',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'default' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/:action[/:id]',
                                    'constraints' => [
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]*',
                                    ],
                                ],
                            ],
                            'voir' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/voir/:id',
                                    'constraints' => [ 'id' => '[0-9]*' ],
                                    'defaults' => [ 'action' => 'voir' ],
                                ],
                            ],
                            'apercevoir' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/apercevoir/:id',
                                    'constraints' => [ 'id' => '[0-9]*' ],
                                    'defaults' => [ 'action' => 'apercevoir' ],
                                ],
                            ],
                            'ajouter' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/ajouter/:structure[/niveau/:niveau]',
                                    'constraints' => [ 'structure' => '[0-9]*' ],
                                    'defaults' => [ 'action' => 'ajouter' ],
                                ],
                            ],
                            'modifier' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/modifier/:structure/:id',
                                    'constraints' => [ 'id' => '[0-9]*' ],
                                    'defaults' => [ 'action' => 'modifier' ],
                                ],
                            ],
                            'supprimer' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/supprimer/:id',
                                    'constraints' => [ 'id' => '[0-9]*' ],
                                    'defaults' => [ 'action' => 'supprimer' ],
                                ],
                            ],
                            'modulateurs' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/modulateurs/:id',
                                    'constraints' => [
                                        'etape' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        '__NAMESPACE__' => 'Application\Controller\OffreFormation',
                                        'controller'    => 'Modulateur',
                                        'action'        => 'saisir'
                                    ],
                                ],
                            ],
                            'centres-couts' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/centres-couts/:id',
                                    'constraints' => [
                                        'etape' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'controller'    => 'EtapeCentreCout',
                                        'action'        => 'saisir'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'of' => [
                        'label'    => 'Offre de formation',
                        'title'    => "Gestion de l'offre de formation",
                        'route'    => 'of',
                        'resource' => 'controller/Application\Controller\OffreFormation:index',
                        'pages' => [
                            'element-ajouter' => [
                                'label'    => "Créer un nouvel enseignement",
                                'title'    => "Créer un nouvel enseignement pour la formation sélectionnée",
                                'route'    => 'of/element/ajouter',
                                'resource' => 'controller/Application\Controller\OffreFormation\ElementPedagogique:ajouter',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-plus',
                                'category' => 'element',
                            ],
                            'element-modifier' => [
                                'label'    => "Modifier cet enseignement",
                                'title'    => "Modifier cet enseignement",
                                'route'    => 'of/element/modifier',
                                'resource' => 'controller/Application\Controller\OffreFormation\ElementPedagogique:modifier',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-edit',
                                'withtarget' => true,
                                'category' => 'element',
                            ],
                            'element-supprimer' => [
                                'label'    => "Supprimer cette formation",
                                'title'    => "Supprimer cette formation",
                                'route'    => 'of/element/supprimer',
                                'resource' => 'controller/Application\Controller\OffreFormation\ElementPedagogique:supprimer',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-remove',
                                'withtarget' => true,
                                'category' => 'element',
                            ],
                            'etape-ajouter' => [
                                'label'    => "Créer une nouvelle formation",
                                'title'    => "Créer une nouvelle formation",
                                'route'    => 'of/etape/ajouter',
                                'resource' => 'controller/Application\Controller\OffreFormation\Etape:ajouter',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-plus',
                                'category' => 'etape',
                            ],
                            'etape-modifier' => [
                                'label'    => "Modifier cette formation",
                                'title'    => "Modifier cette formation",
                                'route'    => 'of/etape/modifier',
                                'resource' => 'controller/Application\Controller\OffreFormation\Etape:modifier',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-edit',
                                'withtarget' => true,
                                'category' => 'etape',
                            ],
                            'etape-supprimer' => [
                                'label'    => "Supprimer cette formation",
                                'title'    => "Supprimer cette formation",
                                'route'    => 'of/etape/supprimer',
                                'resource' => 'controller/Application\Controller\OffreFormation\Etape:supprimer',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-remove',
                                'withtarget' => true,
                                'category' => 'etape',
                            ],
                            'etape-modulateurs' => [
                                'label'    => "Editer les modulateurs liés à cette formation",
                                'title'    => "Editer les modulateurs liés à cette formation",
                                'route'    => 'of/etape/modulateurs',
                                'resource' => 'controller/Application\Controller\OffreFormation\Modulateur:saisir',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-list-alt',
                                'withtarget' => true,
                                'category' => 'modulateur',
                            ],
                            'etape-centres-couts' => [
                                'label'    => "Paramétrer les centres de coûts",
                                'title'    => "Paramétrer les centres de coûts liés à cette formation",
                                'route'    => 'of/etape/centres-couts',
                                'resource' => 'controller/Application\Controller\OffreFormation\EtapeCentreCout:saisir',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-euro',
                                'withtarget' => true,
                                'category' => 'centres-couts',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                /**
                 * OffreFormation
                 */
                [
                    'controller' => 'Application\Controller\OffreFormation',
                    'action'     => ['search-structures', 'search-niveaux'],
                    'roles'      => [IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                [
                    'controller' => 'Application\Controller\OffreFormation',
                    'action'     => ['index', 'export'],
                    'roles'      => [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                /**
                 * Etape
                 */
                [
                    'controller' => 'Application\Controller\OffreFormation\Etape',
                    'action'     => ['voir', 'apercevoir', 'search'],
                    'roles'      => [R_ROLE],
                ],
                [
                    'controller' => 'Application\Controller\OffreFormation\Etape',
                    'action'     => ['ajouter', 'modifier', 'supprimer'],
                    'roles'      => [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                /**
                 * ElementPedagogique
                 */
                [
                    'controller' => 'Application\Controller\OffreFormation\ElementPedagogique',
                    'action'     => ['voir', 'apercevoir', 'search', 'getPeriode'],
                    'roles'      => [R_ROLE],
                ],
                [
                    'controller' => 'Application\Controller\OffreFormation\ElementPedagogique',
                    'action'     => ['ajouter', 'modifier', 'supprimer'],
                    'roles'      => [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                /**
                 * Modulateur
                 */
                [
                    'controller' => 'Application\Controller\OffreFormation\Modulateur',
                    'action'     => ['saisir'],
                    'roles'      => [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                /**
                 * Centre de Cout des Etapes
                 */
                [
                    'controller' => 'Application\Controller\OffreFormation\EtapeCentreCout',
                    'action'     => ['saisir'],
                    'roles'      => [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\OffreFormation'                    => 'Application\Controller\OffreFormationController',
            'Application\Controller\OffreFormation\Etape'              => 'Application\Controller\OffreFormation\EtapeController',
            'Application\Controller\OffreFormation\Modulateur'         => 'Application\Controller\OffreFormation\ModulateurController',
            'Application\Controller\OffreFormation\ElementPedagogique' => 'Application\Controller\OffreFormation\ElementPedagogiqueController',
            'Application\Controller\OffreFormation\EtapeCentreCout'    => 'Application\Controller\OffreFormation\EtapeCentreCoutController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationElementPedagogique'           => 'Application\\Service\\ElementPedagogique',
            'ApplicationCheminPedagogique'            => 'Application\\Service\\CheminPedagogique',
            'ApplicationEtape'                        => 'Application\\Service\\Etape',
            'ApplicationTypeFormation'                => 'Application\\Service\\TypeFormation',
            'ApplicationGroupeTypeFormation'          => 'Application\\Service\\GroupeTypeFormation',
            'ApplicationNiveauEtape'                  => 'Application\\Service\\NiveauEtape',
            'ApplicationNiveauFormation'              => 'Application\\Service\\NiveauFormation',
            'ApplicationModulateur'                   => 'Application\\Service\\Modulateur',
            'ApplicationElementModulateur'            => 'Application\\Service\\ElementModulateur',
            'ApplicationTypeModulateur'               => 'Application\\Service\\TypeModulateur',
            'ApplicationDomaineFonctionnel'           => 'Application\\Service\\DomaineFonctionnel',
            'FormElementPedagogiqueRechercheHydrator' => 'Application\\Form\\OffreFormation\\ElementPedagogiqueRechercheHydrator',
            'ElementModulateursFormHydrator'          => 'Application\\Form\\OffreFormation\\ElementModulateursHydrator',
            'EtapeModulateursFormHydrator'            => 'Application\\Form\\OffreFormation\\EtapeModulateursHydrator',
            'EtapeCentreCoutFormHydrator'             => 'Application\\Form\\OffreFormation\\EtapeCentreCout\\EtapeCentreCoutFormHydrator',
            'ElementCentreCoutFieldsetHydrator'       => 'Application\\Form\\OffreFormation\\EtapeCentreCout\\ElementCentreCoutFieldsetHydrator',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'FormElementPedagogiqueRechercheFieldset' => 'Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset',
            'EtapeSaisie'                             => 'Application\Form\OffreFormation\EtapeSaisie',
            'ElementPedagogiqueSaisie'                => 'Application\Form\OffreFormation\ElementPedagogiqueSaisie',
            'EtapeModulateursSaisie'                  => 'Application\Form\OffreFormation\EtapeModulateursSaisie',
            'ElementModulateursFieldset'              => 'Application\Form\OffreFormation\ElementModulateursFieldset',
            'EtapeCentreCoutSaisieForm'               => 'Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutSaisieForm',
            'ElementCentreCoutSaisieFieldset'         => 'Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutSaisieFieldset',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'EtapeModulateursSaisieForm'       => 'Application\View\Helper\OffreFormation\EtapeModulateursSaisieForm',
            'ElementModulateursSaisieFieldset' => 'Application\View\Helper\OffreFormation\ElementModulateursSaisieFieldset',
            'ElementPedagogique'               => 'Application\View\Helper\OffreFormation\ElementPedagogique',
            'Etape'                            => 'Application\View\Helper\OffreFormation\EtapeViewHelper',
            'FormEtapeCentreCoutSaisie'        => 'Application\View\Helper\OffreFormation\FormEtapeCentreCoutSaisieHelper',
            'FieldsetElementCentreCoutSaisie'  => 'Application\View\Helper\OffreFormation\FieldsetElementCentreCoutSaisieHelper',
        ],
    ],

];