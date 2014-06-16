<?php

namespace Application;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantExterieurRole;

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
                            'route'    => '/:action[/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'rechercher' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/rechercher[/:id]',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'rechercher',
                            ),
                        ),
                    ),
                    'fiche' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:id',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voir',
                                'id'     => 0,
                            ),
                        ),
                    ),
                    'modification-service-du' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:id/modification-service-du',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'ModificationServiceDu',
                                'action'     => 'saisir',
                            ),
                        ),
                    ),
                    'saisir-dossier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:id/saisir-dossier',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Dossier',
                                'action' => 'modifier',
                                'id'     => 0,
                            ),
                        ),
                    ),
                    'services' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:id/services',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Application\Controller\Service',
                                'action' => 'intervenant',
                                'id'     => 0,
                            ),
                        ),
                    ),
                    'pieces-jointes' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:id/pieces-jointes',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Dossier',
                                'action' => 'pieces-jointes',
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
                    'intervenant' => array(
                        'label'    => 'Intervenant',
                        'title'    => "Intervenant",
                        'route'    => 'intervenant',
                        'resource' => 'controller/Application\Controller\Intervenant:index',
                        'pages' => array(
                            'rechercher' => array(
                                'label'  => "Rechercher",
                                'title'  => "Rechercher un intervenant",
                                'route'  => 'intervenant/rechercher',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Intervenant:rechercher',
                            ),
                            'fiche' => array(
                                'label'  => "Fiche",
                                'title'  => "Consultation de la fiche de l'intervenant {id}",
                                'route'  => 'intervenant/fiche',
                                'action' => 'voir',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Intervenant:voir',
                            ),
                            'modification-service-du' => array(
                                'label'  => "Modification de service dû",
                                'title'  => "Modification de service dû de l'intervenant {id}",
                                'route'  => 'intervenant/modification-service-du',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\ModificationServiceDu:saisir',
                            ),
                            'dossier' => array(
                                'label'  => "Dossier",
                                'title'  => "Saisir/modifier un dossier d'intervenant vacataire",
                                'route'  => 'intervenant/saisir-dossier',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Dossier:modifier',
                            ),
                            'service' => array(
                                'label'  => "Services",
                                'title'  => "Services et référentiel de l'intervenant",
                                'route'  => 'intervenant/services',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Service:intervenant',
                            ),
                            'pieces-jointes' => array(
                                'label'  => "Pièces justificatives",
                                'title'  => "Pièces justificatives du dossier de l'intervenant",
                                'route'  => 'intervenant/pieces-jointes',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Dossier:pieces-jointes',
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
                    'action'     => array('index', 'apercevoir'),
                    'roles'      => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => array('voir', 'choisir', 'rechercher', 'search'),
                    'roles'      => array(ComposanteRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => array('voir', 'modifier', 'pieces-jointes'),
                    'roles'      => array(IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\ModificationServiceDu',
                    'action'     => array('saisir'),
                    'roles'      => array(ComposanteRole::ROLE_ID),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Intervenant'           => 'Application\Controller\IntervenantController',
            'Application\Controller\Dossier'               => 'Application\Controller\DossierController',
            'Application\Controller\ModificationServiceDu' => 'Application\Controller\ModificationServiceDuController',
        ),
        'aliases' => array(
            'IntervenantController' => 'Application\Controller\Intervenant',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationOffreFormation'        => 'Application\\Service\\OffreFormation',
            'ApplicationIntervenant'           => 'Application\\Service\\Intervenant',
            'ApplicationCivilite'              => 'Application\\Service\\Civilite',
            'ApplicationStatutIntervenant'     => 'Application\\Service\\StatutIntervenant',
            'ApplicationDossier'               => 'Application\\Service\\Dossier',
            'ApplicationPieceJointe'           => 'Application\\Service\\PieceJointe',
            'ApplicationPieceJointeProcess'    => 'Application\\Service\\Process\PieceJointeProcess',
            'ApplicationTypePieceJointe'       => 'Application\\Service\\TypePieceJointe',
            'ApplicationTypePieceJointeStatut' => 'Application\\Service\\TypePieceJointeStatut',
        ),
        'initializers' => array(
            'Application\Service\Initializer\IntervenantServiceAwareInitializer',
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
            'IntervenantDossier' => 'Application\Form\Intervenant\Dossier',
            'IntervenantModificationServiceDuForm'          => 'Application\Form\Intervenant\ModificationServiceDuForm',
            'IntervenantModificationServiceDuFieldset'      => 'Application\Form\Intervenant\ModificationServiceDuFieldset',
            'IntervenantMotifModificationServiceDuFieldset' => 'Application\Form\Intervenant\MotifModificationServiceDuFieldset',
        ),
    ),
);
