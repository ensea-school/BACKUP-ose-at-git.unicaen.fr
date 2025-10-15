<?php

namespace Application;

use Application\Service\OseBddAdminFactory;
use Application\View\Helper\LocalConnectViewHelperFactory;
use Unicaen\Framework\Application\Application;
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
        'user_entity_class' => \Utilisateur\Entity\Db\Utilisateur::class,
    ],

    'router' => [
        'routes' => [
            'home'             => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'privileges' => ['guest','user'],
            ],
            'plan'             => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/plan',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'plan',
                    ],
                ],
                'may_terminate' => true,
                'privileges' => ['guest','user'],
            ],
            'application'      => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/application',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'privileges' => ['guest','user'],
            ],
            'changement-annee' => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/changement-annee/:annee',
                    'constraints' => [
                        'annee' => '[0-9]*',
                    ],
                    'defaults'    => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'changement-annee',
                    ],
                ],
                'privileges' => ['guest','user'],
            ],
        ],
    ],

    'service_manager'    => [
        'factories'  => [
            HostLocalization\HostLocalizationOse::class                 => HostLocalization\HostLocalizationOseFactory::class,
            ORM\Event\Listeners\HistoriqueListener::class               => ORM\Event\Listeners\HistoriqueListenerFactory::class,
            Bdd::class                                                  => OseBddAdminFactory::class,
            \UnicaenMail\Service\Mail\MailService::class                => Service\MailServiceFactory::class,
        ],
        'aliases'    => [
            'HostLocalization'                           => HostLocalization\HostLocalizationOse::class,
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
            LocalConnectViewHelper::class          => LocalConnectViewHelperFactory::class,
            'tab'                                  => View\Helper\TabViewHelperFactory::class,
            'appLayout'                            => View\Helper\LayoutViewHelperFactory::class,
        ],
        'invokables' => [
            'tree'            => View\Helper\TreeViewHelper::class,
            'formSupprimer'   => View\Helper\FormSupprimerViewHelper::class,
            'formButtonGroup' => View\Helper\FormButtonGroupViewHelper::class,
            'cartridge'       => View\Helper\CartridgeViewHelper::class,
        ],
    ],
    'controllers'        => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
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

if ($customCss = Application::getInstance()->config()['etablissement']['css'] ?? null) {
    $config['public_files']['stylesheets']['999_'] = $customCss;
}

return $config;