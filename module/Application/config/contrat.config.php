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
use Application\Assertion\ContratAssertion;

return [
    'router' => [
        'routes' => [
            'contrat' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/contrat',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Contrat',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'creer' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'creer-contrat',
                            ],
                        ],
                    ],
                    'creer-avenant' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'creer-avenant',
                            ],
                        ],
                    ],
                    'voir' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'voir',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:contrat/supprimer',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'valider' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:contrat/valider',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'valider',
                            ],
                        ],
                    ],
                    'devalider' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:contrat/devalider',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'devalider',
                            ],
                        ],
                    ],
                    'saisir-retour' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:contrat/saisir-retour',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'saisir-retour',
                            ],
                        ],
                    ],
                    'exporter' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:contrat/exporter',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'exporter',
                            ],
                        ],
                    ],
                    'deposer-fichier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:contrat/deposer-fichier',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'deposer-fichier',
                            ],
                        ],
                    ],
                    'lister-fichier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:contrat/lister-fichier',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'lister-fichier',
                            ],
                        ],
                    ],
                    'telecharger-fichier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:contrat/telecharger-fichier[/:fichier/:nomFichier]',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'telecharger-fichier',
                            ],
                        ],
                    ],
                    'supprimer-fichier' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/:contrat/supprimer-fichier[/:fichier]',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                                'fichier' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'supprimer-fichier',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
//    'navigation' => array(
//        'default' => array(
//            'home' => array(
//                'pages' => array(
//                    'contrat' => array(
//                        'label'    => 'Contrat et avenants',
//                        'title'    => "Contrat et avenants de l'intervenant",
//                        'route'    => 'contrat/voir',
//                        'withtarget' => true,
//                        'resource' => 'controller/Application\Controller\Contrat:voir',
//                        'pages' => array(
//                            'exporter-contrat' => array(
//                                'label'  => "Exporter",
//                                'title'  => "Exporter le contrat de l'intervenant au format PDF",
//                                'route'  => 'contrat/exporter',
//                                'withtarget' => true,
//                                'resource' => 'controller/Application\Controller\Contrat:exporter',
//                            ),
//                        ),
//                    ),
//                ),
//            ),
//        ),
//    ),
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => [
                        'creer', 'supprimer', 'exporter', 'valider', 'devalider', 'saisir-retour',
                        'deposer-fichier', 'supprimer-fichier',
                    ],
                    'roles'      => [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => [
                        'index', 'voir',
                        'telecharger-fichier', 'lister-fichier',
                    ],
                    'roles'      => [IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Contrat' => [],
            ],
        ],
        'rule_providers' => [
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                    [
                        [IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                        'Contrat',
                        [ContratAssertion::PRIVILEGE_READ],
                        'ContratAssertion',
                    ],
                    [
                        [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                        'Contrat',
                        [
                            ContratAssertion::PRIVILEGE_CREATE,
                            ContratAssertion::PRIVILEGE_DELETE,
                            ContratAssertion::PRIVILEGE_UPDATE,
                            ContratAssertion::PRIVILEGE_EXPORTER,
                            ContratAssertion::PRIVILEGE_VALIDER,
                            ContratAssertion::PRIVILEGE_DEVALIDER,
                            ContratAssertion::PRIVILEGE_DATE_RETOUR,
                            ContratAssertion::PRIVILEGE_DEPOSER],
                        'ContratAssertion',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Contrat' => 'Application\Controller\ContratController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationContrat'          => 'Application\\Service\\Contrat',
            'ApplicationTypeContrat'      => 'Application\\Service\\TypeContrat',
            'ApplicationContratProcess'   => 'Application\\Service\\Process\\ContratProcess',
            'NecessiteContratRule'        => 'Application\Rule\Intervenant\NecessiteContratRule',
            'PossedeContratRule'          => 'Application\Rule\Intervenant\PossedeContratRule',
            'PeutCreerContratInitialRule' => 'Application\Rule\Intervenant\PeutCreerContratInitialRule',
            'PeutCreerAvenantRule'        => 'Application\Rule\Intervenant\PeutCreerAvenantRule',
            'ContratAssertion'            => 'Application\\Assertion\\ContratAssertion',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],
    'form_elements' => [
        'invokables' => [
        ],
    ],
];
