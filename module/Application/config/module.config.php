<?php

namespace Application;

$main =  array(
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                'string_functions' => array(
                    'CONVERT'  => 'Common\ORM\Query\Functions\Convert',
                    'CONTAINS' => 'Common\ORM\Query\Functions\Contains',
                    'OSE_DIVERS.STRUCTURE_DANS_STRUCTURE' => 'Common\ORM\Query\Functions\OseDivers\StructureDansStructure',
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
    'unicaen-auth' => array(
        /**
         * Fournisseurs d'identité.
         */
        'identity_providers' => array(
//            200 => 'UnicaenAuth\Provider\Identity\Db',
//            100 => 'UnicaenAuth\Provider\Identity\Ldap',
            50  => 'ApplicationIdentityProvider'
        ),
    ),
    'bjyauthorize' => array(
        'role_providers' => array(
            /**
             * 
             */
            'ApplicationRoleProvider' => array(),
            
            /**
             * Rôles issus de l'annuaire LDAP
             */
//            'UnicaenAuth\Provider\Role\Config' => array(
//                // intervant = rôle de base
//                'intervenant' => array('name' => "Intervenant", 'children' => array(
//                    // gestionnaires de composantes
//                    'cn=ucbn_composantes_responsables,ou=groups,dc=unicaen,dc=fr' => array('name' => "Responsable de composante", 'children' => array(
//                        // directeurs de composantes
//                        'cn=ucbn_composantes_directeurs,ou=groups,dc=unicaen,dc=fr' => array('name' => "Directeur de composante", 'children' => array(
//                            // administrateur de l'appli
////                            'cn=admin_cartagen,ou=groups,dc=unicaen,dc=fr',
//                        )),
//                    )),
//                )),
//            ),
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'AuthenticatedUserSavedListener'                 => 'Application\AuthenticatedUserSavedListener',
            'Common\ORM\Event\Listeners\HistoriqueListener'  => 'Common\ORM\Event\Listeners\HistoriqueListener',
            'Common\ORM\Event\Listeners\ValiditeListener'    => 'Common\ORM\Event\Listeners\ValiditeListener',
            'ApplicationContextProvider'                     => 'Application\\Service\\ContextProvider',
            'ApplicationParametres'                          => 'Application\\Service\\Parametres',
            'ApplicationTypeIntervention'                    => 'Application\\Service\\TypeIntervention',
            'ApplicationSource'                              => 'Application\\Service\\Source',
            'ApplicationRole'                                => 'Application\\Service\\Role',
            'ApplicationRoleUtilisateur'                     => 'Application\\Service\\RoleUtilisateur',
            'NavigationPageVisibility'                       => 'Application\\Service\\NavigationPageVisibility',
        ),
        'factories' => array(
            'navigation'                  => 'Application\Service\NavigationFactoryFactory',
            'ApplicationRoleProvider'     => 'Application\Provider\Role\RoleProviderFactory',
            'ApplicationIdentityProvider' => 'Application\Provider\Identity\IdentityProviderFactory',
        ),
        'initializers' => array(
            'Application\Service\ContextProviderAwareInitializer',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'historiqueDl'      => 'Application\View\Helper\HistoriqueDl',
        ),
        'initializers' => array(
            'Application\Service\ContextProviderAwareInitializer',
        ),
    ),
    'form_elements' => array(
        'initializers' => array(
            'Application\Service\ContextProviderAwareInitializer',
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
