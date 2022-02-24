<?php

namespace Application;

use Application\Mouchard\MouchardCompleterContextFactory;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

$config = [
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
                    __DIR__ . '/../src/Entity/Db/Mapping',
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
                    ORM\Event\Listeners\HistoriqueListener::class,
                    ORM\Event\Listeners\ParametreEntityListener::class,
                ],
            ],
        ],
        'cache'         => [
            'apc'        => [
                'namespace' => 'OSE__' . __NAMESPACE__,
            ],
            'filesystem' => [
                'directory' => getcwd() . '/cache/Doctrine',
            ],
        ],
    ],
    'zfcuser'            => [
        'user_entity_class' => Entity\Db\Utilisateur::class,
    ],
    'translator'         => [
        'locale'                    => \AppConfig::get('global', 'locale'),
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => getcwd() . '/language',
                'pattern'  => '%s.mo',
            ],
        ],
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
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
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
            Service\AnneeService::class                   => Service\AnneeService::class,
            Service\LocalContextService::class            => Service\LocalContextService::class,
            Service\ParametresService::class              => Service\ParametresService::class,
            Service\TypeInterventionService::class        => Service\TypeInterventionService::class,
            Service\SourceService::class                  => Service\SourceService::class,
            Service\AffectationService::class             => Service\AffectationService::class,
            Service\RoleService::class                    => Service\RoleService::class,
            Service\PaysService::class                    => Service\PaysService::class,
            Service\DepartementService::class             => Service\DepartementService::class,
            Service\VoirieService::class                  => Service\VoirieService::class,
            Service\GradeService::class                   => Service\GradeService::class,
            Service\CorpsService::class                   => Service\CorpsService::class,
            Service\FichierService::class                 => Service\FichierService::class,
            Service\TauxHoraireHETDService::class         => Service\TauxHoraireHETDService::class,
            Assertion\InformationAssertion::class         => Assertion\InformationAssertion::class,
            ORM\Event\Listeners\HistoriqueListener::class => ORM\Event\Listeners\HistoriqueListener::class,
        ],
        'factories'  => [
            \Laminas\Navigation\Navigation::class       => Navigation\NavigationFactory::class,
            Provider\Role\RoleProvider::class           => Provider\Role\RoleProviderFactory::class,
            Provider\Identity\IdentityProvider::class   => Provider\Identity\IdentityProviderFactory::class,
            Service\ContextService::class               => Service\Factory\ContextServiceFactory::class,
            'MouchardCompleterContext'                  => MouchardCompleterContextFactory::class,
            'UnicaenAuth\Service\Privilege'             => Service\Factory\PrivilegeServiceFactory::class,
            Connecteur\LdapConnecteur::class            => Connecteur\Factory\LdapConnecteurFactory::class,
            Cache\CacheService::class                   => Cache\Factory\CacheServiceFactory::class,
            Service\UtilisateurService::class           => Service\Factory\UtilisateurServiceFactory::class,
            Assertion\InformationAssertion::class       => \UnicaenAuth\Assertion\AssertionFactory::class,
            HostLocalization\HostLocalizationOse::class => HostLocalization\HostLocalizationOseFactory::class,
            ORM\RouteEntitiesInjector::class            => ORM\RouteEntitiesInjectorFactory::class,
        ],
        'aliases'    => [
            'HostLocalization' => HostLocalization\HostLocalizationOse::class,
        ],
    ],
    'view_helpers'       => [
        'factories'  => [
            \UnicaenAuth\View\Helper\UserProfileSelectRadioItem::class => View\Helper\UserProfileSelectRadioItemFactory::class,
            \UnicaenApp\View\Helper\AppLink::class                     => View\Helper\AppLinkFactory::class,
            \UnicaenAuth\View\Helper\UserCurrent::class                => View\Helper\UserCurrentFactory::class,
            \UnicaenAuth\View\Helper\LocalConnectViewHelper::class     => View\Helper\LocalConnectViewHelperFactory::class,
        ],
        'invokables' => [
            'utilisateur'     => View\Helper\UtilisateurViewHelper::class,
            'tree'            => View\Helper\TreeViewHelper::class,
            'formSupprimer'   => View\Helper\FormSupprimerViewHelper::class,
            'formButtonGroup' => View\Helper\FormButtonGroupViewHelper::class,
            'cartridge'       => View\Helper\CartridgeViewHelper::class,
        ],
    ],
    'controllers'        => [
        'factories' => [
            'Application\Controller\Index' => Controller\Factory\IndexControllerFactory::class,
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
        'template_map'        => include __DIR__ . '/../template_map.php',
        'layout'              => 'layout/layout', // e.g., 'layout/layout'
    ],
    'public_files'       => [
        'head_scripts'   => [
            '010_jquery'   => 'vendor/jquery-1.11.3.min.js',
            '020_jqueryui' => 'vendor/jquery-ui-1.11.4/jquery-ui.min.js',
        ],
        'inline_scripts' => [
            '010_bootstrap'   => 'vendor/bootstrap-5.0.2/js/bootstrap.min.js',
            '030_util'        => 'js/util.js',
            '040_unicaen'     => 'js/unicaen.js',
            '050_jquery_form' => 'vendor/jquery.form-3.51.js', // pour l'uploader Unicaen uniquement!!,

            '001_' => 'js/datepicker-fr.js',
            '113_' => 'js/paiement.js',

            '121_' => 'js/piece-jointe.js',
        ],
        'stylesheets'    => [
            '010_jquery-ui'           => 'vendor/jquery-ui-1.11.4/jquery-ui.min.css',
            '020_jquery-ui-structure' => 'vendor/jquery-ui-1.11.4/jquery-ui.structure.min.css',
            '030_jquery-ui-theme'     => 'vendor/jquery-ui-1.11.4/jquery-ui.theme.min.css',
            '040_bootstrap'           => 'vendor/bootstrap-5.0.2/css/bootstrap.min.css',
            //'050_bootstrap-theme'     => 'vendor/bootstrap-5.0.2/css/bootstrap-theme.min.css',
            '060_unicaen'             => null,

            //            '111_' => 'css/cartridge.css',
            //            '112_' => 'vendor/font-awesome-4.5.0/css/font-awesome.min.css',
            //            '113_' => 'vendor/open-sans-gh-pages/open-sans.css',
            //            '114_' => 'css/budget.css',
            //            '115_' => 'css/paiement.css',
            //            '116_' => 'css/agrement.css',
            //            '118_' => 'css/acceuil.css',
            //            '120_' => 'css/callout.css',
            //            '121_' => 'css/piece-jointe.css',
        ],
    ],
];

if ($customCss = \AppConfig::get('etablissement', 'css')) {
    $config['public_files']['stylesheets']['999_'] = $customCss;
}

return $config;