<?php

namespace Application;

use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Privilege;
use UnicaenApp\Util;

return [
    'router'          => [
        'routes' => [
            'of' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/offre-de-formation',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'OffreFormation',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:action[/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'element' => [
                        'type'          => 'Literal',
                        'options'       => [
                            'route'    => '/element',
                            'defaults' => [
                                '__NAMESPACE__' => 'Application\Controller\OffreFormation',
                                'controller'    => 'ElementPedagogique',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes'  => [
                            'voir'        => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/voir/:elementPedagogique',
                                    'constraints' => ['elementPedagogique' => '[0-9]*'],
                                    'defaults'    => ['action' => 'voir'],
                                ],
                            ],
                            'ajouter'     => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => ['action' => 'saisir'],
                                ],
                            ],
                            'modifier'    => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/modifier/:elementPedagogique',
                                    'constraints' => ['elementPedagogique' => '[0-9]*'],
                                    'defaults'    => ['action' => 'saisir'],
                                ],
                            ],
                            'supprimer'   => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/supprimer/:elementPedagogique',
                                    'constraints' => ['elementPedagogique' => '[0-9]*'],
                                    'defaults'    => ['action' => 'supprimer'],
                                ],
                            ],
                            'search'      => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/search',
                                    'defaults' => ['action' => 'search'],
                                ],
                            ],
                            'get-periode' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/get-periode/:elementPedagogique',
                                    'constraints' => ['elementPedagogique' => '[0-9]*'],
                                    'defaults'    => ['action' => 'getPeriode'],
                                ],
                            ],
                        ],
                    ],
                    'etape'   => [
                        'type'          => 'Literal',
                        'options'       => [
                            'route'    => '/etape',
                            'defaults' => [
                                '__NAMESPACE__' => 'Application\Controller\OffreFormation',
                                'controller'    => 'Etape',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes'  => [
                            'voir'          => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/voir/:etape',
                                    'constraints' => ['etape' => '[0-9]*'],
                                    'defaults'    => ['action' => 'voir'],
                                ],
                            ],
                            'ajouter'       => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => ['action' => 'saisir'],
                                ],
                            ],
                            'modifier'      => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/modifier/:etape',
                                    'constraints' => ['etape' => '[0-9]*'],
                                    'defaults'    => ['action' => 'saisir'],
                                ],
                            ],
                            'supprimer'     => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/supprimer/:etape',
                                    'constraints' => ['etape' => '[0-9]*'],
                                    'defaults'    => ['action' => 'supprimer'],
                                ],
                            ],
                            'modulateurs'   => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/modulateurs/:etape',
                                    'constraints' => ['etape' => '[0-9]*'],
                                    'defaults'    => [
                                        '__NAMESPACE__' => 'Application\Controller\OffreFormation',
                                        'controller'    => 'Modulateur',
                                        'action'        => 'saisir',
                                    ],
                                ],
                            ],
                            'centres-couts' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/centres-couts/:etape',
                                    'constraints' => [
                                        'etape' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'controller' => 'EtapeCentreCout',
                                        'action'     => 'saisir',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'of' => [
                        'label'    => 'Offre de formation',
                        'title'    => "Gestion de l'offre de formation",
                        'route'    => 'of',
                        'resource' => Util::actionToResource('Application\Controller\OffreFormation', 'index'),
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            'Application\Guard\PrivilegeController' => [
                [
                    'controller' => 'Application\Controller\OffreFormation',
                    'action'     => ['index'],
                    'privileges' => Privilege::ODF_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\OffreFormation',
                    'action'     => ['export'],
                    'privileges' => Privilege::ODF_EXPORT_CSV,
                ],
            ],
            'BjyAuthorize\Guard\Controller'         => [
                /**
                 * OffreFormation
                 */
                [
                    'controller' => 'Application\Controller\OffreFormation',
                    'action'     => ['search-structures', 'search-niveaux'],
                    'roles'      => [IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                /**
                 * Etape
                 */
                [
                    'controller' => 'Application\Controller\OffreFormation\Etape',
                    'action'     => ['voir', 'search'],
                    'roles'      => [R_ROLE],
                ],
                [
                    'controller' => 'Application\Controller\OffreFormation\Etape',
                    'action'     => ['saisir', 'supprimer'],
                    'roles'      => [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                /**
                 * ElementPedagogique
                 */
                [
                    'controller' => 'Application\Controller\OffreFormation\ElementPedagogique',
                    'action'     => ['voir', 'search', 'getPeriode'],
                    'roles'      => [R_ROLE],
                ],
                [
                    'controller' => 'Application\Controller\OffreFormation\ElementPedagogique',
                    'action'     => ['saisir', 'supprimer'],
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
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'ElementPedagogique' => [],
                'Etape'              => [],
                'CentreCoutEp'       => [],
            ],
        ],
        'rule_providers'     => [
            'Application\Provider\Rule\PrivilegeRuleProvider' => [
                'allow' => [
                    [
                        'privileges' => Privilege::ODF_ELEMENT_VISUALISATION,
                        'resources'  => ['ElementPedagogique', 'Structure'],
                    ],
                    [
                        'privileges' => Privilege::ODF_ELEMENT_EDITION,
                        'resources'  => ['ElementPedagogique', 'Structure'],
                        'assertion'  => 'AssertionOffreDeFormation',
                    ],
                    [
                        'privileges' => Privilege::ODF_ETAPE_VISUALISATION,
                        'resources'  => ['Etape', 'Structure'],
                    ],
                    [
                        'privileges' => Privilege::ODF_ETAPE_EDITION,
                        'resources'  => ['Etape', 'Structure'],
                        'assertion'  => 'AssertionOffreDeFormation',
                    ],
                    [
                        'privileges' => Privilege::ODF_CENTRES_COUT_EDITION,
                        'resources'  => ['Etape', 'Structure', 'ElementPedagogique', 'CentreCoutEp'],
                        'assertion'  => 'AssertionOffreDeFormation',
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
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
            'AssertionOffreDeFormation'               => 'Application\Assertion\OffreDeFormationAssertion',
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'FormElementPedagogiqueRechercheFieldset' => 'Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset',
            'EtapeSaisie'                             => 'Application\Form\OffreFormation\EtapeSaisie',
            'ElementPedagogiqueSaisie'                => 'Application\Form\OffreFormation\ElementPedagogiqueSaisie',
            'EtapeModulateursSaisie'                  => 'Application\Form\OffreFormation\EtapeModulateursSaisie',
            'ElementModulateursFieldset'              => 'Application\Form\OffreFormation\ElementModulateursFieldset',
            'EtapeCentreCoutForm'                     => 'Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm',
            'ElementCentreCoutFieldset'               => 'Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutFieldset',
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'EtapeModulateursSaisieForm'          => 'Application\View\Helper\OffreFormation\EtapeModulateursSaisieForm',
            'ElementModulateursSaisieFieldset'    => 'Application\View\Helper\OffreFormation\ElementModulateursSaisieFieldset',
            'ElementPedagogique'                  => 'Application\View\Helper\OffreFormation\ElementPedagogiqueViewHelper',
            'Etape'                               => 'Application\View\Helper\OffreFormation\EtapeViewHelper',
            'EtapeCentreCoutForm'                 => 'Application\View\Helper\OffreFormation\EtapeCentreCoutFormViewHelper',
            'ElementCentreCoutFieldset'           => 'Application\View\Helper\OffreFormation\ElementCentreCoutFieldsetViewHelper',
            'fieldsetElementPedagogiqueRecherche' => 'Application\View\Helper\OffreFormation\FieldsetElementPedagogiqueRecherche',
        ],
    ],

];