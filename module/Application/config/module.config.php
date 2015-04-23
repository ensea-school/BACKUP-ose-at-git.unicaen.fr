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

$main =  [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\OCI8\Driver',
            ],
        ],
        'driver' => [
            'orm_default_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => [
                    __DIR__ . '/../src/Application/Entity/Db/Mapping',
                ],
            ],
            'orm_default' => [
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => [
                    'Application\Entity\Db' => 'orm_default_driver'
                ]
            ],
        ],
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    'Doctrine\DBAL\Event\Listeners\OracleSessionInit',
                    'Common\ORM\Event\Listeners\HistoriqueListener',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'OSE__' . __NAMESPACE__,
            ],
        ],
    ],
    'zfcuser' => [
        // telling ZfcUser to use our own class
        'user_entity_class' => 'Application\Entity\Db\Utilisateur',
//        // telling ZfcUserDoctrineORM to skip the entities it defines
//        'enable_default_entities' => false,
    ],
    'router' => [
        'routes' => [
            'home' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ],
                ],
            ],
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/application',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'Index',
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
                    'intervenant' => [
                        // réservation de l'emplacement pour le menu Intervenant
                    ],
                    'service' => [
                        // réservation de l'emplacement pour le menu Enseignements
                    ],
                    'of' => [
                        // réservation de l'emplacement pour le menu Offre de formation
                    ],
                    'gestion' => [
                        // réservation de l'emplacement pour le menu Gestion
                    ],
                ],
            ],
        ],
    ],
    'unicaen-auth' => [
        /**
         * Fournisseurs d'identité.
         */
        'identity_providers' => [
//            200 => 'UnicaenAuth\Provider\Identity\Db',
//            100 => 'UnicaenAuth\Provider\Identity\Ldap',
            50  => 'ApplicationIdentityProvider'
        ],
    ],
    'bjyauthorize' => [
        'role_providers' => [
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
        ],
        'guards' => [
            'Application\Guard\ControllerGuard' => [],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'AuthenticatedUserSavedListener'                 => 'Application\AuthenticatedUserSavedListener',
            'Common\ORM\Event\Listeners\HistoriqueListener'  => 'Common\ORM\Event\Listeners\HistoriqueListener',
            'ApplicationAnnee'                               => 'Application\\Service\\Annee',
            'ApplicationContext'                             => 'Application\\Service\\Context',
            'ApplicationLocalContext'                        => 'Application\\Service\\LocalContext',
            'ApplicationParametres'                          => 'Application\\Service\\Parametres',
            'ApplicationUtilisateur'                         => 'Application\\Service\\Utilisateur',
            'ApplicationTypeIntervention'                    => 'Application\\Service\\TypeIntervention',
            'ApplicationSource'                              => 'Application\\Service\\Source',
            'ApplicationRole'                                => 'Application\\Service\\Role',
            'ApplicationTypeRole'                            => 'Application\\Service\\TypeRole',
            'IntervenantNavigationPageVisibility'            => 'Application\\Service\\IntervenantNavigationPageVisibility',
            'ApplicationRuleProvider'                        => 'Application\Provider\Rule\RuleProvider',
        ],
        'factories' => [
            'navigation'                  => 'Application\Service\NavigationFactoryFactory',
            'ApplicationRoleProvider'     => 'Application\Provider\Role\RoleProviderFactory',
            'ApplicationIdentityProvider' => 'Application\Provider\Identity\IdentityProviderFactory',
        ],
        'abstract_factories' => [
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'userProfileSelectRadioItem' => 'Application\View\Helper\UserProfileSelectRadioItemFactory',
            'appLink'                    => 'Application\View\Helper\AppLinkFactory',
        ],
        'invokables' => [
            'historiqueDl' => 'Application\View\Helper\HistoriqueDl',
            'validationDl' => 'Application\View\Helper\ValidationDl',
            'mailto'       => 'Application\View\Helper\Mailto',
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Index'   => 'Application\Controller\IndexController',
            'UnicaenAuth\Controller\Utilisateur' => 'Application\Controller\UtilisateurController',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
//            'ModalStrategy',
        ],
    ],
];

return array_merge_recursive(
    $main,
    include 'intervenant.config.php',
    include 'piece-jointe.config.php',
    include 'structure.config.php',
    include 'etablissement.config.php',
    include 'recherche.config.php',
    include 'service.config.php',
    include 'volume-horaire.config.php',
    include 'offre-formation.config.php',
    include 'contrat.config.php',
    include 'validation.config.php',
    include 'gestion.config.php',
    include 'agrement.config.php',
    include 'formule.config.php',
    include 'workflow.config.php',
    include 'indicateur.config.php',
    include 'notification.config.php',
    include 'paiement.config.php'
);
