<?php
return array(
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array('controller' => 'Import\Controller\Import', 'action' => array(), 'roles' => array()),
                array('controller' => 'Import\Controller\Intervenant', 'action' => array('search'), 'roles' => array()),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Import\Controller\Import' => 'Import\Controller\ImportController',
            'Import\Controller\Intervenant' => 'Import\Controller\IntervenantController',
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'import' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/import',
                    'defaults' => array(
                        'controller' => 'Import\Controller\Import',
                    ),
                ),
                'child_routes' => array(
                    'intervenant' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/intervenant[/][:action]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Import\Controller\Intervenant',
                                'action'     => 'search',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'import' => __DIR__ . '/../view',
        ),
    ),
);