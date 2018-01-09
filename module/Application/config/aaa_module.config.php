<?php

namespace Application;

use Application\Mouchard\MouchardCompleterContextFactory;
use Application\Provider\Role\RoleProvider;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
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
                    'CONVERT' => ORM\Query\Functions\Convert::class,
                    'REPLACE' => ORM\Query\Functions\Replace::class,
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
                        'controller'    => 'Application\Controller\Index',
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
    'bjyauthorize'       => [
        'identity_provider' => Provider\Identity\IdentityProvider::class,

        'role_providers'     => [
            Provider\Role\RoleProvider::class => [
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
                        'assertion' => Assertion\InformationAssertion::class,
                    ],
                ],
            ],
        ],
    ],
    'service_manager'    => [
        'invokables' => [
            Service\AnneeService::class            => Service\AnneeService::class,
            Service\ContextService::class          => Service\ContextService::class,
            Service\LocalContextService::class     => Service\LocalContextService::class,
            Service\ParametresService::class       => Service\ParametresService::class,
            Service\UtilisateurService::class      => Service\UtilisateurService::class,
            Service\TypeInterventionService::class => Service\TypeInterventionService::class,
            Service\SourceService::class           => Service\SourceService::class,
            Service\AffectationService::class      => Service\AffectationService::class,
            Service\RoleService::class             => Service\RoleService::class,
            Service\PaysService::class             => Service\PaysService::class,
            Service\DepartementService::class      => Service\DepartementService::class,
            Service\FichierService::class          => Service\FichierService::class,
            Service\TauxHoraireHETDService::class  => Service\TauxHoraireHETDService::class,
            'UnicaenAuth\Service\Privilege'        => Service\PrivilegeService::class,
            Assertion\InformationAssertion::class  => Assertion\InformationAssertion::class,
        ],
        'factories'  => [
            'navigation'                  => Service\NavigationFactoryFactory::class,
            Provider\Role\RoleProvider::class     => Provider\Role\RoleProviderFactory::class,
            Provider\Identity\IdentityProvider::class => Provider\Identity\IdentityProviderFactory::class,
            'MouchardCompleterContext'    => MouchardCompleterContextFactory::class,
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
        'factories' => [
            'context' => Controller\Plugin\ContextFactory::class,
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
            '010_jquery'    => 'vendor/jquery-1.11.3.min.js',
            '020_jqueryui'  => 'vendor/bootstrap-3.3.5/js/bootstrap.min.js',
            '019_bootstrap' => 'vendor/jquery-ui-1.11.4/jquery-ui.min.js',

            //            '020_jqueryui' => 'vendor/jquery-ui-1.11.4/jquery-ui.min.js',
            //            '019_bootstrap'   => 'vendor/bootstrap-3.3.5/js/bootstrap.min.js',

        ],
        'inline_scripts' => [
            '010_bootstrap'   => null,
            '030_util'        => 'vendor/unicaen-app/js/util.js',
            '040_unicaen'     => 'vendor/unicaen-app/js/unicaen.js',
            '050_jquery_form' => 'vendor/jquery.form-3.51.js', // pour l'uploader Unicaen uniquement!!,

            '001_' => 'js/datepicker-fr.js',
            '113_' => 'js/paiement.js',

            '121_' => 'js/piece-jointe.js',
        ],
        'stylesheets'    => [
            '010_jquery-ui'           => 'vendor/jquery-ui-1.11.4/jquery-ui.min.css',
            '020_jquery-ui-structure' => 'vendor/jquery-ui-1.11.4/jquery-ui.structure.min.css',
            '030_jquery-ui-theme'     => 'vendor/jquery-ui-1.11.4/jquery-ui.theme.min.css',
            '040_bootstrap'           => 'vendor/bootstrap-3.3.5/css/bootstrap.min.css',
            '050_bootstrap-theme'     => 'vendor/bootstrap-3.3.5/css/bootstrap-theme.min.css',
            '060_unicaen'             => 'vendor/unicaen-app/css/unicaen.css',


            '111_' => 'css/cartridge.css',
            '112_' => 'vendor/font-awesome-4.5.0/css/font-awesome.min.css',
            '113_' => 'vendor/open-sans-gh-pages/open-sans.css',
            '114_' => 'css/budget.css',
            '115_' => 'css/paiement.css',
            '116_' => 'css/agrement.css',
            '118_' => 'css/acceuil.css',
            '120_' => 'css/callout.css',

            '121_' => 'css/piece-jointe.css',
        ],
    ],
];