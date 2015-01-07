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
                    'route'    => '/indicateur',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Indicateur',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'voir' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:indicateur',
                            'constraints' => array(
                                'indicateur' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voir',
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
                ),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
                    
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Indicateur',
                    'action'     => array('index', 'voir', 'abonner'),
                    'roles'      => array('user'),
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
        'invokables' => array(
            'IndicateurService'                => 'Application\\Service\\Indicateur',
            'NotificationIndicateurService'    => 'Application\\Service\\NotificationIndicateur',
            
            'AttenteValidationDonneesPerso'    => 'Application\\Service\\Indicateur\\AttenteValidationDonneesPersoIndicateurImpl',
            'AttentePieceJustifValidee'        => 'Application\\Service\\Indicateur\\AttentePieceJustifValideeIndicateurImpl',
            'AttenteValidationEns'             => 'Application\\Service\\Indicateur\\AttenteValidationEnsIndicateurImpl',
            'AttenteAgrementCR'                => 'Application\\Service\\Indicateur\\AttenteAgrementCRIndicateurImpl',
            'AttenteAgrementCA'                => 'Application\\Service\\Indicateur\\AttenteAgrementCAIndicateurImpl',
            'AgrementCAMaisPasContrat'         => 'Application\\Service\\Indicateur\\AgrementCAMaisPasContratIndicateurImpl',
            'SaisieServiceApresContratAvenant' => 'Application\\Service\\Indicateur\\SaisieServiceApresContratAvenantIndicateurImpl',
            'AttenteContrat'                   => 'Application\\Service\\Indicateur\\AttenteContratIndicateurImpl',
            'AttenteAvenant'                   => 'Application\\Service\\Indicateur\\AttenteAvenantIndicateurImpl',
            'ContratAvenantDeposes'            => 'Application\\Service\\Indicateur\\ContratAvenantDeposesIndicateurImpl',
        ),
        'factories' => array(
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