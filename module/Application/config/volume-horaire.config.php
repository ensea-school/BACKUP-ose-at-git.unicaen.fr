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
            'volume-horaire' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/volume-horaire',
                    'defaults' => array(
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'VolumeHoraire',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action[/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'saisie' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/saisie/:service',
                            'constraints' => array(
                                'service' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'saisie',
                            ),
                        ),
                    ),
                    'modifier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/modifier/:id',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'modifier',
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
                    'volume-horaire' => array(
                        'label'    => 'Volumes horaires',
                        'title'    => "Gestion des volumes horaires",
                        'visible' => false,
                        'route'    => 'volume-horaire',
                        'params' => array(
                            'action' => 'index',
                        ),
                        'pages' => array(
                            'consultation' => array(
                                'label'  => "Consultation",
                                'title'  => "Consultation des volumes horaires",
                                'route'  => 'volume-horaire',
                                'visible' => false,
                                'withtarget' => true,
                                'pages' => array(),
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
                    'controller' => 'Application\Controller\VolumeHoraire',
                    'action' => array('voir', 'liste', 'saisie'),
                    'roles' => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID)
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\VolumeHoraire'      => 'Application\Controller\VolumeHoraireController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationVolumeHoraire'                  => 'Application\\Service\\VolumeHoraire',
            'FormVolumeHoraireSaisieMultipleHydrator'   => 'Application\Form\VolumeHoraire\SaisieMultipleHydrator',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'volumeHoraireDl'                           => 'Application\View\Helper\VolumeHoraire\Dl',
            'volumeHoraireListe'                        => 'Application\View\Helper\VolumeHoraire\Liste',
            'volumeHoraireSaisieMultipleFieldset'       => 'Application\View\Helper\VolumeHoraire\SaisieMultipleFieldset',
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
            'VolumeHoraireSaisie'                       => 'Application\Form\VolumeHoraire\Saisie',
            'VolumeHoraireSaisieMultipleFieldset'       => 'Application\Form\VolumeHoraire\SaisieMultipleFieldset', // Nécessite plusieurs instances
        ),
    ),
);