<?php

namespace Application;

use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use UnicaenAuth\Guard\PrivilegeController;

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
                                'resource' => PrivilegeController::getResourceId('Application\Controller\Indicateur','index'),
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
                    'roles'      => ['user', ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Indicateur' => Controller\IndicateurController::class,
        ],
    ],
    'service_manager' => [
        'invokables'   => [
            'applicationIndicateur'                    => Service\IndicateurService::class,
            'NotificationIndicateurService'            => Service\NotificationIndicateur::class,
            'AttenteAgrementCR'                        => Service\Indicateur\Agrement\AttenteAgrementCRIndicateurImpl::class,
            'AttenteAgrementCA'                        => Service\Indicateur\Agrement\AttenteAgrementCAIndicateurImpl::class,
            'AgrementCAMaisPasContrat'                 => Service\Indicateur\Contrat\AgrementCAMaisPasContratIndicateurImpl::class,
            'AttenteContrat'                           => Service\Indicateur\Contrat\AttenteContratIndicateurImpl::class,
            'AttenteAvenant'                           => Service\Indicateur\Contrat\AttenteAvenantIndicateurImpl::class,
            'AttenteRetourContrat'                     => Service\Indicateur\Contrat\AttenteRetourContratIndicateurImpl::class,
            'ContratAvenantDeposes'                    => Service\Indicateur\Contrat\ContratAvenantDeposesIndicateurImpl::class,
            'SaisieServiceApresContratAvenant'         => Service\Indicateur\Contrat\SaisieServiceApresContratAvenantIndicateurImpl::class,
            'AttenteValidationDonneesPerso'            => Service\Indicateur\Dossier\AttenteValidationDonneesPersoIndicateurImpl::class,
            'DonneesPersoDiffImport'                   => Service\Indicateur\Dossier\DonneesPersoDiffImportIndicateurImpl::class,
            'DonneesPersoModif'                        => Service\Indicateur\Dossier\DonneesPersoModifIndicateurImpl::class,
            'AttenteDemandeMepVac'                     => Service\Indicateur\Paiement\AttenteDemandeMepVacIndicateurImpl::class,
            'AttenteDemandeMepPerm'                    => Service\Indicateur\Paiement\AttenteDemandeMepPermIndicateurImpl::class,
            'AttenteMepVac'                            => Service\Indicateur\Paiement\AttenteMepVacIndicateurImpl::class,
            'AttenteMepPerm'                           => Service\Indicateur\Paiement\AttenteMepPermIndicateurImpl::class,
            'AttentePieceJustif'                       => Service\Indicateur\PieceJointe\AttentePieceJustifIndicateurImpl::class,
            'AttenteValidationPieceJustif'             => Service\Indicateur\PieceJointe\AttenteValidationPieceJustifIndicateurImpl::class,
            'PermAffectAutreIntervMeme'                => Service\Indicateur\Service\Affectation\PermAffectAutreIntervMemeIndicateurImpl::class,
            'PermAffectMemeIntervAutre'                => Service\Indicateur\Service\Affectation\PermAffectMemeIntervAutreIndicateurImpl::class,
            'BiatssAffectMemeIntervAutre'              => Service\Indicateur\Service\Affectation\BiatssAffectMemeIntervAutreIndicateurImpl::class,
            'PlafondHcPrevuHorsRemuFcDepasse'          => Service\Indicateur\Service\Plafond\PlafondHcPrevuHorsRemuFcDepasseIndicateurImpl::class,
            'PlafondHcRealiseHorsRemuFcDepasse'        => Service\Indicateur\Service\Plafond\PlafondHcRealiseHorsRemuFcDepasseIndicateurImpl::class,
            'PlafondRefPrevuDepasse'                   => Service\Indicateur\Service\Plafond\PlafondRefPrevuDepasseIndicateurImpl::class,
            'PlafondRefRealiseDepasse'                 => Service\Indicateur\Service\Plafond\PlafondRefRealiseDepasseIndicateurImpl::class,
            'AttenteValidationEnsPrevuVac'             => Service\Indicateur\Service\Validation\Enseignement\Prevu\AttenteValidationVacIndicateurImpl::class,
            'AttenteValidationEnsPrevuPerm'            => Service\Indicateur\Service\Validation\Enseignement\Prevu\AttenteValidationPermIndicateurImpl::class,
            'AttenteValidationEnsRealiseVac'           => Service\Indicateur\Service\Validation\Enseignement\Realise\AttenteValidationVacIndicateurImpl::class,
            'AttenteValidationEnsRealisePerm'          => Service\Indicateur\Service\Validation\Enseignement\Realise\AttenteValidationPermIndicateurImpl::class,
            'AttenteValidationEnsRealisePermAutreComp' => Service\Indicateur\Service\Validation\Enseignement\Realise\AttenteValidationPermAutreCompIndicateurImpl::class,
            'AttenteValidationRefPrevuPerm'            => Service\Indicateur\Service\Validation\Referentiel\Prevu\AttenteValidationPermIndicateurImpl::class,
            'AttenteValidationRefRealisePerm'          => Service\Indicateur\Service\Validation\Referentiel\Realise\AttenteValidationPermIndicateurImpl::class,
            'AttenteValidationRefRealisePermAutreComp' => Service\Indicateur\Service\Validation\Referentiel\Realise\AttenteValidationPermAutreCompIndicateurImpl::class,
            'EnsHisto'                                 => Service\Indicateur\Service\EnsHistoIndicateurImpl::class,
            'EnsRealisePermSaisieNonCloturee'          => Service\Indicateur\Service\EnsRealisePermSaisieNonClotureeIndicateurImpl::class,
        ],
    ],
];