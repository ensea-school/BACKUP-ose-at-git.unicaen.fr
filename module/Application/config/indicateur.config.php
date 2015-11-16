<?php

namespace Application;

use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\DrhRole;
use UnicaenApp\Util;

return [
    'router' => [
        'routes' => [
            'indicateur' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/gestion/indicateur',
                    'defaults' => [
                        'controller'    => 'Application\Controller\Indicateur',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'result' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/result/:indicateur[/structure/:structure]',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                                'structure'  => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'result',
                            ],
                        ],
                    ],
                    'details' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:indicateur[/structure/:structure]',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                                'structure'  => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'details',
                            ],
                        ],
                    ],
                    'abonner' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/abonner/:indicateur',
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
                            'route' => '/abonnements/:personnel',
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
                    'purger-indicateur-donnees-perso-modif' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/purger-indicateur-donnees-perso-modif/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'purger-indicateur-donnees-perso-modif',
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
                                'resource' => Util::actionToResource('Application\Controller\Indicateur','index'),
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
                    'action'     => [
                        'index',
                        'result','details',
                        'abonner',
                        'abonnements',
                        'result-item-donnees-perso-diff-import',
                        'result-item-donnees-perso-modif',
                        'purger-indicateur-donnees-perso-modif'
                    ],
                    'roles'      => ['user', ComposanteRole::ROLE_ID, DrhRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Indicateur' => 'Application\Controller\IndicateurController',
        ],
    ],
    'service_manager' => [
        'invokables'   => [
            'applicationIndicateur'                    => 'Application\\Service\\IndicateurService',
            'NotificationIndicateurService'            => 'Application\\Service\\NotificationIndicateur',
            'AttenteAgrementCR'                        => 'Application\\Service\\Indicateur\\Agrement\\AttenteAgrementCRIndicateurImpl',
            'AttenteAgrementCA'                        => 'Application\\Service\\Indicateur\\Agrement\\AttenteAgrementCAIndicateurImpl',
            'AgrementCAMaisPasContrat'                 => 'Application\\Service\\Indicateur\\Contrat\\AgrementCAMaisPasContratIndicateurImpl',
            'AttenteContrat'                           => 'Application\\Service\\Indicateur\\Contrat\\AttenteContratIndicateurImpl',
            'AttenteAvenant'                           => 'Application\\Service\\Indicateur\\Contrat\\AttenteAvenantIndicateurImpl',
            'AttenteRetourContrat'                     => 'Application\\Service\\Indicateur\\Contrat\\AttenteRetourContratIndicateurImpl',
            'ContratAvenantDeposes'                    => 'Application\\Service\\Indicateur\\Contrat\\ContratAvenantDeposesIndicateurImpl',
            'SaisieServiceApresContratAvenant'         => 'Application\\Service\\Indicateur\\Contrat\\SaisieServiceApresContratAvenantIndicateurImpl',
            'AttenteValidationDonneesPerso'            => 'Application\\Service\\Indicateur\\Dossier\\AttenteValidationDonneesPersoIndicateurImpl',
            'DonneesPersoDiffImport'                   => 'Application\\Service\\Indicateur\\Dossier\\DonneesPersoDiffImportIndicateurImpl',
            'DonneesPersoModif'                        => 'Application\\Service\\Indicateur\\Dossier\\DonneesPersoModifIndicateurImpl',
            'AttenteDemandeMepVac'                     => 'Application\\Service\\Indicateur\\Paiement\\AttenteDemandeMepVacIndicateurImpl',
            'AttenteDemandeMepPerm'                    => 'Application\\Service\\Indicateur\\Paiement\\AttenteDemandeMepPermIndicateurImpl',
            'AttenteMepVac'                            => 'Application\\Service\\Indicateur\\Paiement\\AttenteMepVacIndicateurImpl',
            'AttenteMepPerm'                           => 'Application\\Service\\Indicateur\\Paiement\\AttenteMepPermIndicateurImpl',
            'AttentePieceJustif'                       => 'Application\\Service\\Indicateur\\PieceJointe\\AttentePieceJustifIndicateurImpl',
            'AttenteValidationPieceJustif'             => 'Application\\Service\\Indicateur\\PieceJointe\\AttenteValidationPieceJustifIndicateurImpl',
            'PermAffectAutreIntervMeme'                => 'Application\\Service\\Indicateur\\Service\\Affectation\\PermAffectAutreIntervMemeIndicateurImpl',
            'PermAffectMemeIntervAutre'                => 'Application\\Service\\Indicateur\\Service\\Affectation\\PermAffectMemeIntervAutreIndicateurImpl',
            'BiatssAffectMemeIntervAutre'              => 'Application\\Service\\Indicateur\\Service\\Affectation\\BiatssAffectMemeIntervAutreIndicateurImpl',
            'PlafondHcPrevuHorsRemuFcDepasse'          => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondHcPrevuHorsRemuFcDepasseIndicateurImpl',
            'PlafondHcRealiseHorsRemuFcDepasse'        => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondHcRealiseHorsRemuFcDepasseIndicateurImpl',
            'PlafondRefPrevuDepasse'                   => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondRefPrevuDepasseIndicateurImpl',
            'PlafondRefRealiseDepasse'                 => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondRefRealiseDepasseIndicateurImpl',
            'AttenteValidationEnsPrevuVac'             => 'Application\\Service\\Indicateur\\Service\\Validation\\Enseignement\\Prevu\\AttenteValidationVacIndicateurImpl',
            'AttenteValidationEnsPrevuPerm'            => 'Application\\Service\\Indicateur\\Service\\Validation\\Enseignement\\Prevu\\AttenteValidationPermIndicateurImpl',
            'AttenteValidationEnsRealiseVac'           => 'Application\\Service\\Indicateur\\Service\\Validation\\Enseignement\\Realise\\AttenteValidationVacIndicateurImpl',
            'AttenteValidationEnsRealisePerm'          => 'Application\\Service\\Indicateur\\Service\\Validation\\Enseignement\\Realise\\AttenteValidationPermIndicateurImpl',
            'AttenteValidationEnsRealisePermAutreComp' => 'Application\\Service\\Indicateur\\Service\\Validation\\Enseignement\\Realise\\AttenteValidationPermAutreCompIndicateurImpl',
            'AttenteValidationRefPrevuPerm'            => 'Application\\Service\\Indicateur\\Service\\Validation\\Referentiel\\Prevu\\AttenteValidationPermIndicateurImpl',
            'AttenteValidationRefRealisePerm'          => 'Application\\Service\\Indicateur\\Service\\Validation\\Referentiel\\Realise\\AttenteValidationPermIndicateurImpl',
            'AttenteValidationRefRealisePermAutreComp' => 'Application\\Service\\Indicateur\\Service\\Validation\\Referentiel\\Realise\\AttenteValidationPermAutreCompIndicateurImpl',
            'EnsHisto'                                 => 'Application\\Service\\Indicateur\\Service\\EnsHistoIndicateurImpl',
            'EnsRealisePermSaisieNonCloturee'          => 'Application\\Service\\Indicateur\\Service\\EnsRealisePermSaisieNonClotureeIndicateurImpl',
        ],
    ],
];