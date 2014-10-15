<?php

namespace Application;

const R_USER                        = 'user';

const R_ROLE                        = Acl\Role::ROLE_ID;

const R_ADMINISTRATEUR              = Acl\AdministrateurRole::ROLE_ID;

const R_COMPOSANTE                  = Acl\ComposanteRole::ROLE_ID;
const R_DIRECTEUR_COMPOSANTE        = Acl\DirecteurComposanteRole::ROLE_ID;
const R_GESTIONNAIRE_COMPOSANTE     = Acl\GestionnaireComposanteRole::ROLE_ID;
const R_RESPONSABLE_COMPOSANTE      = Acl\ResponsableComposanteRole::ROLE_ID;
const R_SUPERVISEUR_COMPOSANTE      = Acl\SuperviseurComposanteRole::ROLE_ID;

const R_RESPONSABLE_RECHERCHE_LABO  = Acl\ResponsableRechercheLaboRole::ROLE_ID;

const R_DRH                         = Acl\DrhRole::ROLE_ID;
const R_GESTIONNAIRE_DRH            = Acl\GestionnaireDrhRole::ROLE_ID;
const R_RESPONSABLE_DRH             = Acl\ResponsableDrhRole::ROLE_ID;

const R_ETABLISSEMENT               = Acl\EtablissementRole::ROLE_ID;
const R_SUPERVISEUR_ETABLISSEMENT   = Acl\SuperviseurEtablissementRole::ROLE_ID;

const R_INTERVENANT                 = Acl\IntervenantRole::ROLE_ID;
const R_INTERVENANT_PERMANENT       = Acl\IntervenantPermanentRole::ROLE_ID;
const R_INTERVENANT_EXTERIEUR       = Acl\IntervenantExterieurRole::ROLE_ID;

const R_FOAD                        = Acl\FoadRole::ROLE_ID;
const R_RESPONSABLE_FOAD            = Acl\ResponsableFoadRole::ROLE_ID;

$R_ALL = [R_ADMINISTRATEUR, R_COMPOSANTE, R_RESPONSABLE_RECHERCHE_LABO, R_DRH, R_ETABLISSEMENT, R_INTERVENANT, R_FOAD];
$R_NOT_INTERVENANT = [R_ADMINISTRATEUR, R_COMPOSANTE, R_RESPONSABLE_RECHERCHE_LABO, R_DRH, R_ETABLISSEMENT, R_FOAD];
$R_COMMUN = [R_ADMINISTRATEUR, R_DRH, R_ETABLISSEMENT, R_FOAD];

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
    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
                    'intervenant' => array(
                        // réservation de l'emplacement pour le menu Intervenant
                    ),
                    'service' => array(
                        // réservation de l'emplacement pour le menu Enseignements
                    ),
                    'of' => array(
                        // réservation de l'emplacement pour le menu Offre de formation
                    ),
                    'gestion' => array(
                        // réservation de l'emplacement pour le menu Gestion
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
            'ApplicationRoleProvider' => [
                'Application\\Acl\\Role',

                'Application\\Acl\\AdministrateurRole',

                'Application\\Acl\\ComposanteRole',
                    'Application\\Acl\\DirecteurComposanteRole',
                    'Application\\Acl\\GestionnaireComposanteRole',
                    'Application\\Acl\\ResponsableComposanteRole',
                    'Application\\Acl\\SuperviseurComposanteRole',
                    'Application\\Acl\\ResponsableRechercheLaboRole',

                'Application\\Acl\\DrhRole',
                    'Application\\Acl\\GestionnaireDrhRole',
                    'Application\\Acl\\ResponsableDrhRole',

                'Application\\Acl\\EtablissementRole',
                    'Application\\Acl\\SuperviseurEtablissementRole',

                'Application\\Acl\\FoadRole',
                    'Application\\Acl\\ResponsableFoadRole',

                'Application\\Acl\\IntervenantRole',
                    'Application\\Acl\\IntervenantExterieurRole',
                    'Application\\Acl\\IntervenantPermanentRole',
            ],
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'AuthenticatedUserSavedListener'                 => 'Application\AuthenticatedUserSavedListener',
            'Common\ORM\Event\Listeners\HistoriqueListener'  => 'Common\ORM\Event\Listeners\HistoriqueListener',
            'Common\ORM\Event\Listeners\ValiditeListener'    => 'Common\ORM\Event\Listeners\ValiditeListener',
            'ApplicationContextProvider'                     => 'Application\\Service\\ContextProvider',
            'ApplicationlocalContext'                        => 'Application\\Service\\LocalContext',
            'ApplicationParametres'                          => 'Application\\Service\\Parametres',
            'ApplicationTypeIntervention'                    => 'Application\\Service\\TypeIntervention',
            'ApplicationSource'                              => 'Application\\Service\\Source',
            'ApplicationRole'                                => 'Application\\Service\\Role',
            'ApplicationTypeRole'                            => 'Application\\Service\\TypeRole',
            'IntervenantNavigationPageVisibility'            => 'Application\\Service\\IntervenantNavigationPageVisibility',
        ),
        'factories' => array(
            'navigation'                  => 'Application\Service\NavigationFactoryFactory',
            'ApplicationRoleProvider'     => 'Application\Provider\Role\RoleProviderFactory',
            'ApplicationIdentityProvider' => 'Application\Provider\Identity\IdentityProviderFactory',
        ),
        'abstract_factories' => array(
        ),
        'initializers' => array(
            'Application\Service\ContextProviderAwareInitializer',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'historiqueDl' => 'Application\View\Helper\HistoriqueDl',
            'validationDl' => 'Application\View\Helper\ValidationDl',
            'mailto'       => 'Application\View\Helper\Mailto',
            'contextProvider' => 'Application\View\Helper\ContextProvider',
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
            'Application\Controller\Index'   => 'Application\Controller\IndexController',
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
    include 'piece-jointe.config.php',
    include 'structure.config.php',
    include 'etablissement.config.php',
    include 'demo.config.php',
    include 'recherche.config.php',
    include 'service.config.php',
    include 'volume-horaire.config.php',
    include 'offre-formation.config.php',
    include 'contrat.config.php',
    include 'validation.config.php',
    include 'agrement.config.php',
    include 'gestion.config.php'
);
