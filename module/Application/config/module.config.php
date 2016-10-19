<?php

namespace Application;

use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

$main = [
    'doctrine'           => [
        'connection'    => [
            'orm_default' => [
                'driverClass' => \Doctrine\DBAL\Driver\OCI8\Driver::class,
            ],
        ],
        'driver'        => [
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
                    'CONVERT'       => ORM\Query\Functions\Convert::class,
                    'REPLACE'       => ORM\Query\Functions\Replace::class,
                    'compriseEntre' => ORM\Query\Functions\OseDivers\CompriseEntre::class,
                    'pasHistorise'  => ORM\Query\Functions\OseDivers\PasHistorise::class,
                ],
                'filters'          => [
                    'historique' => ORM\Filter\HistoriqueFilter::class,
                    'annee'      => ORM\Filter\AnneeFilter::class,
                ],
            ],
        ],
        'eventmanager'  => [
            'orm_default' => [
                'subscribers' => [
                    \Doctrine\DBAL\Event\Listeners\OracleSessionInit::class,
                    'UnicaenApp\HistoriqueListener',
                ],
            ],
        ],
        'cache'         => [
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

        'role_providers'     => [
            'ApplicationRoleProvider' => [
                Acl\Role::class,
            ],
        ],
        'guards'             => [
            \BjyAuthorize\Guard\Controller::class => [
                [
                    'controller' => 'Application\Controller\Index',
                    'action'     => ['changement-annee'],
                    'roles'      => ['guest'],
                ],
            ],
        ],
        'resource_providers' => [
            \BjyAuthorize\Provider\Resource\Config::class => [
                'Information' => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'roles'     => ['user'],
                        'resources' => 'Information',
                        'assertion' => 'assertionInformation',
                    ],
                ],
            ],
        ],
    ],
    'service_manager'    => [
        'invokables' => [
            'ApplicationAnnee'              => Service\Annee::class,
            'ApplicationContext'            => Service\Context::class,
            'ApplicationLocalContext'       => Service\LocalContext::class,
            'ApplicationParametres'         => Service\Parametres::class,
            'ApplicationUtilisateur'        => Service\Utilisateur::class,
            'ApplicationTypeIntervention'   => Service\TypeIntervention::class,
            'ApplicationSource'             => Service\Source::class,
            'ApplicationAffectation'        => Service\Affectation::class,
            'ApplicationRole'               => Service\Role::class,
            'ApplicationPays'               => Service\Pays::class,
            'ApplicationDepartement'        => Service\Departement::class,
            'applicationFichier'            => Service\FichierService::class,
            'applicationTauxHoraireHETD'    => Service\TauxHoraireHETDService::class,
            'UnicaenAuth\Service\Privilege' => Service\PrivilegeService::class,
            'assertionInformation'          => Assertion\InformationAssertion::class,
        ],
        'factories'  => [
            'navigation'                  => Service\NavigationFactoryFactory::class,
            'ApplicationRoleProvider'     => Provider\Role\RoleProviderFactory::class,
            'ApplicationIdentityProvider' => Provider\Identity\IdentityProviderFactory::class,
        ],
    ],
    'view_helpers'       => [
        'factories'  => [
            'userProfileSelectRadioItem' => View\Helper\UserProfileSelectRadioItemFactory::class,
            'appLink'                    => View\Helper\AppLinkFactory::class,
        ],
        'invokables' => [
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
        'template_map'        => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            //'error/404'     => __DIR__ . '/../view/error/404.phtml',
            'error/index'   => __DIR__ . '/../view/error/index.phtml',
        ],
        'layout'              => 'layout/layout', // e.g., 'layout/layout'
    ],
    'public_files'       => [
        'head_scripts'   => [
            '020_jqueryui' => 'https://gest.unicaen.fr/public/jquery-ui-1.11.4.minimal/jquery-ui.min.js',
        ],
        'inline_scripts' => [
            '001_' => 'js/datepicker-fr.js',
            '111_' => 'js/service.js',
            '112_' => 'js/service-referentiel.js',
            '113_' => 'js/paiement.js',
            '114_' => 'js/offre-formation.js',
            '115_' => 'js/droits.js',
            '116_' => 'js/piece-jointe.js',
            '117_' => 'https://gest.unicaen.fr/public/bootstrap-select-1.9.4/dist/js/bootstrap-select.min.js',
            '118_' => 'js/indicateur.js',
            '900_' => 'https://gest.unicaen.fr/public/tinymce-4.4.1/js/tinymce/tinymce.min.js',
        ],
        'stylesheets'    => [
            '010_jquery-ui'           => 'https://gest.unicaen.fr/public/jquery-ui-1.11.4.minimal/jquery-ui.min.css',
            '020_jquery-ui-structure' => 'https://gest.unicaen.fr/public/jquery-ui-1.11.4.minimal/jquery-ui.structure.min.css',
            '030_jquery-ui-theme'     => 'https://gest.unicaen.fr/public/jquery-ui-1.11.4.minimal/jquery-ui.theme.min.css',
            '110_'                    => 'https://gest.unicaen.fr/public/bootstrap-select-1.9.4/dist/css/bootstrap-select.min.css',
            '111_'                    => 'css/cartridge.css',
            '112_'                    => 'https://gest.unicaen.fr/public/font-awesome-4.5.0/css/font-awesome.min.css',
            '113_'                    => 'https://gest.unicaen.fr/public/open-sans-gh-pages/open-sans.css',
            '114_'                    => 'css/budget.css',
            '115_'                    => 'css/paiement.css',
            '116_'                    => 'css/agrement.css',
            '117_'                    => 'css/service.css',
            '118_'                    => 'css/acceuil.css',
            '119_'                    => 'css/droits.css',
            '120_'                    => 'css/callout.css',
            '121_'                    => 'css/piece-jointe.css',
            '122_'                    => 'css/indicateur.css',
        ],
    ],
];

return array_merge_recursive(
    $main,
    include 'gestion.config.php',
    include 'intervenant.config.php',
    include 'dossier-pieces.config.php',
    include 'structure.config.php',
    include 'etablissement.config.php',
    include 'recherche.config.php',
    include 'service.config.php',
    include 'volume-horaire.config.php',
    include 'offre-formation.config.php',
    include 'contrat.config.php',
    include 'validation.config.php',

    include 'droits.config.php',
    include 'agrement.config.php',
    include 'formule.config.php',
    include 'workflow.config.php',
    include 'indicateur.config.php',
    include 'paiement.config.php',
    include 'log.config.php',
    include 'pilotage.config.php',
    include 'budget.config.php',
    include 'parametre.config.php'
);
