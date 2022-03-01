<?php

namespace Application;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'intervenant'                                => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/intervenant',
                    'defaults' => [
                        'controller' => 'Application\Controller\Intervenant',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'rechercher'              => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/rechercher',
                            'defaults' => [
                                'action' => 'rechercher',
                            ],
                        ],
                    ],
                    'recherche'               => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/recherche',
                            'defaults' => [
                                'action' => 'recherche',
                            ],
                        ],
                    ],
                    'voir'                    => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/voir',
                            'defaults' => [
                                'action' => 'voir',
                            ],
                        ],
                    ],
                    'fiche'                   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/fiche',
                            'defaults' => [
                                'action' => 'fiche',
                            ],
                        ],
                    ],
                    'creer'                   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'action'        => 'saisir',
                                'action-detail' => 'creer',
                            ],
                        ],
                    ],
                    'saisir'                  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/saisir',
                            'defaults' => [
                                'action'        => 'saisir',
                                'action-detail' => 'saisir',
                            ],
                        ],
                    ],
                    'dupliquer'               => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/dupliquer',
                            'defaults' => [
                                'action'        => 'saisir',
                                'action-detail' => 'dupliquer',
                            ],
                        ],
                    ],
                    'synchronisation'         => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/synchronisation',
                            'defaults' => [
                                'action' => 'synchronisation',
                            ],
                        ],
                    ],
                    'synchroniser'            => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/synchroniser',
                            'defaults' => [
                                'action' => 'synchroniser',
                            ],
                        ],
                    ],
                    'supprimer'               => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/supprimer',
                            'defaults' => [
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'historiser'              => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/historiser',
                            'defaults' => [
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer'               => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/restaurer',
                            'defaults' => [
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'definir-par-defaut'      => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/definir-par-defaut',
                            'defaults' => [
                                'action' => 'definir-par-defaut',
                            ],
                        ],
                    ],
                    'voir-heures-comp'        => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/voir-heures-comp/:intervenant',
                            'defaults' => [
                                'action' => 'voir-heures-comp',
                            ],
                        ],
                    ],
                    'formule-totaux-hetd'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/formule-totaux-hetd/:intervenant/:typeVolumeHoraire/:etatVolumeHoraire',
                            'constraints' => [
                                'typeVolumeHoraire' => '[0-9]*',
                                'etatVolumeHoraire' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'formule-totaux-hetd',
                            ],
                        ],
                    ],
                    'feuille-de-route'        => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/feuille-de-route',
                            'defaults' => [
                                'action' => 'feuille-de-route',
                            ],
                        ],
                    ],
                    'modification-service-du' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/modification-service-du',
                            'defaults' => [
                                'controller' => 'Application\Controller\ModificationServiceDu',
                                'action'     => 'saisir',
                            ],
                        ],
                    ],
                    'services'                => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/services',
                            'defaults' => [
                                'controller'               => 'Application\Controller\Intervenant',
                                'action'                   => 'services',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ],
                        ],
                    ],
                    'referentiel'             => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/referentiel',
                            'defaults' => [
                                'controller'               => 'Application\Controller\ServiceReferentiel',
                                'action'                   => 'index',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ],
                        ],
                    ],
                    'services-realises'       => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/services-realises',
                            'defaults' => [
                                'controller'               => 'Application\Controller\Intervenant',
                                'action'                   => 'services',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ],
                        ],
                    ],
                    'referentiel-realise'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/referentiel',
                            'defaults' => [
                                'controller'               => 'Application\Controller\ServiceReferentiel',
                                'action'                   => 'index',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ],
                        ],
                    ],
                    'cloturer'                => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/cloturer',
                            'defaults' => [
                                'action' => 'cloturer',
                            ],
                        ],
                    ],
                    'mise-en-paiement'        => [
                        'type'          => 'Segment',
                        'may_terminate' => false,
                        'options'       => [
                            'route'    => '/:intervenant/mise-en-paiement',
                            'defaults' => [
                                'controller' => 'Application\Controller\Paiement',
                            ],
                        ],
                        'child_routes'  => [
                            'visualisation' => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/visualisation',
                                    'defaults' => [
                                        'action' => 'visualisationMiseEnPaiement',
                                    ],
                                ],
                            ],
                            'demande'       => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/demande',
                                    'defaults' => [
                                        'action' => 'demandeMiseEnPaiement',
                                    ],
                                ],
                            ],
                            'edition'       => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/edition',
                                    'defaults' => [
                                        'action' => 'editionMiseEnPaiement',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'contrat'                 => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/contrat',
                            'defaults' => [
                                'controller' => 'Application\Controller\Contrat',
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
            'modification-service-du'                    => [
                'type'         => 'Literal',
                'options'      => [
                    'route'    => '/modification-service-du',
                    'defaults' => [
                        'controller' => 'Application\Controller\ModificationServiceDu',
                    ],
                ],
                'child_routes' => [
                    'export-csv' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/export-csv',
                            'defaults' => [
                                'action' => 'export-csv',
                            ],
                        ],
                    ],
                ],
            ],
            'validation-volume-horaire-type-intervenant' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/validation-vh-ti',
                    'defaults' => [
                        'controller' => 'Application\Controller\Intervenant',
                        'action'     => 'validation-volume-horaire-type-intervenant',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'delete' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/delete/:regleStructureValidation',
                            'constraints' => [
                                'regleStructureValidation' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'validation-volume-horaire-type-intervenant-delete',
                            ],
                        ],
                    ],
                    'saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie/[:regleStructureValidation]',
                            'constraints' => [
                                'regleStructureValidation' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'validation-volume-horaire-type-intervenant-saisie',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion'     => [
                        'pages' => [
                            'modification-service-du-export-csv' => [
                                'label'        => "Modifications de service dû (CSV)",
                                'icon'         => 'fas fa-fill',
                                'title'        => "Modifications de service dû (CSV)",
                                'route'        => 'modification-service-du/export-csv',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\ModificationServiceDu', 'export-csv'),
                                'order'        => 45,
                                'border-color' => '#45DAE0',
                            ],
                        ],
                    ],
                    'intervenant' => [
                        'label'    => 'Intervenant',
                        'title'    => "Intervenant",
                        'route'    => 'intervenant',
                        'resource' => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'index'),
                        'order'    => 1,
                        'pages'    => [
                            'rechercher'              => [
                                'label'        => " Rechercher",
                                'title'        => "Rechercher un intervenant",
                                'route'        => 'intervenant/rechercher',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'icon'         => "fas fa-magnifying-glass",
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'rechercher'),
                                'order'        => 1,
                            ],
                            'voir'                    => [
                                'label'        => "Fiche individuelle",
                                'title'        => "Consultation de la fiche de l'intervenant {id}",
                                'route'        => 'intervenant/voir',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'voir'),
                                'order'        => 2,
                            ],
                            'voir-heures-comp'        => [
                                'label'        => "Calcul HETD",
                                'title'        => "Calcul des heures équivalent TD {id}",
                                'route'        => 'intervenant/voir-heures-comp',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'action'       => 'voir-heures-comp',
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'voir-heures-comp'),
                                'order'        => 3,
                            ],
                            'modification-service-du' => [
                                'label'        => "Modification de service dû",
                                'title'        => "Modification de service dû de l'intervenant {id}",
                                'route'        => 'intervenant/modification-service-du',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\ModificationServiceDu', 'saisir'),
                                'order'        => 4,
                            ],
                            'service'                 => [
                                'label'               => "Enseignements prévisionnels",
                                'title'               => "Enseignements prévisionnels de l'intervenant",
                                'route'               => 'intervenant/services',
                                'paramsInject'        => [
                                    'intervenant',
                                ],
                                'workflow-etape-code' => WfEtape::CODE_SERVICE_SAISIE,
                                'withtarget'          => true,
                                'resource'            => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'services'),
                                'visible'             => Assertion\ServiceAssertion::class,
                                'order'               => 6,
                            ],
                            'contrat'                 => [
                                'label'        => "Contrat / avenant",
                                'title'        => "Contrat et avenants de l'intervenant",
                                'route'        => 'intervenant/contrat',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Contrat', 'index'),
                                'order'        => 12,
                            ],
                            'services-realises'       => [
                                'label'               => "Enseignements réalisés",
                                'title'               => "Constatation des enseignements réalisés",
                                'route'               => 'intervenant/services-realises',
                                'paramsInject'        => [
                                    'intervenant',
                                ],
                                'workflow-etape-code' => WfEtape::CODE_SERVICE_SAISIE_REALISE,
                                'withtarget'          => true,
                                'resource'            => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'services'),
                                'visible'             => Assertion\ServiceAssertion::class,
                                'order'               => 13,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['rechercher', 'recherche'],
                    'privileges' => [
                        Privileges::INTERVENANT_RECHERCHE,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['index', 'voir', 'fiche', 'menu'],
                    'privileges' => [
                        Privileges::INTERVENANT_FICHE,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['services'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_VISUALISATION,
                        Privileges::REFERENTIEL_VISUALISATION,
                    ],
                    'assertion'  => Assertion\ServiceAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['saisir', 'definir-par-defaut', 'synchronisation', 'synchroniser'],
                    'privileges' => [
                        Privileges::INTERVENANT_EDITION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['restaurer'],
                    'privileges' => [
                        Privileges::INTERVENANT_AJOUT_STATUT,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['supprimer', 'historiser'],
                    'privileges' => [
                        Privileges::INTERVENANT_SUPPRESSION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\ModificationServiceDu',
                    'action'     => ['saisir'],
                    'privileges' => [
                        Privileges::MODIF_SERVICE_DU_VISUALISATION,
                    ],
                    'assertion'  => Assertion\ModificationServiceDuAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\ModificationServiceDu',
                    'action'     => ['export-csv'],
                    'privileges' => [
                        Privileges::MODIF_SERVICE_DU_EXPORT_CSV,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['voir-heures-comp'],
                    'privileges' => [
                        Privileges::INTERVENANT_CALCUL_HETD,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['cloturer'],
                    'privileges' => [
                        Privileges::CLOTURE_CLOTURE,
                        Privileges::CLOTURE_REOUVERTURE,
                    ],
                    'assertion'  => Assertion\IntervenantAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['formule-totaux-hetd'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_VISUALISATION,
                        Privileges::REFERENTIEL_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['validation-volume-horaire-type-intervenant',
                                     'validation-volume-horaire-type-intervenant-saisie',
                                     'validation-volume-horaire-type-intervenant-delete',
                    ],
                    'privileges' => [
                        Privileges::MODIF_SERVICE_DU_EDITION,
                    ],
                ],
            ],
        ],
        'resource_providers' => [
            \BjyAuthorize\Provider\Resource\Config::class => [
                'Intervenant' => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => Privileges::MODIF_SERVICE_DU_EDITION,
                        'resources'  => 'Intervenant',
                        'assertion'  => Assertion\ModificationServiceDuAssertion::class,
                    ],
                    [
                        'privileges' => [
                            Privileges::CLOTURE_CLOTURE,
                            Privileges::CLOTURE_REOUVERTURE,
                            Privileges::INTERVENANT_EDITION,
                            Privileges::INTERVENANT_EDITION_AVANCEE,
                        ],
                        'resources'  => ['Validation', 'Intervenant'],
                        'assertion'  => Assertion\IntervenantAssertion::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories'  => [
            'Application\Controller\Intervenant' => Controller\Factory\IntervenantControllerFactory::class,
        ],
        'invokables' => [
            'Application\Controller\ModificationServiceDu' => Controller\ModificationServiceDuController::class,
        ],
    ],
    'service_manager' => [
        'factories'  => [
            Service\IntervenantService::class               => Service\Factory\IntervenantServiceFactory::class,
            Processus\IntervenantProcessus::class           => Processus\Factory\IntervenantProcessusFactory::class,
            Assertion\ModificationServiceDuAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
            Assertion\IntervenantAssertion::class           => \UnicaenAuth\Assertion\AssertionFactory::class,
        ],
        'invokables' => [
            Service\MotifModificationServiceDuService::class => Service\MotifModificationServiceDuService::class,
            Service\CiviliteService::class                   => Service\CiviliteService::class,
            Service\GradeService::class                      => Service\GradeService::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'formuleTotauxHetd' => View\Helper\Intervenant\TotauxHetdViewHelper::class,
            'intervenant'       => View\Helper\Intervenant\IntervenantViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'factories'  => [
            Form\Intervenant\EditionForm::class => Form\Intervenant\Factory\EditionFormFactory::class,
        ],
        'invokables' => [

            Form\Intervenant\HeuresCompForm::class                     => Form\Intervenant\HeuresCompForm::class,
            Form\Intervenant\ModificationServiceDuForm::class          => Form\Intervenant\ModificationServiceDuForm::class,
            Form\Intervenant\ModificationServiceDuFieldset::class      => Form\Intervenant\ModificationServiceDuFieldset::class,
            Form\Intervenant\MotifModificationServiceDuFieldset::class => Form\Intervenant\MotifModificationServiceDuFieldset::class,
            Form\Intervenant\RegleStructureValidationForm::class       => Form\Intervenant\RegleStructureValidationForm::class,
        ],
    ],
];
