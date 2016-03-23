<?php

namespace Application;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'intervenant' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/intervenant',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Intervenant',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'rechercher'                     => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/rechercher',
                            'defaults' => [
                                'action' => 'rechercher',
                            ],
                        ],
                    ],
                    'recherche'                      => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/recherche',
                            'defaults' => [
                                'action' => 'recherche',
                            ],
                        ],
                    ],
                    'voir'                           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'voir',
                            ],
                        ],
                    ],
                    'fiche'                          => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/fiche',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'fiche',
                            ],
                        ],
                    ],
                    'saisir'                         => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/saisir',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisir',
                            ],
                        ],
                    ],
                    'voir-heures-comp'               => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/voir-heures-comp/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'voir-heures-comp',
                            ],
                        ],
                    ],
                    'formule-totaux-hetd'            => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/formule-totaux-hetd/:intervenant/:typeVolumeHoraire/:etatVolumeHoraire',
                            'constraints' => [
                                'intervenant'       => '[0-9]*',
                                'typeVolumeHoraire' => '[0-9]*',
                                'etatVolumeHoraire' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'formule-totaux-hetd',
                            ],
                        ],
                    ],
                    'feuille-de-route'               => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/feuille-de-route',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'feuille-de-route',
                            ],
                        ],
                    ],
                    'modification-service-du'        => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/modification-service-du',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => 'ModificationServiceDu',
                                'action'     => 'saisir',
                            ],
                        ],
                    ],
                    'services'                       => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/services',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller'               => 'Application\Controller\Service',
                                'action'                   => 'index',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ],
                        ],
                    ],
                    'referentiel'                    => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/referentiel',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller'               => 'Application\Controller\ServiceReferentiel',
                                'action'                   => 'index',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ],
                        ],
                    ],
                    'services-realises'              => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/services-realises',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller'               => 'Application\Controller\Service',
                                'action'                   => 'index',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ],
                        ],
                    ],
                    'referentiel-realise'            => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/referentiel',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller'               => 'Application\Controller\ServiceReferentiel',
                                'action'                   => 'index',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ],
                        ],
                    ],
                    'cloturer-saisie'                => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/services/:type-volume-horaire-code/cloturer',
                            'constraints' => [
                                'id'                       => '[0-9]*',
                                'type-volume-horaire-code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => 'Application\Controller\Service',
                                'action'     => 'cloturer-saisie',
                            ],
                        ],
                    ],
                    'demande-mise-en-paiement'       => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/demande-mise-en-paiement',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => 'Application\Controller\Paiement',
                                'action'     => 'demandeMiseEnPaiement',
                            ],
                        ],
                    ],
                    'validation-service'             => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/validation/service-prevu',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller'               => 'Validation',
                                'action'                   => 'service',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ],
                        ],
                    ],
                    'validation-service-realise'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/validation/service-realise',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller'               => 'Validation',
                                'action'                   => 'service',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ],
                        ],
                    ],
                    'validation-referentiel'         => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/validation/referentiel',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller'               => 'Validation',
                                'action'                   => 'referentiel',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ],
                        ],
                    ],
                    'validation-referentiel-realise' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/validation/referentiel-realise',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller'               => 'Validation',
                                'action'                   => 'referentiel',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ],
                        ],
                    ],
                    'contrat'                        => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/contrat',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => 'Contrat',
                                'action'     => 'index',
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
                    'intervenant' => [
                        'label'    => 'Intervenant',
                        'title'    => "Intervenant",
                        'route'    => 'intervenant',
                        'resource' => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'index'),
                        'pages'    => [
                            'rechercher'                     => [
                                'label'        => " Rechercher",
                                'title'        => "Rechercher un intervenant",
                                'route'        => 'intervenant/rechercher',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'icon'         => "glyphicon glyphicon-search",
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'rechercher'),
                            ],
                            'voir'                           => [
                                'label'        => "Fiche individuelle",
                                'title'        => "Consultation de la fiche de l'intervenant {id}",
                                'route'        => 'intervenant/voir',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'voir'),
                            ],
                            'voir-heures-comp'               => [
                                'label'        => "Calcul HETD",
                                'title'        => "Calcul des heures équivalent TD {id}",
                                'route'        => 'intervenant/voir-heures-comp',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'action'       => 'voir-heures-comp',
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'voir-heures-comp'),
                            ],
                            'modification-service-du'        => [
                                'label'        => "Modification de service dû",
                                'title'        => "Modification de service dû de l'intervenant {id}",
                                'route'        => 'intervenant/modification-service-du',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\ModificationServiceDu', 'saisir'),
                            ],
                            'dossier'                        => [
                                // coquille vide qui réserve l'emplacement du menu
                            ],
                            'service'                        => [
                                'label'        => "Enseignements prévisionnels",
                                'title'        => "Enseignements  prévisionnelsde l'intervenant",
                                'route'        => 'intervenant/services',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'workflow-etape-code' => WfEtape::CODE_SERVICE_SAISIE,
                                'withtarget'   => true,
                                'visible'      => 'assertionService',
                            ],
                            'pieces-jointes-saisie'          => [
                                // coquille vide qui réserve l'emplacement du menu
                            ],
                            'validation-dossier'             => [
                                // coquille vide qui réserve l'emplacement du menu
                            ],
                            'validation-service-prevu'       => [
                                'label'        => "Validation des enseignements prévisionnels",
                                'title'        => "Validation des enseignements prévisionnels de l'intervenant",
                                'route'        => 'intervenant/validation-service',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Validation', 'service'),
                                //'visible'      => 'IntervenantNavigationPageVisibility',
                            ],
                            'validation-referentiel-prevu'   => [
                                'label'        => "Validation du référentiel prévisionnel",
                                'title'        => "Validation du référentiel prévisionnel de l'intervenant",
                                'route'        => 'intervenant/validation-referentiel',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Validation', 'referentiel'),
                                //'visible'      => 'IntervenantNavigationPageVisibility',
                            ],
                            'agrement-conseil-restreint'     => [
                                // coquille vide qui réserve l'emplacement du menu
                            ],
                            'agrement-conseil-academique'    => [
                                // coquille vide qui réserve l'emplacement du menu
                            ],
                            'contrat'                        => [
                                'label'        => "Contrat / avenant",
                                'title'        => "Contrat et avenants de l'intervenant",
                                'route'        => 'intervenant/contrat',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Contrat', 'index'),
                            ],
                            'services-realises'              => [
                                'label'        => "Enseignements réalisés",
                                'title'        => "Constatation des enseignements réalisés",
                                'route'        => 'intervenant/services-realises',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'workflow-etape-code' => WfEtape::CODE_SERVICE_SAISIE_REALISE,
                                'withtarget'   => true,
                                'visible'      => 'assertionService',
                            ],
                            'validation-service-realise'     => [
                                'label'        => "Validation des enseignements réalisés",
                                'title'        => "Validation des enseignements réalisés de l'intervenant",
                                'route'        => 'intervenant/validation-service-realise',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Validation', 'service'),
                                //'visible'      => 'IntervenantNavigationPageVisibility',
                            ],
                            'validation-referentiel-realise' => [
                                'label'        => "Validation du référentiel réalisé",
                                'title'        => "Validation du référentiel réalisé de l'intervenant",
                                'route'        => 'intervenant/validation-referentiel-realise',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Validation', 'referentiel'),
                                //'visible'      => 'IntervenantNavigationPageVisibility',
                            ],
                            'demande-mise-en-paiement'       => [
                                'label'        => "Demande de mise en paiement",
                                'title'        => "Demande de mise en paiement",
                                'route'        => 'intervenant/demande-mise-en-paiement',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Paiement', 'demandeMiseEnPaiement'),
                                //'visible'      => 'IntervenantNavigationPageVisibility',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            PrivilegeController::class      => [
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['rechercher', 'recherche'],
                    'privileges' => [
                        Privileges::INTERVENANT_RECHERCHE,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['index', 'voir', 'fiche'],
                    'privileges' => [
                        Privileges::INTERVENANT_FICHE,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['saisir'],
                    'privileges' => [
                        Privileges::INTERVENANT_EDITION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\ModificationServiceDu',
                    'action'     => ['saisir'],
                    'privileges' => [
                        Privileges::MODIF_SERVICE_DU_VISUALISATION,
                    ],
                    'assertion'  => 'ModificationServiceDuAssertion',
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['voir-heures-comp'],
                    'privileges' => [
                        Privileges::INTERVENANT_CALCUL_HETD,
                    ],
                ],
                [ /// @todo à protéger d'avantage...
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['formule-totaux-hetd'],
                    'roles'      => ['user'],
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
                        'assertion'  => 'ModificationServiceDuAssertion',
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Intervenant'           => Controller\IntervenantController::class,
            'Application\Controller\ModificationServiceDu' => Controller\ModificationServiceDuController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationIntervenant'                => Service\Intervenant::class,
            'ApplicationMotifModificationServiceDu' => Service\MotifModificationServiceDu::class,
            'ApplicationCivilite'                   => Service\Civilite::class,
            'ApplicationStatutIntervenant'          => Service\StatutIntervenant::class,
            'ApplicationTypeIntervenant'            => Service\TypeIntervenant::class,
            'IntervenantAssertion'                  => Assertion\IntervenantAssertion::class,
            'ModificationServiceDuAssertion'        => Assertion\ModificationServiceDuAssertion::class,
            'PeutSaisirServiceRule'                 => Rule\Intervenant\PeutSaisirServiceRule::class,
            'PeutSaisirReferentielRule'             => Rule\Intervenant\PeutSaisirReferentielRule::class,
            'ServiceValideRule'                     => Rule\Intervenant\ServiceValideRule::class,
            'PeutValiderServiceRule'                => Rule\Intervenant\PeutValiderServiceRule::class,
            'ReferentielValideRule'                 => Rule\Intervenant\ReferentielValideRule::class,
            'EstAffecteRule'                        => Rule\Intervenant\EstAffecteRule::class,
        ],
    ],
    'view_helpers'    => [
        'invokables'   => [
            'formuleTotauxHetd' => View\Helper\Intervenant\TotauxHetdViewHelper::class,
            'Intervenant'       => View\Helper\Intervenant\IntervenantViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'IntervenantEditionForm'                        => Form\Intervenant\EditionForm::class,
            'IntervenantHeuresCompForm'                     => Form\Intervenant\HeuresCompForm::class,
            'IntervenantModificationServiceDuForm'          => Form\Intervenant\ModificationServiceDuForm::class,
            'IntervenantModificationServiceDuFieldset'      => Form\Intervenant\ModificationServiceDuFieldset::class,
            'IntervenantMotifModificationServiceDuFieldset' => Form\Intervenant\MotifModificationServiceDuFieldset::class,
        ],
    ],
];
