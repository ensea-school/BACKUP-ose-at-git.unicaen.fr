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
use Application\Assertion\ContratAssertion;
    
return array(
    'router' => array(
        'routes' => array(
            'contrat' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/contrat',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Contrat',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'creer' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'creer-contrat',
                            ),
                        ),
                    ),
                    'creer-avenant' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'creer-avenant',
                            ),
                        ),
                    ),
                    'voir' => array(
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
                    'valider' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:contrat/valider',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'valider',
                            ),
                        ),
                    ),
                    'devalider' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:contrat/devalider',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'devalider',
                            ),
                        ),
                    ),
                    'saisir-retour' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:contrat/saisir-retour',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'saisir-retour',
                            ),
                        ),
                    ),
                    'exporter' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:contrat/exporter',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'exporter',
                            ),
                        ),
                    ),
                    'deposer-fichier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:contrat/deposer-fichier',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'deposer-fichier',
                            ),
                        ),
                    ),
                    'lister-fichier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:contrat/lister-fichier',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'lister-fichier',
                            ),
                        ),
                    ),
                    'telecharger-fichier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:contrat/telecharger-fichier[/:fichier/:nomFichier]',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'telecharger-fichier',
                            ),
                        ),
                    ),
                    'supprimer-fichier' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:contrat/supprimer-fichier[/:fichier]',
                            'constraints' => array(
                                'contrat' => '[0-9]*',
                                'fichier' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'supprimer-fichier',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
//    'navigation' => array(
//        'default' => array(
//            'home' => array(
//                'pages' => array(
//                    'contrat' => array(
//                        'label'    => 'Contrat et avenants',
//                        'title'    => "Contrat et avenants de l'intervenant",
//                        'route'    => 'contrat/voir',
//                        'withtarget' => true,
//                        'resource' => 'controller/Application\Controller\Contrat:voir',
//                        'pages' => array(
//                            'exporter-contrat' => array(
//                                'label'  => "Exporter",
//                                'title'  => "Exporter le contrat de l'intervenant au format PDF",
//                                'route'  => 'contrat/exporter',
//                                'withtarget' => true,
//                                'resource' => 'controller/Application\Controller\Contrat:exporter',
//                            ),
//                        ),
//                    ),
//                ),
//            ),
//        ),
//    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => array(
                        'creer', 'exporter', 'valider', 'devalider', 'saisir-retour', 
                        'deposer-fichier', 'supprimer-fichier', 
                    ),
                    'roles'      => array(ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => array(
                        'index', 'voir', 
                        'telecharger-fichier', 'lister-fichier', 
                    ),
                    'roles'      => array(IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID),
                ),
            ),
        ),
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'Contrat' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(
                        array(IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID), 
                        'Contrat', 
                        array(ContratAssertion::PRIVILEGE_READ), 
                        'ContratAssertion',
                    ),
                    array(
                        array(ComposanteRole::ROLE_ID), 
                        'Contrat', 
                        array(
                            ContratAssertion::PRIVILEGE_CREATE, 
                            ContratAssertion::PRIVILEGE_DELETE, 
                            ContratAssertion::PRIVILEGE_UPDATE, 
                            ContratAssertion::PRIVILEGE_EXPORTER,
                            ContratAssertion::PRIVILEGE_VALIDER,
                            ContratAssertion::PRIVILEGE_DEVALIDER, 
                            ContratAssertion::PRIVILEGE_DATE_RETOUR, 
                            ContratAssertion::PRIVILEGE_DEPOSER), 
                        'ContratAssertion',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Contrat' => 'Application\Controller\ContratController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationContrat'          => 'Application\\Service\\Contrat',
            'ApplicationTypeContrat'      => 'Application\\Service\\TypeContrat',
            'ApplicationContratProcess'   => 'Application\\Service\\Process\\ContratProcess',
            'NecessiteContratRule'        => 'Application\Rule\Intervenant\NecessiteContratRule',
            'PossedeContratRule'          => 'Application\Rule\Intervenant\PossedeContratRule',
            'PeutCreerContratInitialRule' => 'Application\Rule\Intervenant\PeutCreerContratInitialRule',
            'PeutCreerAvenantRule'        => 'Application\Rule\Intervenant\PeutCreerAvenantRule',
            'ContratAssertion'            => 'Application\\Assertion\\ContratAssertion',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
        ),
    ),
);
