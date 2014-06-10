<?php

namespace Import;

/**
 * @todo Impossible de faire le use ci-dessous!
 */
//use Application\Entity\Db\RoleUtilisateur;
const ROLE_ID_ADMIN = 'Administrateur';

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
                        'resource' => 'controller/Import\Controller\Import:index',
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

    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Import\Controller\Import',
                    'roles' => array(ROLE_ID_ADMIN),
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