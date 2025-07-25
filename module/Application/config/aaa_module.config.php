<?php

namespace Application;

use Application\Service\OseBddAdminFactory;
use Application\View\Helper\LocalConnectViewHelperFactory;
use Framework\Container\AutowireFactory;
use Unicaen\BddAdmin\Bdd;
use UnicaenAuthentification\View\Helper\LocalConnectViewHelper;

$config = [
    'doctrine' => [
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
                    ORM\Event\Listeners\HistoriqueListener::class,
                    ORM\Event\Listeners\ParametreEntityListener::class,
                    ORM\Event\Listeners\EntityManagerListener::class,
                ],
            ],
        ],
    ],
    'zfcuser'  => [
        'user_entity_class' => Entity\Db\Utilisateur::class,
    ],

    'router' => [
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
            'plan'             => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/plan',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'plan',
                    ],
                ],
                'may_terminate' => true,
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
                    'action'     => ['changement-annee', 'plan'],
                    'roles'      => ['guest'],
                ],
            ],
        ],
        'resource_providers' => [
            Provider\Resource\ResourceProvider::class => [],
        ],
    ],
    'service_manager'    => [
        'invokables' => [
            Service\AnneeService::class        => Service\AnneeService::class,
            Service\LocalContextService::class => Service\LocalContextService::class,
            Service\SourceService::class       => Service\SourceService::class,
            Service\AffectationService::class  => Service\AffectationService::class,
            Service\RoleService::class         => Service\RoleService::class,
            Service\FichierService::class      => Service\FichierService::class,
        ],
        'factories'  => [
            \Laminas\Navigation\Navigation::class                       => Navigation\NavigationFactory::class,
            Provider\Role\RoleProvider::class                           => Provider\Role\RoleProviderFactory::class,
            Provider\Resource\ResourceProvider::class                   => Provider\Resource\ResourceProviderFactory::class,
            Provider\Identity\IdentityProvider::class                   => Provider\Identity\IdentityProviderFactory::class,
            Service\ContextService::class                               => Service\Factory\ContextServiceFactory::class,
            \UnicaenPrivilege\Service\Privilege\PrivilegeService::class => Service\Factory\PrivilegeServiceFactory::class,
            Connecteur\LdapConnecteur::class                            => Connecteur\Factory\LdapConnecteurFactory::class,
            Cache\CacheService::class                                   => Cache\Factory\CacheServiceFactory::class,
            Service\UtilisateurService::class                           => Service\Factory\UtilisateurServiceFactory::class,
            HostLocalization\HostLocalizationOse::class                 => HostLocalization\HostLocalizationOseFactory::class,
            ORM\RouteEntitiesInjector::class                            => ORM\RouteEntitiesInjectorFactory::class,
            ORM\Event\Listeners\HistoriqueListener::class               => ORM\Event\Listeners\HistoriqueListenerFactory::class,
            Bdd::class                                                  => OseBddAdminFactory::class,
            \UnicaenMail\Service\Mail\MailService::class                => Service\MailServiceFactory::class,
        ],
        'aliases'    => [
            'HostLocalization'              => HostLocalization\HostLocalizationOse::class,
            Service\PrivilegeService::class => \UnicaenPrivilege\Service\Privilege\PrivilegeService::class,
        ],
    ],
    'view_helpers'       => [
        'aliases'    => [
            // on utilise les objets standards de Laminas, et plus ceux d'Unicaen
            'headLink'     => \Laminas\View\Helper\HeadLink::class,
            'headScript'   => \Laminas\View\Helper\HeadScript::class,
            'inlineScript' => \Laminas\View\Helper\InlineScript::class,
        ],
        'factories'  => [
            \UnicaenApp\View\Helper\AppLink::class => View\Helper\AppLinkFactory::class,
            LocalConnectViewHelper::class          => LocalConnectViewHelperFactory::class,
            'tab'                                  => View\Helper\TabViewHelperFactory::class,
            'appLayout'                            => View\Helper\LayoutViewHelperFactory::class,
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
];

if ($customCss = \AppAdmin::$config['etablissement']['css'] ?? null) {
    $config['public_files']['stylesheets']['999_'] = $customCss;
}

return $config;