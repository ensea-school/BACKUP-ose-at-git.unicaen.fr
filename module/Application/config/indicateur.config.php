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

return array(
    'router' => array(
        'routes' => array(
            'indicateur' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/gestion/indicateur',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Indicateur',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'result' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:indicateur[/structure/:structure]',
                            'constraints' => array(
                                'indicateur' => '[0-9]*',
                                'structure'  => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'result',
                            ),
                        ),
                    ),
                    'abonner' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:indicateur/abonner',
                            'constraints' => array(
                                'indicateur' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'abonner',
                            ),
                        ),
                    ),
                    'abonnements' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:personnel/abonnements',
                            'constraints' => array(
                                'personnel' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'abonnements',
                            ),
                        ),
                    ),
                    'result-item' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/result-item/:action/:intervenant',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'intervenant' => '[0-9]*',
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
                    'gestion' => array(
                        'pages' => array(
                            'indicateurs' => array(
                                'label'    => "Indicateurs",
                                'title'    => "Indicateurs",
                                'route'    => 'indicateur',
                                'resource' => 'controller/Application\Controller\Indicateur:index',
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
                    'controller' => 'Application\Controller\Indicateur',
                    'action'     => array('index', 'result', 'abonner', 'abonnements', 'result-item-donnees-perso-diff-import'),
                    'roles'      => array(ComposanteRole::ROLE_ID, DrhRole::ROLE_ID, AdministrateurRole::ROLE_ID),
                ),
            ),
        ),
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
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Indicateur' => 'Application\Controller\IndicateurController',
        ),
        'initializers' => array(
        ),
    ),
    'service_manager' => array(
        'invokables'   => array(
            'IndicateurService'                 => 'Application\\Service\\Indicateur',
            'NotificationIndicateurService'     => 'Application\\Service\\NotificationIndicateur',
            'AttenteValidationDonneesPerso'     => 'Application\\Service\\Indicateur\\AttenteValidationDonneesPersoIndicateurImpl',
            'AttentePieceJustif'                => 'Application\\Service\\Indicateur\\AttentePieceJustifIndicateurImpl',
            'AttenteValidationPieceJustif'      => 'Application\\Service\\Indicateur\\AttenteValidationPieceJustifIndicateurImpl',
            'AttenteValidationEnsPrevu'        => 'Application\\Service\\Indicateur\\AttenteValidationEnsPrevuIndicateurImpl',
            'AttenteValidationEnsRealise'      => 'Application\\Service\\Indicateur\\AttenteValidationEnsRealiseIndicateurImpl',
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
        ),
        'factories'    => array(
        ),
        'initializers' => array(
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
        ),
        'initializers' => array(
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
        ),
    ),
);