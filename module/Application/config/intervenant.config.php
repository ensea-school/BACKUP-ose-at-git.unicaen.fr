<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'intervenant' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/intervenant',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Intervenant',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action[/:id][?page=:page]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'saisir-dossier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/saisir-dossier/:id',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'saisir-dossier',
                                'id'     => 0,
                            ),
                        ),
                    ),
                    'voir-services' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/voir-services/:id',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voir-services',
                                'id'     => 0,
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
//                    'intervenants' => array(
//                        'label'    => 'Intervenants',
//                        'title'    => "Gestion des intervenants",
//                        'route'    => 'intervenant',
//                        'resource' => 'controller/Application\Controller\Intervenant:index',
//                        'pages' => array(
//                            'voir' => array(
//                                'label'  => "Consultation",
//                                'title'  => "Consultation de la fiche de l'intervenant {id}",
//                                'route'  => 'intervenant/default',
//                                'visible' => false,
//                                'withtarget' => true,
//                                'pages' => array(),
//                            ),
////                            'modifier' => array(
////                                'label'  => "Modifier",
////                                'title'  => "Modifier l'intervenant {id}",
////                                'route'  => 'intervenant/modifier',
////                                'visible' => false,
////                                'withtarget' => true,
////                                'pages' => array(),
////                            ),
//                        ),
//                    ),
                    'intervenant' => array(
                        'label'    => 'Intervenant',
                        'title'    => "Intervenant",
                        'route'    => 'intervenant',
                        'resource' => 'controller/Application\Controller\Intervenant:index',
                        'pages' => array(
                            'dossier' => array(
                                'label'  => "Saisir un dossier",
                                'title'  => "Saisir un dossier d'intervenant vacataire",
                                'route'  => 'intervenant/saisir-dossier',
                                'visible' => false,
                                'withtarget' => true,
                            ),
                            'voir' => array(
                                'label'  => "Fiche intervenant",
                                'title'  => "Consultation de la fiche de l'intervenant {id}",
                                'route'  => 'intervenant/default',
                                'action' => 'voir',
                                'visible' => false,
                                'withtarget' => true,
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Intervenant',
                    'action' => array('index', 'choisir', 'modifier', 'rechercher', 'voir', 'apercevoir', 'search', 'saisir-dossier', 'voir-services'),
                    'roles' => array('user'),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Intervenant' => 'Application\Controller\IntervenantController',
        ),
        'aliases' => array(
            'IntervenantController' => 'Application\Controller\Intervenant',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationOffreFormation' => 'Application\\Service\\OffreFormation',
            'ApplicationIntervenant'    => 'Application\\Service\\Intervenant',
            'ApplicationDossier'        => 'Application\\Service\\Dossier',
        ),
        'initializers' => array(
            'Application\Service\Initializer\IntervenantServiceAwareInitializer',
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
            'IntervenantDossier' => 'Application\Form\Intervenant\Dossier',
        ),
    ),
);