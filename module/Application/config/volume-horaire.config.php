<?php

namespace Application;

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
            'volume-horaire-referentiel' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/volume-horaire-referentiel',
                    'defaults' => array(
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'VolumeHoraireReferentiel',
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
                            'route'    => '/saisie/:serviceReferentiel',
                            'constraints' => array(
                                'serviceReferentiel' => '[0-9]*',
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
                    'roles' => array(R_INTERVENANT, R_COMPOSANTE, R_ADMINISTRATEUR)
                ),
            ),
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\VolumeHoraireReferentiel',
                    'action' => array('voir', 'liste', 'saisie'),
                    'roles' => array(R_INTERVENANT, R_COMPOSANTE, R_ADMINISTRATEUR)
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\VolumeHoraire'            => 'Application\Controller\VolumeHoraireController',
            'Application\Controller\VolumeHoraireReferentiel' => 'Application\Controller\VolumeHoraireReferentielController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationVolumeHoraire'                  => 'Application\\Service\\VolumeHoraire',
            'ApplicationVolumeHoraireReferentiel'       => 'Application\\Service\\VolumeHoraireReferentiel',
            'ApplicationTypeVolumeHoraire'              => 'Application\\Service\\TypeVolumeHoraire',
            'ApplicationEtatVolumeHoraire'              => 'Application\\Service\\EtatVolumeHoraire',
            'FormVolumeHoraireSaisieMultipleHydrator'   => 'Application\Form\VolumeHoraire\SaisieMultipleHydrator',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'volumeHoraireDl'                           => 'Application\View\Helper\VolumeHoraire\Dl',
            'volumeHoraireListe'                        => 'Application\View\Helper\VolumeHoraire\Liste',
            'volumeHoraireReferentielDl'                => 'Application\View\Helper\VolumeHoraireReferentiel\Dl',
            'volumeHoraireReferentielListe'             => 'Application\View\Helper\VolumeHoraireReferentiel\Liste',
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
            'VolumeHoraireSaisie'                            => 'Application\Form\VolumeHoraire\Saisie',
            'VolumeHoraireSaisieMultipleFieldset'            => 'Application\Form\VolumeHoraire\SaisieMultipleFieldset', // Nécessite plusieurs instances
            'VolumeHoraireReferentielSaisie'                 => 'Application\Form\VolumeHoraireReferentiel\Saisie',
            'VolumeHoraireReferentielSaisieMultipleFieldset' => 'Application\Form\VolumeHoraireReferentiel\SaisieMultipleFieldset', // Nécessite plusieurs instances
        ),
    ),
);
