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
            
            'AttenteAgrementCR'                 => 'Application\\Service\\Indicateur\\Agrement\\AttenteAgrementCRIndicateurImpl',
            'AttenteAgrementCA'                 => 'Application\\Service\\Indicateur\\Agrement\\AttenteAgrementCAIndicateurImpl',
            
            'AgrementCAMaisPasContrat'          => 'Application\\Service\\Indicateur\\Contrat\\AgrementCAMaisPasContratIndicateurImpl',
            'AttenteContrat'                    => 'Application\\Service\\Indicateur\\Contrat\\AttenteContratIndicateurImpl',
            'AttenteAvenant'                    => 'Application\\Service\\Indicateur\\Contrat\\AttenteAvenantIndicateurImpl',
            'AttenteRetourContrat'              => 'Application\\Service\\Indicateur\\Contrat\\AttenteRetourContratIndicateurImpl',
            'ContratAvenantDeposes'             => 'Application\\Service\\Indicateur\\Contrat\\ContratAvenantDeposesIndicateurImpl',
            'SaisieServiceApresContratAvenant'  => 'Application\\Service\\Indicateur\\Contrat\\SaisieServiceApresContratAvenantIndicateurImpl',
            
            'AttenteValidationDonneesPerso'     => 'Application\\Service\\Indicateur\\Dossier\\AttenteValidationDonneesPersoIndicateurImpl',
            'DonneesPersoDiffImport'            => 'Application\\Service\\Indicateur\\Dossier\\DonneesPersoDiffImportIndicateurImpl',
             
            'AttenteDemandeMepVac'              => 'Application\\Service\\Indicateur\\Paiement\\AttenteDemandeMepVacIndicateurImpl',
            'AttenteDemandeMepPerm'             => 'Application\\Service\\Indicateur\\Paiement\\AttenteDemandeMepPermIndicateurImpl',
            'AttenteMepVac'                     => 'Application\\Service\\Indicateur\\Paiement\\AttenteMepVacIndicateurImpl',
            'AttenteMepPerm'                    => 'Application\\Service\\Indicateur\\Paiement\\AttenteMepPermIndicateurImpl',
                       
            'AttentePieceJustif'                => 'Application\\Service\\Indicateur\\PieceJointe\\AttentePieceJustifIndicateurImpl',
            'AttenteValidationPieceJustif'      => 'Application\\Service\\Indicateur\\PieceJointe\\AttenteValidationPieceJustifIndicateurImpl',
            
            'PermAffectAutreIntervMeme'         => 'Application\\Service\\Indicateur\\Service\\Affectation\\PermAffectAutreIntervMemeIndicateurImpl',
            'PermAffectMemeIntervAutre'         => 'Application\\Service\\Indicateur\\Service\\Affectation\\PermAffectMemeIntervAutreIndicateurImpl',
            'BiatssAffectMemeIntervAutre'       => 'Application\\Service\\Indicateur\\Service\\Affectation\\BiatssAffectMemeIntervAutreIndicateurImpl',
            
            'PlafondHcPrevuHorsRemuFcDepasse'   => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondHcPrevuHorsRemuFcDepasseIndicateurImpl',
            'PlafondHcRealiseHorsRemuFcDepasse' => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondHcRealiseHorsRemuFcDepasseIndicateurImpl',
            'PlafondRefPrevuDepasse'            => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondRefPrevuDepasseIndicateurImpl',
            'PlafondRefRealiseDepasse'          => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondRefRealiseDepasseIndicateurImpl',
            
            'AttenteValidationEnsPrevuVac'      => 'Application\\Service\\Indicateur\\Service\\Validation\\AttenteValidationEnsPrevuVacIndicateurImpl',
            'AttenteValidationEnsPrevuPerm'     => 'Application\\Service\\Indicateur\\Service\\Validation\\AttenteValidationEnsPrevuPermIndicateurImpl',
            'AttenteValidationEnsRealiseVac'    => 'Application\\Service\\Indicateur\\Service\\Validation\\AttenteValidationEnsRealiseVacIndicateurImpl',
            'AttenteValidationEnsRealisePerm'   => 'Application\\Service\\Indicateur\\Service\\Validation\\AttenteValidationEnsRealisePermIndicateurImpl',
            
            'EnsHisto'                          => 'Application\\Service\\Indicateur\\Service\\EnsHistoIndicateurImpl',
            'EnsRealisePermSaisieCloturee'      => 'Application\\Service\\Indicateur\\Service\\EnsRealisePermSaisieClotureeIndicateurImpl',
            
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