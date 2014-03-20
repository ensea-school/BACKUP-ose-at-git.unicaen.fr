<?php

namespace Application;

return array(
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                'string_functions' => array(
                    'CONVERT' => 'Common\ORM\Query\Functions\Convert'
                ),
                'filters' => array(
                    'historique' => 'Common\ORM\Filter\HistoriqueFilter',
//                    'validite'   => 'Common\ORM\Filter\ValiditeFilter',
                ),
            )
        ),
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\OCI8\Driver',
            ),
        ),
        'driver' => array(
            'orm_default_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => array(
                    __DIR__ . '/../src/Application/Entity/Db/Mapping',
                ),
            ),
            'orm_default' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                    'Application\Entity\Db' => 'orm_default_driver'
                )
            ),
        ),
        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'Doctrine\DBAL\Event\Listeners\OracleSessionInit',
                    'Common\ORM\Event\Listeners\HistoriqueListener',
                    'Common\ORM\Event\Listeners\ValiditeListener',
                ),
            ),
        ),
        'cache' => array(
            'apc' => array(
                'namespace' => 'OSE__' . __NAMESPACE__,
            ),
        ),
    ),
    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class' => 'Application\Entity\Db\Utilisateur',
//        // telling ZfcUserDoctrineORM to skip the entities it defines
//        'enable_default_entities' => false,
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'recherche' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/recherche/:action',
                    'constraints' => array(
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Recherche',
                    ),
                ),
            ),
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
                    'modifier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/modifier/:id',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'modifier',
                            ),
                        ),
                    ),
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
                ),
            ),
            'demo' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/demo[/:action]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Demo',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
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
                    'demo' => array(
                        'label'    => 'Démo',
                        'route'    => 'demo',
                        'params' => array(
                            'action' => 'index',
                        ),
                        'pages' => array(
                            'of' => array(
                                'label'  => "Offre de formation",
                                'route'  => 'demo',
                                'params' => array(
                                    'action' => 'of',
                                ),
                                'visible' => true,
                                'pages' => array(),
                            ),
//                            'of-avec-ue' => array(
//                                'label'  => "Offre de formation (avec UE)",
//                                'route'  => 'demo',
//                                'params' => array(
//                                    'action' => 'of',
//                                ),
//                                'query' => array(
//                                    'avecUe' => 1,
//                                ),
//                                'visible' => true,
//                                'pages' => array(),
//                            ),
                            'intervenant' => array(
                                'label'  => "Intervenants",
                                'route'  => 'demo',
                                'params' => array(
                                    'action' => 'intervenant',
                                ),
                                'visible' => true,
                                'pages' => array(),
                            ),
                            'service-ref' => array(
                                'label'  => "Service référentiel",
                                'route'  => 'demo',
                                'params' => array(
                                    'action' => 'saisir-service-referentiel-intervenant',
                                ),
                                'visible' => true,
                                'pages' => array(),
                            ),
                        ),
                    ),
                    'intervenant' => array(
                        'label'    => 'Intervenant',
                        'title'    => "Gestion des intervenants",
                        'route'    => 'intervenant',
                        'resource' => 'controller/Application\Controller\Intervenant:index',
                        'pages' => array(
//                            'rechercher' => array(
//                                'label'  => "Rechercher",
//                                'title'  => "Rechercher un intervenant",
//                                'route'  => 'intervenant/default',
//                                'params' => array(
//                                    'action' => 'rechercher',
//                                ),
//                                'visible' => true,
//                                'pages' => array(),
//                            ),
                            'voir' => array(
                                'label'  => "Voir",
                                'title'  => "Voir l'intervenant {id}",
                                'route'  => 'intervenant/default',
                                'visible' => false,
                                'withtarget' => true,
                                'pages' => array(),
                            ),
//                            'modifier' => array(
//                                'label'  => "Modifier",
//                                'title'  => "Modifier l'intervenant {id}",
//                                'route'  => 'intervenant/modifier',
//                                'visible' => false,
//                                'withtarget' => true,
//                                'pages' => array(),
//                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'role_providers' => array(
            /**
             * Fournit les rôles issus de la base de données éventuelle de l'appli.
             * NB: si le rôle par défaut 'guest' est fourni ici, il ne sera pas ajouté en double dans les ACL.
             * NB: si la connexion à la base échoue, ce n'est pas bloquant!
             */
//            'UnicaenAuth\Provider\Role\DbRole' => array(
//                'object_manager'    => 'doctrine.entitymanager.orm_default',
//                'role_entity_class' => 'Application\Entity\Db\UtilisateurRole',
//            ),
        ),
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Demo', 
                    'roles' => array('user')),
                array(
                    'controller' => 'Application\Controller\Intervenant', 
                    'action' => array('index', 'choisir', 'modifier', 'rechercher', 'voir', 'saisirServiceReferentiel', 'search'), 
                    'roles' => array('user')),
                array(
                    'controller' => 'Application\Controller\Recherche', 
                    'roles' => array('user')),
            ),
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'Common\ORM\Event\Listeners\HistoriqueListener'  => 'Common\ORM\Event\Listeners\HistoriqueListener',
            'Common\ORM\Event\Listeners\ValiditeListener'    => 'Common\ORM\Event\Listeners\ValiditeListener',
        ),
        'factories' => array(
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index'       => 'Application\Controller\IndexController',
            'Application\Controller\Demo'        => 'Application\Controller\DemoController',
            'Application\Controller\Intervenant' => 'Application\Controller\IntervenantController',
            'Application\Controller\Recherche'   => 'Application\Controller\RechercheController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
//            'Application\View\Renderer\ModalStrategyFactory',
        ),
    ),
);
