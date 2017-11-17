<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

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
                            'volume-horaire'   => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/volume-horaire/:elementPedagogique',
                                    'constraints' => [
                                        'elementPedagogique' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action'     => 'volume-horaire',
                                    ],
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
                            'taux-mixite'   => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/taux-mixite/:etape',
                                    'constraints' => [
                                        'etape' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'controller' => 'Etape',
                                        'action'     => 'taux-mixite',
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
                        'resource' => PrivilegeController::getResourceId('Application\Controller\OffreFormation', 'index'),
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            PrivilegeController::class => [
                /* Global */
                [
                    'controller' => 'Application\Controller\OffreFormation',
                    'action'     => ['index', 'search-structures', 'search-niveaux'],
                    'privileges' => Privileges::ODF_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\OffreFormation',
                    'action'     => ['export'],
                    'privileges' => Privileges::ODF_EXPORT_CSV,
                ],
                /* Etapes */
                [
                    'controller' => 'Application\Controller\OffreFormation\Etape',
                    'action'     => ['voir', 'search'],
                    'privileges' => Privileges::ODF_ETAPE_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\OffreFormation\Etape',
                    'action'     => ['saisir', 'supprimer'],
                    'privileges' => Privileges::ODF_ETAPE_EDITION,
                ],
                /* Éléments pédagogiques */
                [
                    'controller' => 'Application\Controller\OffreFormation\ElementPedagogique',
                    'action'     => ['voir'], // getPeriode est utilisé pour la saisie de service!!!
                    'privileges' => Privileges::ODF_ELEMENT_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\OffreFormation\ElementPedagogique',
                    'action'     => ['search', 'getPeriode'], // getPeriode est utilisé pour la saisie de service!!!
                    'privileges' => [
                        Privileges::ODF_ELEMENT_VISUALISATION,
                        Privileges::ENSEIGNEMENT_EDITION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\OffreFormation\ElementPedagogique',
                    'action'     => ['volume-horaire'],
                    'privileges' => [
                        Privileges::ODF_ELEMENT_VH_VISUALISATION,
                        Privileges::ODF_ELEMENT_VH_EDITION,
                    ],

                ],
                [
                    'controller' => 'Application\Controller\OffreFormation\ElementPedagogique',
                    'action'     => ['saisir', 'supprimer'],
                    'privileges' => Privileges::ODF_ELEMENT_EDITION,
                ],
                /* Modulateurs */
                [
                    'controller' => 'Application\Controller\OffreFormation\Modulateur',
                    'action'     => ['saisir'],
                    'privileges' => Privileges::ODF_MODULATEURS_EDITION,
                ],
                /* Centres de coûts */
                [
                    'controller' => 'Application\Controller\OffreFormation\EtapeCentreCout',
                    'action'     => ['saisir'],
                    'privileges' => Privileges::ODF_CENTRES_COUT_EDITION,
                ],
                /* Taux de mixité */
                [
                    'controller' => 'Application\Controller\OffreFormation\Etape',
                    'action'     => ['taux-mixite'],
                    'privileges' => Privileges::ODF_TAUX_MIXITE_EDITION,
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'ElementPedagogique' => [],
                'Etape'              => [],
                'CentreCoutEp'       => [],
                'ElementModulateur'  => [],
                'VolumeHoraireEns'   => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => Privileges::ODF_ELEMENT_EDITION,
                        'resources'  => ['ElementPedagogique', 'StructureService'],
                        'assertion'  => 'AssertionOffreDeFormation',
                    ],
                    [
                        'privileges' => Privileges::ODF_ETAPE_EDITION,
                        'resources'  => ['Etape', 'StructureService'],
                        'assertion'  => 'AssertionOffreDeFormation',
                    ],
                    [
                        'privileges' => Privileges::ODF_CENTRES_COUT_EDITION,
                        'resources'  => ['Etape', 'StructureService', 'ElementPedagogique', 'CentreCoutEp'],
                        'assertion'  => 'AssertionOffreDeFormation',
                    ],
                    [
                        'privileges' => Privileges::ODF_MODULATEURS_EDITION,
                        'resources'  => ['Etape', 'StructureService', 'ElementPedagogique', 'ElementModulateur'],
                        'assertion'  => 'AssertionOffreDeFormation',
                    ],
                    [
                        'privileges' => Privileges::ODF_TAUX_MIXITE_EDITION,
                        'resources'  => ['Etape', 'StructureService', 'ElementPedagogique'],
                        'assertion'  => 'AssertionOffreDeFormation',
                    ],
                    [
                        'privileges' => Privileges::ODF_ELEMENT_VH_EDITION,
                        'resources'  => ['Etape', 'StructureService', 'ElementPedagogique','VolumeHoraireEns','TypeIntervention'],
                        'assertion'  => 'AssertionOffreDeFormation',
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\OffreFormation'                    => Controller\OffreFormationController::class,
            'Application\Controller\OffreFormation\Etape'              => Controller\OffreFormation\EtapeController::class,
            'Application\Controller\OffreFormation\Modulateur'         => Controller\OffreFormation\ModulateurController::class,
            'Application\Controller\OffreFormation\EtapeCentreCout'    => Controller\OffreFormation\EtapeCentreCoutController::class,
        ],
        'factories' => [
            'Application\Controller\OffreFormation\ElementPedagogique' => Controller\OffreFormation\Factory\ElementPedagogiqueControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationElementPedagogique'  => Service\ElementPedagogique::class,
            'ApplicationCheminPedagogique'   => Service\CheminPedagogique::class,
            'ApplicationEtape'               => Service\Etape::class,
            'ApplicationTypeFormation'       => Service\TypeFormation::class,
            'ApplicationGroupeTypeFormation' => Service\GroupeTypeFormation::class,
            'ApplicationNiveauEtape'         => Service\NiveauEtape::class,
            'ApplicationNiveauFormation'     => Service\NiveauFormation::class,
            'ApplicationModulateur'          => Service\Modulateur::class,
            'ApplicationElementModulateur'   => Service\ElementModulateur::class,
            'ApplicationTypeModulateur'      => Service\TypeModulateur::class,
            'ApplicationDomaineFonctionnel'  => Service\DomaineFonctionnel::class,
            'AssertionOffreDeFormation'      => Assertion\OffreDeFormationAssertion::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'FormElementPedagogiqueRechercheFieldset'                => Form\OffreFormation\ElementPedagogiqueRechercheFieldset::class,
            'EtapeSaisie'                                            => Form\OffreFormation\EtapeSaisie::class,
            'ElementPedagogiqueSaisie'                               => Form\OffreFormation\ElementPedagogiqueSaisie::class,
            'EtapeModulateursSaisie'                                 => Form\OffreFormation\EtapeModulateursSaisie::class,
            'ElementModulateursFieldset'                             => Form\OffreFormation\ElementModulateursFieldset::class,
            'EtapeCentreCoutForm'                                    => Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm::class,
            'ElementCentreCoutFieldset'                              => Form\OffreFormation\EtapeCentreCout\ElementCentreCoutFieldset::class,
            Form\OffreFormation\TauxMixite\TauxMixiteForm::class     => Form\OffreFormation\TauxMixite\TauxMixiteForm::class,
            Form\OffreFormation\TauxMixite\TauxMixiteFieldset::class => Form\OffreFormation\TauxMixite\TauxMixiteFieldset::class,
        ],
        'factories' => [
            Form\OffreFormation\VolumeHoraireEns::class => Form\OffreFormation\Factory\VolumeHoraireEnsFormFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'EtapeModulateursSaisieForm'          => View\Helper\OffreFormation\EtapeModulateursSaisieForm::class,
            'ElementModulateursSaisieFieldset'    => View\Helper\OffreFormation\ElementModulateursSaisieFieldset::class,
            'ElementPedagogique'                  => View\Helper\OffreFormation\ElementPedagogiqueViewHelper::class,
            'Etape'                               => View\Helper\OffreFormation\EtapeViewHelper::class,
            'EtapeCentreCoutForm'                 => View\Helper\OffreFormation\EtapeCentreCoutFormViewHelper::class,
            'ElementCentreCoutFieldset'           => View\Helper\OffreFormation\ElementCentreCoutFieldsetViewHelper::class,
            'EtapeTauxMixiteForm'                 => View\Helper\OffreFormation\EtapeTauxMixiteFormViewHelper::class,
            'ElementTauxMixiteFieldset'           => View\Helper\OffreFormation\ElementTauxMixiteFieldsetViewHelper::class,
            'fieldsetElementPedagogiqueRecherche' => View\Helper\OffreFormation\FieldsetElementPedagogiqueRecherche::class,
        ],
    ],

];