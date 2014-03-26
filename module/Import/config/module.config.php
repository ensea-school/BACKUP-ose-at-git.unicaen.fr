<?php
return array(
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array('controller' => 'Import\Controller\Import',      'roles' => array('user')),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Import\Controller\Import'      => 'Import\Controller\ImportController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'import' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/import[/:action][/:table]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Import\Controller',
                        'controller' => 'Import',
                        'action'     => 'index',
                        'table'      => null
                    ),
                ),
            ),
        ),
    ),

    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
                    'import' => array(
                        'label'    => 'Import',
                        'route'    => 'import',
                        'pages' => array(
                            'admin' => array(
                                'label'  => "Tableau de bord principal",
                                'route'  => 'import',
                                'params' => array(
                                    'action' => 'showImportTbl',
                                ),
                                'visible' => true,
                                'pages' => array(

                                ),
                            ),
                            'showDiff' => array(
                                'label'  => "Écarts entre OSE et ses sources",
                                'route'  => 'import',
                                'params' => array(
                                    'action' => 'showDiff',
                                ),
                                'visible' => true,
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