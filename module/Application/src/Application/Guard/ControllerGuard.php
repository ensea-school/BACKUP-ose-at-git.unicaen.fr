<?php

namespace Application\Guard;

use BjyAuthorize\Guard\Controller;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of ControllerGuard
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class ControllerGuard extends Controller
{

    public function __construct(array $rules, ServiceLocatorInterface $serviceLocator)
    {
        $rules = [
            [
                'controller' => 'Application\Controller\Gestion',
                'action'     => ['droits'],
                'roles'      => [\Application\Acl\AdministrateurRole::ROLE_ID],
            ],
        ];

        parent::__construct($rules, $serviceLocator);
    }

}
