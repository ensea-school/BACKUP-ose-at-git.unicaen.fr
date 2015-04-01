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
            'structure' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/structure',
                    'defaults' => [
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Structure',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'modifier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/modifier/:id',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'recherche' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/recherche[/:term]',
                            'defaults' => [
                                'action' => 'recherche',
                            ],
                        ],
                    ],
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
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'structure' => [
                        'label'    => 'Structures',
                        'title'    => "Gestion des structures",
                        'route'    => 'structure',
                        'visible'  => false,
                        'params' => [
                            'action' => 'index',
                        ],
                        'pages' => [
                            'voir' => [
                                'label'  => "Voir",
                                'title'  => "Voir une structure",
                                'route'  => 'structure',
                                'visible' => false,
                                'withtarget' => true,
                                'pages' => [],
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
                [
                    'controller' => 'Application\Controller\Structure',
                    'action' => ['voir', 'apercevoir'],
                    'roles' => [R_ROLE]
                ],
                [
                    'controller' => 'Application\Controller\Structure',
                    'action' => ['index', 'choisir', 'recherche'],
                    'roles' => [IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID]
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Structure'   => 'Application\Controller\StructureController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationPersonnel'       => 'Application\\Service\\Personnel',
            'ApplicationStructure'       => 'Application\\Service\\Structure',
            'ApplicationTypeStructure'   => 'Application\\Service\\TypeStructure',
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'structureDl'       => 'Application\View\Helper\StructureDl',
            'structure'         => 'Application\View\Helper\StructureViewHelper',
        ],
    ],
];
