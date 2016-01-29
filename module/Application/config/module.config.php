<?php

namespace Application;

const R_ADMINISTRATEUR        = Acl\AdministrateurRole::ROLE_ID;
const R_COMPOSANTE            = Acl\ComposanteRole::ROLE_ID;
const R_ETABLISSEMENT         = Acl\EtablissementRole::ROLE_ID;
const R_INTERVENANT           = Acl\IntervenantRole::ROLE_ID;
const R_INTERVENANT_EXTERIEUR = Acl\IntervenantExterieurRole::ROLE_ID;


$main = [
    'doctrine'           => [
        'connection'   => [
            'orm_default' => [
                'driverClass' => \Doctrine\DBAL\Driver\OCI8\Driver::class,
            ],
        ],
        'driver'       => [
            'orm_default_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\XmlDriver::class,
                'paths' => [
                    __DIR__ . '/../src/Application/Entity/Db/Mapping',
                ],
            ],
            'orm_default'        => [
                'class'   => \Doctrine\ORM\Mapping\Driver\DriverChain::class,
                'drivers' => [
                    'Application\Entity\Db' => 'orm_default_driver',
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'string_functions' => [
                    'CONVERT'                             => ORM\Query\Functions\Convert::class,
                    'CONTAINS'                            => ORM\Query\Functions\Contains::class,
                    'REPLACE'                             => ORM\Query\Functions\Replace::class,
                    'OSE_DIVERS_STRUCTURE_DANS_STRUCTURE' => ORM\Query\Functions\OseDivers\StructureDansStructure::class,
                    'compriseEntre'                       => ORM\Query\Functions\OseDivers\CompriseEntre::class,
                    'pasHistorise'                        => ORM\Query\Functions\OseDivers\PasHistorise::class,
                ],
                'filters'          => [
                    'historique' => ORM\Filter\HistoriqueFilter::class,
                    'annee'      => ORM\Filter\AnneeFilter::class,
                ],
            ],
        ],
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    \Doctrine\DBAL\Event\Listeners\OracleSessionInit::class,
                    'UnicaenApp\HistoriqueListener',
                ],
            ],
        ],
        'cache'        => [
            'apc' => [
                'namespace' => 'OSE__' . __NAMESPACE__,
            ],
        ],
    ],
    'zfcuser'            => [
        'user_entity_class' => Entity\Db\Utilisateur::class,
    ],
    'router'             => [
        'routes' => [
            'home'             => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ],
                ],
            ],
            'application'      => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/application',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
            'changement-annee' => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/changement-annee/:annee',
                    'constraints' => [
                        'annee' => '[0-9]*',
                    ],
                    'defaults'    => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'changement-annee',
                    ],
                ],
            ],
        ],
    ],
    'navigation'         => [
        'default' => [
            'home' => [
                'pages' => [
                    'intervenant' => [
                        // réservation de l'emplacement pour le menu Intervenant
                    ],
                    'service'     => [
                        // réservation de l'emplacement pour le menu Enseignements
                    ],
                    'of'          => [
                        // réservation de l'emplacement pour le menu Offre de formation
                    ],
                    'gestion'     => [
                        // réservation de l'emplacement pour le menu Gestion
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'       => [
        'identity_provider' => 'ApplicationIdentityProvider',

        'role_providers' => [
            'ApplicationRoleProvider' => [
                Acl\Role::class,

                Acl\AdministrateurRole::class,

                Acl\ComposanteRole::class,

                Acl\EtablissementRole::class,
                Acl\IntervenantRole::class,
                Acl\IntervenantExterieurRole::class,
                Acl\IntervenantPermanentRole::class,
            ],
        ],
        'guards'         => [
            \BjyAuthorize\Guard\Controller::class => [
                [
                    'controller' => 'Application\Controller\Index',
                    'action'     => ['changement-annee'],
                    'roles'      => ['guest'],
                ],
            ],
        ],
    ],
    'service_manager'    => [
        'invokables'         => [
            'ApplicationAnnee'                    => Service\Annee::class,
            'ApplicationContext'                  => Service\Context::class,
            'ApplicationLocalContext'             => Service\LocalContext::class,
            'ApplicationParametres'               => Service\Parametres::class,
            'ApplicationUtilisateur'              => Service\Utilisateur::class,
            'ApplicationTypeIntervention'         => Service\TypeIntervention::class,
            'ApplicationSource'                   => Service\Source::class,
            'ApplicationAffectation'              => Service\Affectation::class,
            'ApplicationRole'                     => Service\Role::class,
            'ApplicationPays'                     => Service\Pays::class,
            'ApplicationDepartement'              => Service\Departement::class,
            'IntervenantNavigationPageVisibility' => Service\IntervenantNavigationPageVisibility::class,
            'UnicaenAuth\Service\Privilege'       => Service\PrivilegeService::class,
        ],
        'factories'          => [
            'navigation'                  => Service\NavigationFactoryFactory::class,
            'ApplicationRoleProvider'     => Provider\Role\RoleProviderFactory::class,
            'ApplicationIdentityProvider' => Provider\Identity\IdentityProviderFactory::class,
        ],
        'abstract_factories' => [
        ],
    ],
    'view_helpers'       => [
        'factories'  => [
            'userProfileSelectRadioItem' => View\Helper\UserProfileSelectRadioItemFactory::class,
            'appLink'                    => View\Helper\AppLinkFactory::class,
        ],
        'invokables' => [
            'validation'      => View\Helper\ValidationViewHelper::class,
            'utilisateur'     => View\Helper\UtilisateurViewHelper::class,
            'formSupprimer'   => View\Helper\FormSupprimerViewHelper::class,
            'formButtonGroup' => View\Helper\FormButtonGroupViewHelper::class,
            'cartridge'       => View\Helper\CartridgeViewHelper::class,
        ],
    ],
    'translator'         => [
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ],
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '/%s/Oracle_Errors.php',
            ],
        ],
    ],
    'controllers'        => [
        'invokables' => [
            'Application\Controller\Index'       => Controller\IndexController::class,
            'UnicaenAuth\Controller\Utilisateur' => Controller\UtilisateurController::class,
        ],
    ],
    'controller_plugins' => [
        'invokables' => [
            'em'      => Controller\Plugin\Em::class,
            'context' => Controller\Plugin\Context::class,
        ],
    ],
    'view_manager'       => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'public_files'       => [
        'inline_scripts' => [
            10 => 'js/datepicker-fr.js',
            11 => 'js/service.js',
            12 => 'js/service-referentiel.js',
            13 => 'js/paiement.js',
            14 => 'js/offre-formation.js',
            15 => 'js/droits.js',
            50 => 'https://gest.unicaen.fr/public/bootstrap-select-1.9.4/dist/js/bootstrap-select.min.js',
        ],
        'stylesheets'    => [
            10 => 'https://gest.unicaen.fr/public/bootstrap-select-1.9.4/dist/css/bootstrap-select.min.css',
            11 => 'css/cartridge.css',
            12 => 'https://gest.unicaen.fr/public/font-awesome-4.5.0/css/font-awesome.min.css',
            13 => 'https://gest.unicaen.fr/public/open-sans-gh-pages/open-sans.css',
            14 => 'css/budget.css',
            15 => 'css/paiement.css',
            16 => 'css/agrement.css',
            17 => 'css/service.css',
            18 => 'css/acceuil.css',
            19 => 'css/droits.css',
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
    include 'droits.config.php',
    include 'agrement.config.php',
    include 'formule.config.php',
    include 'workflow.config.php',
    include 'indicateur.config.php',
    include 'notification.config.php',
    include 'paiement.config.php',
    include 'log.config.php',
    include 'message.config.php',
    include 'pilotage.config.php',
    include 'budget.config.php'
);
