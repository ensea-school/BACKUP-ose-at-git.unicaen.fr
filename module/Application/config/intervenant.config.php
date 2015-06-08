<?php

namespace Application;

use Application\Entity\Db\Privilege;

return [
    'router' => [
        'routes' => [
            'intervenant' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/intervenant',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Intervenant',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action[/:intervenant]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'rechercher' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/rechercher[/:intervenant]',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'rechercher',
                            ],
                        ],
                    ],
                    'fiche' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'voir',
                            ],
                        ],
                    ],
                    'voir-heures-comp' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/voir-heures-comp/:intervenant',
                            'constraints' => [
                                'action'            => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'intervenant'       => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'voir-heures-comp',
                            ],
                        ],
                    ],
                    'formule-totaux-hetd' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/formule-totaux-hetd/:intervenant/:typeVolumeHoraire/:etatVolumeHoraire',
                            'constraints' => [
                                'action'            => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'intervenant'       => '[0-9]*',
                                'typeVolumeHoraire' => '[0-9]*',
                                'etatVolumeHoraire' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'formule-totaux-hetd',
                            ],
                        ],
                    ],
                    'feuille-de-route' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/feuille-de-route',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'feuille-de-route',
                            ],
                        ],
                    ],
                    'modification-service-du' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:intervenant/modification-service-du',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'ModificationServiceDu',
                                'action'     => 'saisir',
                            ],
                        ],
                    ],
                    'saisir-dossier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/saisir-dossier',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Dossier',
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'services' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/services',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Service',
                                'action' => 'index',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ],
                        ],
                    ],
                    'referentiel' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/referentiel',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\ServiceReferentiel',
                                'action' => 'index',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ],
                        ],
                    ],
                    'services-realises' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/services-realises',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Service',
                                'action' => 'index',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ],
                        ],
                    ],
                    'referentiel-realise' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/referentiel',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\ServiceReferentiel',
                                'action' => 'index',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ],
                        ],
                    ],
                    'cloturer-saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/services/:type-volume-horaire-code/cloturer',
                            'constraints' => [
                                'id' => '[0-9]*',
                                'type-volume-horaire-code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Service',
                                'action' => 'cloturer-saisie',
                            ],
                        ],
                    ],
                    'demande-mise-en-paiement' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/demande-mise-en-paiement',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Paiement',
                                'action' => 'demandeMiseEnPaiement',
                            ],
                        ],
                    ],
                    'validation-dossier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:intervenant/validation/dossier',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Validation',
                                'action'     => 'dossier',
                            ],
                        ],
                    ],
                    'validation-service' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:intervenant/validation/service-prevu',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Validation',
                                'action' => 'service',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ],
                        ],
                    ],
                    'validation-service-realise' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:intervenant/validation/service-realise',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Validation',
                                'action' => 'service',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ],
                        ],
                    ],
                    'validation-referentiel' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:intervenant/validation/referentiel',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Validation',
                                'action' => 'referentiel',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ],
                        ],
                    ],
                    'validation-referentiel-realise' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:intervenant/validation/referentiel-realise',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Validation',
                                'action' => 'referentiel',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ],
                        ],
                    ],
                    'contrat' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:intervenant/contrat',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Contrat',
                                'action' => 'index',
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
                        'label'    => 'Intervenant',
                        'title'    => "Intervenant",
                        'route'    => 'intervenant',
                        'resource' => 'controller/Application\Controller\Intervenant:index',
                        'pages' => [
                            'rechercher' => [
                                'label'  => " Rechercher",
                                'title'  => "Rechercher un intervenant",
                                'route'  => 'intervenant/rechercher',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'icon'   => "glyphicon glyphicon-search",
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Intervenant:rechercher',
                            ],
                            'fiche' => [
                                'label'  => "Fiche individuelle",
                                'title'  => "Consultation de la fiche de l'intervenant {id}",
                                'route'  => 'intervenant/fiche',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Intervenant:voir',
                            ],
                            'voir-heures-comp' => [
                                'label'  => "Calcul HETD",
                                'title'  => "Calcul des heures équivalent TD {id}",
                                'route'  => 'intervenant/voir-heures-comp',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'action' => 'voir-heures-comp',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Intervenant:voir-heures-comp',
                            ],
                            'modification-service-du' => [
                                'label'  => "Modification de service dû",
                                'title'  => "Modification de service dû de l'intervenant {id}",
                                'route'  => 'intervenant/modification-service-du',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\ModificationServiceDu:saisir',
                            ],
                            'dossier' => [
                                'label'  => "Données personnelles",
                                'title'  => "Saisir les données personnelles d'un intervenant vacataire",
                                'route'  => 'intervenant/saisir-dossier',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Dossier:modifier',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ],
                            'service' => [
                                'label'  => "Enseignements prévisionnels",
                                'title'  => "Enseignements  prévisionnelsde l'intervenant",
                                'route'  => 'intervenant/services',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Service:index',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ],
                            'pieces-jointes-saisie' => [
                                // coquille vide qui réserve l'emplacement du menu
                            ],
                            'pieces-jointes-validation' => [
                                // coquille vide qui réserve l'emplacement du menu
                            ],
                            'validation-dossier' => [
                                'label'  => "Validation des données personnelles",
                                'title'  => "Validation des données personnelles de l'intervenant",
                                'route'  => 'intervenant/validation-dossier',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:dossier',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ],
                            'validation-service-prevu' => [
                                'label'  => "Validation des enseignements prévisionnels",
                                'title'  => "Validation des enseignements prévisionnels de l'intervenant",
                                'route'  => 'intervenant/validation-service',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:service',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ],
                            'validation-referentiel-prevu' => [
                                'label'  => "Validation du référentiel prévisionnel",
                                'title'  => "Validation du référentiel prévisionnel de l'intervenant",
                                'route'  => 'intervenant/validation-referentiel',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:referentiel',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ],
                            'agrement' => [
                                // coquille vide qui réserve l'emplacement du menu "Agréments"
                            ],
                            'contrat' => [
                                'label'  => "Contrat / avenant",
                                'title'  => "Contrat et avenants de l'intervenant",
                                'route'  => 'intervenant/contrat',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Contrat:index',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ],
                            'services-realises' => [
                                'label'  => "Enseignements réalisés",
                                'title'  => "Constatation des enseignements réalisés",
                                'route'  => 'intervenant/services-realises',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Service:index',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ],
                            'validation-service-realise' => [
                                'label'  => "Validation des enseignements réalisés",
                                'title'  => "Validation des enseignements réalisés de l'intervenant",
                                'route'  => 'intervenant/validation-service-realise',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:service',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ],
                            'validation-referentiel-realise' => [
                                'label'  => "Validation du référentiel réalisé",
                                'title'  => "Validation du référentiel réalisé de l'intervenant",
                                'route'  => 'intervenant/validation-referentiel-realise',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:referentiel',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ],
                            'demande-mise-en-paiement' => [
                                'label'  => "Demande de mise en paiement",
                                'title'  => "Demande de mise en paiement",
                                'route'  => 'intervenant/demande-mise-en-paiement',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Paiement:demandemiseenpaiement',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'Application\Guard\PrivilegeController' => [
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['index', 'rechercher'],
                    'privileges' => [
                        Privilege::INTERVENANT_RECHERCHE
                    ]
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['voir'],
                    'privileges' => [
                        Privilege::INTERVENANT_FICHE
                    ]
                ],
                [
                    'controller' => 'Application\Controller\ModificationServiceDu',
                    'action'     => ['saisir'],
                    'privileges' => [
                        Privilege::MODIF_SERVICE_DU_VISUALISATION
                    ],
                    'assertion'  => 'ModificationServiceDuAssertion',
                ],
            ],
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['formule-totaux-hetd'],
                    'roles'      => $R_ALL, /** @todo à sécuriser à l'aide d'une assertion pour éviter qu'un intervenant ne puisse voir les HETD des autres */
                    'assertion'  => 'IntervenantAssertion',
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['apercevoir',],
                    'roles'      => [R_ROLE],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['voir', 'index', 'feuille-de-route'],
                    'roles'      => [R_INTERVENANT, R_COMPOSANTE,  R_ADMINISTRATEUR],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['choisir', 'rechercher', 'search'],
                    'roles'      => [R_COMPOSANTE, R_ADMINISTRATEUR],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['voir-heures-comp'],
                    'roles'      => [R_DRH, R_ADMINISTRATEUR, R_COMPOSANTE, R_ETABLISSEMENT],
                ],
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['voir', 'modifier'],
                    'roles'      => [R_INTERVENANT_EXTERIEUR, R_COMPOSANTE, R_ADMINISTRATEUR],
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Intervenant' => [],
            ],
        ],
        'rule_providers' => [
            'Application\Provider\Rule\RuleProvider' => [
                'allow' => [
                    [
                        Privilege::MODIF_SERVICE_DU_EDITION,
                        'Intervenant',
                        [Privilege::MODIF_SERVICE_DU_EDITION],
                        'ModificationServiceDuAssertion',
                    ],
                ],
            ],
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                    [
                        $R_ALL,
                        'Intervenant',
                        ['total-heures-comp'],
                        'IntervenantAssertion',
                    ],

                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Intervenant'           => 'Application\Controller\IntervenantController',
            'Application\Controller\Dossier'               => 'Application\Controller\DossierController',
            'Application\Controller\ModificationServiceDu' => 'Application\Controller\ModificationServiceDuController',
        ],
        'aliases' => [
            'IntervenantController' => 'Application\Controller\Intervenant',
        ],
        'initializers' => [
            'Application\Service\Initializer\IntervenantServiceAwareInitializer',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationIntervenant'                => 'Application\\Service\\Intervenant',
            'ApplicationMotifModificationServiceDu' => 'Application\\Service\\MotifModificationServiceDu',
            'ApplicationCivilite'                   => 'Application\\Service\\Civilite',
            'ApplicationStatutIntervenant'          => 'Application\\Service\\StatutIntervenant',
            'ApplicationTypeIntervenant'            => 'Application\\Service\\TypeIntervenant',
            'ApplicationDossier'                    => 'Application\\Service\\Dossier',
            'IntervenantAssertion'                  => 'Application\\Assertion\\IntervenantAssertion',
            'ModificationServiceDuAssertion'        => 'Application\\Assertion\\ModificationServiceDuAssertion',
            'PeutSaisirDossierRule'                 => 'Application\Rule\Intervenant\PeutSaisirDossierRule',
            'PeutSaisirServiceRule'                 => 'Application\Rule\Intervenant\PeutSaisirServiceRule',
            'PeutSaisirReferentielRule'             => 'Application\Rule\Intervenant\PeutSaisirReferentielRule',
            'PossedeDossierRule'                    => 'Application\Rule\Intervenant\PossedeDossierRule',
            'ServiceValideRule'                     => 'Application\Rule\Intervenant\ServiceValideRule',
            'PeutValiderServiceRule'                => 'Application\Rule\Intervenant\PeutValiderServiceRule',
            'ReferentielValideRule'                 => 'Application\Rule\Intervenant\ReferentielValideRule',
            'NecessiteAgrementRule'                 => 'Application\Rule\Intervenant\NecessiteAgrementRule',
            'AgrementFourniRule'                    => 'Application\Rule\Intervenant\AgrementFourniRule',
            'EstAffecteRule'                        => 'Application\Rule\Intervenant\EstAffecteRule',
        ],
        'initializers' => [
            'Application\Service\Initializer\IntervenantServiceAwareInitializer',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'formuleTotauxHetd' => 'Application\View\Helper\Intervenant\TotauxHetdViewHelper',
            'Intervenant'       => 'Application\View\Helper\Intervenant\IntervenantViewHelper',
        ],
        'initializers' => [
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'IntervenantDossier' => 'Application\Form\Intervenant\Dossier',
            'IntervenantHeuresCompForm'                     => 'Application\Form\Intervenant\HeuresCompForm',
            'IntervenantModificationServiceDuForm'          => 'Application\Form\Intervenant\ModificationServiceDuForm',
            'IntervenantModificationServiceDuFieldset'      => 'Application\Form\Intervenant\ModificationServiceDuFieldset',
            'IntervenantMotifModificationServiceDuFieldset' => 'Application\Form\Intervenant\MotifModificationServiceDuFieldset',
        ],
    ],
];
