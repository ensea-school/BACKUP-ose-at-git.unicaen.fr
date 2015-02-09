<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'intervenant' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/intervenant',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Intervenant',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action[/:intervenant]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'rechercher' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/rechercher[/:intervenant]',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'rechercher',
                            ),
                        ),
                    ),
                    'fiche' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voir',
                            ),
                        ),
                    ),
                    'voir-heures-comp' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/voir-heures-comp/:intervenant',
                            'constraints' => array(
                                'action'            => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'intervenant'       => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voir-heures-comp',
                            ),
                        ),
                    ),
                    'formule-totaux-hetd' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/formule-totaux-hetd/:intervenant/:typeVolumeHoraire/:etatVolumeHoraire',
                            'constraints' => array(
                                'action'            => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'intervenant'       => '[0-9]*',
                                'typeVolumeHoraire' => '[0-9]*',
                                'etatVolumeHoraire' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'formule-totaux-hetd',
                            ),
                        ),
                    ),
                    'feuille-de-route' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant/feuille-de-route',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'feuille-de-route',
                            ),
                        ),
                    ),
                    'modification-service-du' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/modification-service-du',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'ModificationServiceDu',
                                'action'     => 'saisir',
                            ),
                        ),
                    ),
                    'saisir-dossier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant/saisir-dossier',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Dossier',
                                'action' => 'modifier',
                            ),
                        ),
                    ),
                    'services' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant/services',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Application\Controller\Service',
                                'action' => 'index',
                                'type-volume-horaire-code' => 'PREVU',
                            ),
                        ),
                    ),
                    'referentiel' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant/referentiel',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Application\Controller\ServiceReferentiel',
                                'action' => 'index',
                                'type-volume-horaire-code' => 'PREVU',
                            ),
                        ),
                    ),
                    'services-realises' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant/services-realises',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Application\Controller\Service',
                                'action' => 'index',
                                'type-volume-horaire-code' => 'REALISE',
                            ),
                        ),
                    ),
                    'validation-dossier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/validation/dossier',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Validation',
                                'action'     => 'dossier',
                            ),
                        ),
                    ),
                    'validation-service' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/validation/service-prevu',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Validation',
                                'action' => 'service',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ),
                        ),
                    ),
                    'validation-service-realise' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/validation/service-realise',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Validation',
                                'action' => 'service',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ),
                        ),
                    ),
                    'validation-referentiel' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/validation/referentiel',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Validation',
                                'action' => 'referentiel',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                            ),
                        ),
                    ),
                    'validation-referentiel-realise' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/validation/referentiel-realise',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Validation',
                                'action' => 'referentiel',
                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
                            ),
                        ),
                    ),
                    'contrat' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/contrat',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Contrat',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
                    'intervenant' => array(
                        'label'    => 'Intervenant',
                        'title'    => "Intervenant",
                        'route'    => 'intervenant',
                        'resource' => 'controller/Application\Controller\Intervenant:index',
                        'pages' => array(
                            'rechercher' => array(
                                'label'  => " Rechercher",
                                'title'  => "Rechercher un intervenant",
                                'route'  => 'intervenant/rechercher',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'icon'   => "glyphicon glyphicon-search",
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Intervenant:rechercher',
                            ),
                            'fiche' => array(
                                'label'  => "Fiche",
                                'title'  => "Consultation de la fiche de l'intervenant {id}",
                                'route'  => 'intervenant/fiche',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Intervenant:voir',
                            ),
                            'voir-heures-comp' => array(
                                'label'  => "Heures complémentaires",
                                'title'  => "Calcul des heures complémentaires {id}",
                                'route'  => 'intervenant/voir-heures-comp',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'action' => 'voir-heures-comp',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Intervenant:voir-heures-comp',
                            ),
                            'modification-service-du' => array(
                                'label'  => "Modification de service dû",
                                'title'  => "Modification de service dû de l'intervenant {id}",
                                'route'  => 'intervenant/modification-service-du',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\ModificationServiceDu:saisir',
                            ),
                            'dossier' => array(
                                'label'  => "Données personnelles",
                                'title'  => "Saisir les données personnelles d'un intervenant vacataire",
                                'route'  => 'intervenant/saisir-dossier',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Dossier:modifier',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ),
                            'service' => array(
                                'label'  => "Enseignements prévisionnels",
                                'title'  => "Enseignements  prévisionnelsde l'intervenant",
                                'route'  => 'intervenant/services',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Service:index',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ),
                            'pieces-jointes-saisie' => array(
                                // coquille vide qui réserve l'emplacement du menu
                            ),
                            'pieces-jointes-validation' => array(
                                // coquille vide qui réserve l'emplacement du menu
                            ),
                            'validation-dossier' => array(
                                'label'  => "Validation des données personnelles",
                                'title'  => "Validation des données personnelles de l'intervenant",
                                'route'  => 'intervenant/validation-dossier',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:dossier',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ),
                            'validation-service-prevu' => array(
                                'label'  => "Validation des enseignements prévisionnels",
                                'title'  => "Validation des enseignements prévisionnels de l'intervenant",
                                'route'  => 'intervenant/validation-service',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:service',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ),
                            'validation-referentiel-prevu' => array(
                                'label'  => "Validation du référentiel prévisionnel",
                                'title'  => "Validation du référentiel prévisionnel de l'intervenant",
                                'route'  => 'intervenant/validation-referentiel',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:referentiel',
//                                'visible' => 'IntervenantNavigationPageVisibility',
                            ),
                            'agrement' => array(
                                // coquille vide qui réserve l'emplacement du menu "Agréments"
                            ),
                            'contrat' => array(
                                'label'  => "Contrat / avenant",
                                'title'  => "Contrat et avenants de l'intervenant",
                                'route'  => 'intervenant/contrat',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Contrat:index',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ),
                            'services-realises' => array(
                                'label'  => "Enseignements réalisés",
                                'title'  => "Constatation des enseignements réalisés",
                                'route'  => 'intervenant/services-realises',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Service:index',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ),
                            'validation-service-realise' => array(
                                'label'  => "Validation des enseignements réalisés",
                                'title'  => "Validation des enseignements réalisés de l'intervenant",
                                'route'  => 'intervenant/validation-service-realise',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:service',
//                                'visible' => 'IntervenantNavigationPageVisibility',
                            ),
                            'validation-referentiel-realise' => array(
                                'label'  => "Validation du référentiel réalisé",
                                'title'  => "Validation du référentiel réalisé de l'intervenant",
                                'route'  => 'intervenant/validation-referentiel-realise',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:referentiel',
//                                'visible' => 'IntervenantNavigationPageVisibility',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => array('formule-totaux-hetd'),
                    'roles'      => $R_ALL, /** @todo à sécuriser à l'aide d'une assertion pour éviter qu'un intervenant ne puisse voir les HETD des autres */
                    'assertion'  => 'IntervenantAssertion',
                ),
                array(
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => array('apercevoir',),
                    'roles'      => array(R_ROLE),
                ),
                array(
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => array('voir', 'index', 'feuille-de-route'),
                    'roles'      => array(R_INTERVENANT, R_COMPOSANTE,  R_ADMINISTRATEUR),
                ),
                array(
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => array('choisir', 'rechercher', 'search'),
                    'roles'      => array(R_COMPOSANTE, R_ADMINISTRATEUR),
                ),
                array(
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => array('voir-heures-comp'),
                    'roles'      => array(R_DRH, R_ADMINISTRATEUR, R_COMPOSANTE, R_ETABLISSEMENT),
                ),
                array(
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => array('voir', 'modifier'),
                    'roles'      => array(R_INTERVENANT_EXTERIEUR, R_COMPOSANTE, R_ADMINISTRATEUR),
                ),
                array(
                    'controller' => 'Application\Controller\ModificationServiceDu',
                    'action'     => array('saisir'),
                    'roles'      => array(R_COMPOSANTE, R_ADMINISTRATEUR),
                ),
            ),
        ),
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'Intervenant' => [],
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(
                        $R_ALL,
                        'Intervenant',
                        array('total-heures-comp'),
                        'IntervenantAssertion',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Intervenant'           => 'Application\Controller\IntervenantController',
            'Application\Controller\Dossier'               => 'Application\Controller\DossierController',
            'Application\Controller\ModificationServiceDu' => 'Application\Controller\ModificationServiceDuController',
        ),
        'aliases' => array(
            'IntervenantController' => 'Application\Controller\Intervenant',
        ),
        'initializers' => array(
            'Application\Service\Initializer\IntervenantServiceAwareInitializer',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationOffreFormation'           => 'Application\\Service\\OffreFormation',
            'ApplicationIntervenant'              => 'Application\\Service\\Intervenant',
            'ApplicationCivilite'                 => 'Application\\Service\\Civilite',
            'ApplicationStatutIntervenant'        => 'Application\\Service\\StatutIntervenant',
            'ApplicationTypeIntervenant'          => 'Application\\Service\\TypeIntervenant',
            'ApplicationDossier'                  => 'Application\\Service\\Dossier',
            'IntervenantAssertion'                => 'Application\\Assertion\\IntervenantAssertion',
            'PeutSaisirDossierRule'               => 'Application\Rule\Intervenant\PeutSaisirDossierRule',
            'PeutSaisirModificationServiceDuRule' => 'Application\Rule\Intervenant\PeutSaisirModificationServiceDuRule',
            'PeutSaisirServiceRule'               => 'Application\Rule\Intervenant\PeutSaisirServiceRule',
            'PeutSaisirReferentielRule'           => 'Application\Rule\Intervenant\PeutSaisirReferentielRule',
            'PossedeDossierRule'                  => 'Application\Rule\Intervenant\PossedeDossierRule',
            'PossedeServicesRule'                 => 'Application\Rule\Intervenant\PossedeServicesRule',
            'PossedeReferentielRule'              => 'Application\Rule\Intervenant\PossedeReferentielRule',
            'DossierValideRule'                   => 'Application\Rule\Intervenant\DossierValideRule',
            'ServiceValideRule'                   => 'Application\Rule\Intervenant\ServiceValideRule',
            'PeutValiderServiceRule'              => 'Application\Rule\Intervenant\PeutValiderServiceRule',
            'ReferentielValideRule'               => 'Application\Rule\Intervenant\ReferentielValideRule',
            'NecessiteAgrementRule'               => 'Application\Rule\Intervenant\NecessiteAgrementRule',
            'AgrementFourniRule'                  => 'Application\Rule\Intervenant\AgrementFourniRule',
            'EstAffecteRule'                      => 'Application\Rule\Intervenant\EstAffecteRule',
        ),
        'initializers' => array(
            'Application\Service\Initializer\IntervenantServiceAwareInitializer',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'formuleTotauxHetd' => 'Application\View\Helper\Intervenant\TotauxHetdViewHelper',
        ),
        'initializers' => array(
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
            'IntervenantDossier' => 'Application\Form\Intervenant\Dossier',
            'IntervenantHeuresCompForm'                     => 'Application\Form\Intervenant\HeuresCompForm',
            'IntervenantModificationServiceDuForm'          => 'Application\Form\Intervenant\ModificationServiceDuForm',
            'IntervenantModificationServiceDuFieldset'      => 'Application\Form\Intervenant\ModificationServiceDuFieldset',
            'IntervenantMotifModificationServiceDuFieldset' => 'Application\Form\Intervenant\MotifModificationServiceDuFieldset',
        ),
    ),
);
