<?php

namespace Application;

use Application\Acl\Role;
use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\DirecteurComposanteRole;
use Application\Acl\GestionnaireComposanteRole;
use Application\Acl\ResponsableComposanteRole;
use Application\Acl\SuperviseurComposanteRole;
use Application\Acl\ResponsableRechercheLaboRole;
use Application\Acl\DrhRole;
use Application\Acl\GestionnaireDrhRole;
use Application\Acl\ResponsableDrhRole;
use Application\Acl\EtablissementRole;
use Application\Acl\SuperviseurEtablissementRole;
use Application\Acl\IntervenantRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantExterieurRole;
use Application\Acl\FoadRole;
use Application\Acl\ResponsableFoadRole;

return [
    'router' => [
        'routes' => [
            'indicateur' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/gestion/indicateur',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Indicateur',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'result' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:indicateur[/structure/:structure]',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                                'structure'  => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'result',
                            ],
                        ],
                    ],
                    'abonner' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:indicateur/abonner',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'abonner',
                            ],
                        ],
                    ],
                    'abonnements' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:personnel/abonnements',
                            'constraints' => [
                                'personnel' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'abonnements',
                            ],
                        ],
                    ],
                    'result-item' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/result-item/:action/:intervenant',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'intervenant' => '[0-9]*',
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
                    'gestion' => [
                        'pages' => [
                            'indicateurs' => [
                                'label'    => "Indicateurs",
                                'title'    => "Indicateurs",
                                'route'    => 'indicateur',
                                'resource' => 'controller/Application\Controller\Indicateur:index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Indicateur',
                    'action'     => ['index', 'result', 'abonner', 'abonnements', 'result-item-donnees-perso-diff-import'],
                    'roles'      => [ComposanteRole::ROLE_ID, DrhRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
            ],
        ],
//        'resource_providers' => array(
//            'BjyAuthorize\Provider\Resource\Config' => array(
//                'Intervenant' => [],
//            ),
//        ),
//        'rule_providers' => array(
//            'BjyAuthorize\Provider\Rule\Config' => array(
//                'allow' => array(
//                    array(
//                        $R_ALL,
//                        'Intervenant',
//                        array('total-heures-comp'),
//                        'IntervenantAssertion',
//                    ),
//                ),
//            ),
//        ),
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Indicateur' => 'Application\Controller\IndicateurController',
        ],
        'initializers' => [
        ],
    ],
    'service_manager' => [
        'invokables'   => [
            'applicationIndicateur'             => 'Application\\Service\\Indicateur',
            'IndicateurService'                 => 'Application\\Service\\Indicateur',
            'NotificationIndicateurService'     => 'Application\\Service\\NotificationIndicateur',
            'AttenteValidationDonneesPerso'     => 'Application\\Service\\Indicateur\\AttenteValidationDonneesPersoIndicateurImpl',
            'AttentePieceJustif'                => 'Application\\Service\\Indicateur\\AttentePieceJustifIndicateurImpl',
            'EnsRealisePermSaisieCloturee'      => 'Application\\Service\\Indicateur\\EnsRealisePermSaisieClotureeIndicateurImpl',
            'AttenteValidationPieceJustif'      => 'Application\\Service\\Indicateur\\AttenteValidationPieceJustifIndicateurImpl',
            'AttenteValidationEnsPrevuVac'      => 'Application\\Service\\Indicateur\\AttenteValidationEnsPrevuVacIndicateurImpl',
            'AttenteValidationEnsPrevuPerm'     => 'Application\\Service\\Indicateur\\AttenteValidationEnsPrevuPermIndicateurImpl',
            'AttenteValidationEnsRealiseVac'    => 'Application\\Service\\Indicateur\\AttenteValidationEnsRealiseVacIndicateurImpl',
            'AttenteValidationEnsRealisePerm'   => 'Application\\Service\\Indicateur\\AttenteValidationEnsRealisePermIndicateurImpl',
            'AttenteAgrementCR'                 => 'Application\\Service\\Indicateur\\AttenteAgrementCRIndicateurImpl',
            'AttenteAgrementCA'                 => 'Application\\Service\\Indicateur\\AttenteAgrementCAIndicateurImpl',
            'AgrementCAMaisPasContrat'          => 'Application\\Service\\Indicateur\\AgrementCAMaisPasContratIndicateurImpl',
            'SaisieServiceApresContratAvenant'  => 'Application\\Service\\Indicateur\\SaisieServiceApresContratAvenantIndicateurImpl',
            'AttenteContrat'                    => 'Application\\Service\\Indicateur\\AttenteContratIndicateurImpl',
            'AttenteAvenant'                    => 'Application\\Service\\Indicateur\\AttenteAvenantIndicateurImpl',
            'ContratAvenantDeposes'             => 'Application\\Service\\Indicateur\\ContratAvenantDeposesIndicateurImpl',
            'DonneesPersoDiffImport'            => 'Application\\Service\\Indicateur\\DonneesPersoDiffImportIndicateurImpl',
            'AttenteRetourContrat'              => 'Application\\Service\\Indicateur\\AttenteRetourContratIndicateurImpl',
            'PermAffectAutreIntervMeme'         => 'Application\\Service\\Indicateur\\PermAffectAutreIntervMemeIndicateurImpl',
            'PermAffectMemeIntervAutre'         => 'Application\\Service\\Indicateur\\PermAffectMemeIntervAutreIndicateurImpl',
            'BiatssAffectMemeIntervAutre'       => 'Application\\Service\\Indicateur\\BiatssAffectMemeIntervAutreIndicateurImpl',
            'AttenteDemandeMep'                 => 'Application\\Service\\Indicateur\\AttenteDemandeMepIndicateurImpl',
            'AttenteMep'                        => 'Application\\Service\\Indicateur\\AttenteMepIndicateurImpl',
            'PlafondHcPrevuHorsRemuFcDepasse'   => 'Application\\Service\\Indicateur\\PlafondHcPrevuHorsRemuFcDepasseIndicateurImpl',
            'PlafondHcRealiseHorsRemuFcDepasse' => 'Application\\Service\\Indicateur\\PlafondHcRealiseHorsRemuFcDepasseIndicateurImpl',
            'PlafondRefPrevuDepasse'            => 'Application\\Service\\Indicateur\\PlafondRefPrevuDepasseIndicateurImpl',
            'PlafondRefRealiseDepasse'          => 'Application\\Service\\Indicateur\\PlafondRefRealiseDepasseIndicateurImpl',
            'EnsHisto'                          => 'Application\\Service\\Indicateur\\EnsHistoIndicateurImpl',
        ],
        'factories'    => [
        ],
        'initializers' => [
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
        'initializers' => [
        ],
    ],
    'form_elements' => [
        'invokables' => [
        ],
    ],
];