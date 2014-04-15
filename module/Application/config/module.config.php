<?php

namespace Application;

$main =  array(
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                'string_functions' => array(
                    'CONVERT'  => 'Common\ORM\Query\Functions\Convert',
                    'CONTAINS' => 'Common\ORM\Query\Functions\Contains',
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
    ),
    'service_manager' => array(
        'invokables' => array(
            'Common\ORM\Event\Listeners\HistoriqueListener'  => 'Common\ORM\Event\Listeners\HistoriqueListener',
            'Common\ORM\Event\Listeners\ValiditeListener'    => 'Common\ORM\Event\Listeners\ValiditeListener',
            'ApplicationContext'                             => 'Application\\Service\\Context',
            'ApplicationParametres'                          => 'Application\\Service\\Parametres',
            'ApplicationTypeIntervention'                    => 'Application\\Service\\TypeIntervention',
        ),
        'factories' => array(
            'ModalStrategy' => 'Application\View\Renderer\ModalStrategyFactory',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'historiqueDl'      => 'Application\View\Helper\HistoriqueDl',
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
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
//            'ModalStrategy',
        ),
    ),
);

return array_merge_recursive(
    $main,
    include 'intervenant.config.php',
    include 'structure.config.php',
    include 'etablissement.config.php',
    include 'demo.config.php',
    include 'recherche.config.php',
    include 'service.config.php',
    include 'volume-horaire.config.php',
    include 'offre-formation.config.php'
);
