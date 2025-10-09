<?php

namespace Agrement;

use Agrement\Assertion\AgrementAssertion;
use Agrement\Controller\AgrementController;
use Agrement\Controller\Factory\AgrementControllerFactory;
use Agrement\Entity\Db\TypeAgrement;
use Agrement\Form\Factory\AgrementFormFactory;
use Agrement\Form\Saisie;
use Agrement\Service\AgrementService;
use Agrement\Service\AgrementServiceFactory;
use Agrement\Service\TblAgrementService;
use Agrement\Service\TblAgrementServiceFactory;
use Agrement\Service\TypeAgrementService;
use Agrement\Service\TypeAgrementServiceFactory;
use Agrement\View\Helper\AgrementVewHelperFactory;
use Application\Provider\Privileges;


return [
    'routes' => [
        'intervenant' => [
            'child_routes' => [
                'agrement' => [
                    'route'         => '/:intervenant/agrement',
                    'controller'    => AgrementController::class,
                    'action'        => 'index',
                    'may_terminate' => true,
                    'child_routes'  => [
                        'conseil-academique' => [
                            'route'    => '/conseil-academique',
                            'action'   => 'lister',
                            'defaults' => [
                                'typeAgrementCode' => TypeAgrement::CODE_CONSEIL_ACADEMIQUE,
                            ],
                        ],
                        'conseil-restreint'  => [
                            'route'    => '/conseil-restreint',
                            'action'   => 'lister',
                            'defaults' => [
                                'typeAgrementCode' => TypeAgrement::CODE_CONSEIL_RESTREINT,
                            ],
                        ],
                        'ajouter'            => [
                            'route'       => '/:typeAgrement/ajouter[/:structure]',
                            'constraints' => [
                                'typeAgrement' => '[0-9]*',
                                'structure'    => '[0-9]*',
                            ],
                            'action'      => 'saisir',
                        ],
                        'voir'               => [
                            'route'       => '/voir/:agrement',
                            'constraints' => [
                                'agrement' => '[0-9]*',
                            ],
                            'action'      => 'voir',
                        ],
                        'saisir'             => [
                            'route'       => '/saisir/[:agrement]',
                            'constraints' => [
                                'agrement' => '[0-9]*',
                            ],
                            'action'      => 'saisir',
                        ],
                        'supprimer'          => [
                            'route'       => '/supprimer/[:agrement]',
                            'constraints' => [
                                'agrement' => '[0-9]*',
                            ],
                            'action'      => 'supprimer',
                        ],
                    ],
                ],
            ],
        ],
        'gestion'     => [
            'child_routes' => [
                'agrement' => [
                    'route'         => '/agrement',
                    'controller'    => AgrementController::class,
                    'action'        => 'index',
                    'may_terminate' => true,
                    'child_routes'  => [
                        'conseil-academique' => [
                            'route'    => '/conseil-academique',
                            'action'   => 'saisir-lot',
                            'defaults' => [
                                'typeAgrementCode' => TypeAgrement::CODE_CONSEIL_ACADEMIQUE,
                            ],
                        ],
                        'conseil-restreint'  => [
                            'route'    => '/conseil-restreint',
                            'action'   => 'saisir-lot',
                            'defaults' => [
                                'typeAgrementCode' => TypeAgrement::CODE_CONSEIL_RESTREINT,
                            ],
                        ],
                        'export-csv'         => [
                            'route'  => '/export-csv',
                            'action' => 'export-csv',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'intervenant' => [
            'pages' => [
                'agrement-conseil-restreint'  => [
                    'label'        => 'Agrément : Conseil restreint',
                    'title'        => 'Agrément : Conseil restreint',
                    'route'        => 'intervenant/agrement/conseil-restreint',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'withtarget'   => true,
                    'order'        => 10,
                ],
                'agrement-conseil-academique' => [
                    'label'        => 'Agrément : Conseil académique',
                    'title'        => 'Agrément : Conseil académique',
                    'route'        => 'intervenant/agrement/conseil-academique',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'withtarget'   => true,
                    'order'        => 11,
                ],
            ],
        ],

        'gestion' => [
            'pages' => [
                'agrement' => [
                    'label'    => "Agréments par lot",
                    'title'    => "Gestion des agréments par lot",
                    'icon'     => 'fas fa-tags',
                    'route'    => 'gestion/agrement',
                    'order'    => 50,
                    'color'    => '#E1AC5A',
                    'pages'    => [
                        'conseil-restreint'  => [
                            'label'       => 'Conseil restreint',
                            'description' => 'Gestion par lots des agréments du conseil restreint',
                            'title'       => 'Conseil restreint',
                            'route'       => 'gestion/agrement/conseil-restreint',
                        ],
                        'conseil-academique' => [
                            'label'       => 'Conseil académique',
                            'description' => 'Gestion par lots des agréments du conseil académique',
                            'title'       => 'Conseil académique',
                            'route'       => 'gestion/agrement/conseil-academique',
                        ],
                        'export-csv'         => [
                            'label'       => 'Export CSV',
                            'description' => 'Export CSV des agrément donnés ou en attente',
                            'title'       => 'Export CSV',
                            'route'       => 'gestion/agrement/export-csv',
                        ],
                    ],
                ],
            ],
        ],

    ],

    'guards' => [
        [
            'controller' => AgrementController::class,
            'action'     => ['index', 'lister', 'voir'],
            'privileges' => [
                Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION,
                Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION,
            ],
            'assertion'  => AgrementAssertion::class,
        ],
        [
            'controller' => AgrementController::class,
            'action'     => ['ajouter', 'saisir-lot', 'saisir'],
            'privileges' => [
                Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION,
                Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION,
                Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION,
                Privileges::AGREMENT_CONSEIL_RESTREINT_EDITION,
            ],
            'assertion'  => AgrementAssertion::class,
        ],
        [
            'controller' => AgrementController::class,
            'action'     => ['export-csv'],
            'privileges' => [
                Privileges::AGREMENT_EXPORT_CSV,
            ],
        ],
        [
            'controller' => AgrementController::class,
            'action'     => ['supprimer'],
            'privileges' => [
                Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION,
                Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION,
            ],
            'assertion'  => AgrementAssertion::class,
        ],
    ],

    'rules' => [
        [
            'privileges' => [
                Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION,
                Privileges::AGREMENT_CONSEIL_RESTREINT_EDITION,
                Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION,
                Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION,
            ],
            'resources'  => ['TblAgrement', 'Agrement', 'Structure'],
            'assertion'  => AgrementAssertion::class,
        ],
    ],

    'controllers'  => [
        AgrementController::class => AgrementControllerFactory::class,
    ],
    'services'     => [
        AgrementService::class     => AgrementServiceFactory::class,
        TblAgrementService::class  => TblAgrementServiceFactory::class,
        TypeAgrementService::class => TypeAgrementServiceFactory::class,
    ],
    'view_helpers' => [
        'agrement' => AgrementVewHelperFactory::class,
    ],

    'forms' => [
        Saisie::class => AgrementFormFactory::class,
    ],
];
