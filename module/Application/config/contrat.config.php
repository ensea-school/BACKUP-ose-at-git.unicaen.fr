<?php

namespace Application;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantExterieurRole;
    
return array(
    'router' => array(
        'routes' => array(
            'contrat' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/contrat',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Contrat',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'creer' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'creer-contrat',
                            ),
                        ),
                    ),
                    'creer-avenant' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'creer-avenant',
                            ),
                        ),
                    ),
                    'voir' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voir',
                            ),
                        ),
                    ),
                    'valider' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:contrat/valider',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'valider',
                            ),
                        ),
                    ),
                    'devalider' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:contrat/devalider',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'devalider',
                            ),
                        ),
                    ),
                    'saisir-retour' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:contrat/saisir-retour',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'saisir-retour',
                            ),
                        ),
                    ),
                    'exporter' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:contrat/exporter',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'exporter',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
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
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => array('creer', 'valider', 'devalider', 'saisir-retour'),
                    'roles'      => array(ComposanteRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => array('index', 'voir', 'exporter'),
                    'roles'      => array(IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID),
                ),
            ),
        ),
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'Contrat' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(
                        array(ComposanteRole::ROLE_ID), 
                        'Contrat', 
                        array('create', 'read', 'delete', 'update'), 
                        'ContratAssertion',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Contrat' => 'Application\Controller\ContratController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationContrat'          => 'Application\\Service\\Contrat',
            'ApplicationTypeContrat'      => 'Application\\Service\\TypeContrat',
            'ApplicationContratProcess'   => 'Application\\Service\\Process\\ContratProcess',
            'PeutCreerContratInitialRule' => 'Application\Rule\Intervenant\PeutCreerContratInitialRule',
            'PeutCreerAvenantRule'        => 'Application\Rule\Intervenant\PeutCreerAvenantRule',
            'ContratAssertion'            => 'Application\\Service\\Assertion\\ContratAssertion',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
        ),
    ),
);
