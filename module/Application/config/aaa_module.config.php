<?php

namespace Application;

use Application\Mouchard\MouchardCompleterContextFactory;
use Application\View\Helper\UserProfileSelectRadioItem;
use Application\View\Helper\UserProfileSelectRadioItemFactory;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

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
                    ORM\Event\Listeners\EntityManagerListener::class,
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
    'console'            => [
        'router' => [
            'routes' => [
                'generate-proxies' => [
                    'options' => [
                        'route'    => 'generate-proxies',
                        'defaults' => [
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'generate-proxies',
                        ],
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
            Provider\Resource\ResourceProvider::class     => [],
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
            Service\AdresseNumeroComplService::class               => Service\AdresseNumeroComplService::class,
            Service\AnneeService::class                            => Service\AnneeService::class,
            Service\LocalContextService::class                     => Service\LocalContextService::class,
            Service\ParametresService::class                       => Service\ParametresService::class,
            \OffreFormation\Service\TypeInterventionService::class => \OffreFormation\Service\TypeInterventionService::class,
            Service\SourceService::class                           => Service\SourceService::class,
            Service\AffectationService::class                      => Service\AffectationService::class,
            Service\RoleService::class                             => Service\RoleService::class,
            Service\PaysService::class                             => Service\PaysService::class,
            Service\DepartementService::class                      => Service\DepartementService::class,
            Service\VoirieService::class                           => Service\VoirieService::class,
            Service\GradeService::class                            => Service\GradeService::class,
            Service\CorpsService::class                            => Service\CorpsService::class,
            Service\FichierService::class                          => Service\FichierService::class,
            Service\TauxHoraireHETDService::class                  => Service\TauxHoraireHETDService::class,
            Service\TypeValidationService::class                   => Service\TypeValidationService::class,
            Service\ValidationService::class                       => Service\ValidationService::class,
        ],
        'factories'  => [
            \Laminas\Navigation\Navigation::class                       => Navigation\NavigationFactory::class,
            Provider\Role\RoleProvider::class                           => Provider\Role\RoleProviderFactory::class,
            Provider\Resource\ResourceProvider::class                   => Provider\Resource\ResourceProviderFactory::class,
            Provider\Identity\IdentityProvider::class                   => Provider\Identity\IdentityProviderFactory::class,
            Service\ContextService::class                               => Service\Factory\ContextServiceFactory::class,
            'MouchardCompleterContext'                                  => MouchardCompleterContextFactory::class,
            Service\PrivilegeService::class                             => Service\Factory\PrivilegeServiceFactory::class,
            \UnicaenPrivilege\Service\Privilege\PrivilegeService::class => Service\Factory\PrivilegeServiceFactory::class,
            Connecteur\LdapConnecteur::class                            => Connecteur\Factory\LdapConnecteurFactory::class,
            Cache\CacheService::class                                   => Cache\Factory\CacheServiceFactory::class,
            Service\UtilisateurService::class                           => Service\Factory\UtilisateurServiceFactory::class,
            Assertion\InformationAssertion::class                       => \UnicaenPrivilege\Assertion\AssertionFactory::class,
            HostLocalization\HostLocalizationOse::class                 => HostLocalization\HostLocalizationOseFactory::class,
            ORM\RouteEntitiesInjector::class                            => ORM\RouteEntitiesInjectorFactory::class,
            ORM\Event\Listeners\HistoriqueListener::class               => ORM\Event\Listeners\HistoriqueListenerFactory::class,
        ],
        'aliases'    => [
            'HostLocalization' => HostLocalization\HostLocalizationOse::class,
        ],
    ],
    'view_helpers'       => [
        'aliases'    => [
            // on utilise les objets standards de Laminas, et plus ceux d'Unicaen
            'headLink'     => \Laminas\View\Helper\HeadLink::class,
            'headScript'   => \Laminas\View\Helper\HeadScript::class,
            'inlineScript' => \Laminas\View\Helper\InlineScript::class,
            'userProfileSelectRadioItem' => UserProfileSelectRadioItem::class,
        ],
        'factories'  => [
            UserProfileSelectRadioItem::class => UserProfileSelectRadioItemFactory::class,
            //            \UnicaenUtilisateur\View\Helper\UserProfileSelectRadioItem::class  => View\Helper\UserProfileSelectRadioItemFactory::class,
//            \UnicaenApp\View\Helper\AppLink::class                             => View\Helper\AppLinkFactory::class,
//            \UnicaenUtilisateur\View\Helper\UserCurrent::class                 => View\Helper\UserCurrentFactory::class,
            'tab'                                                              => View\Helper\TabViewHelperFactory::class,
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
    'vite'               => [
        'host'        => 'http://localhost:5133',
        'vue-url'     => '/vendor/vue.js',
        'hot-loading' => \AppConfig::inDev() ? \AppConfig::get('dev', 'hot-loading') : false,
    ],
];

if ($customCss = \AppConfig::get('etablissement', 'css')) {
    $config['public_files']['stylesheets']['999_'] = $customCss;
}

return $config;