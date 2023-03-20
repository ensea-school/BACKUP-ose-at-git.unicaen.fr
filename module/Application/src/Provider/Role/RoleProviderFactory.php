<?php

namespace Application\Provider\Role;

use Application\Provider\Privilege\Privileges;
use Application\Service\ContextService;
use Intervenant\Service\StatutService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Psr\Container\ContainerInterface;
use InvalidArgumentException;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RoleProviderFactory
{
    use ContextServiceAwareTrait;


    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em = $container->get(\Application\Constants::BDD);
        /* @var $em \Doctrine\ORM\EntityManager */

        $roleProvider = new RoleProvider();
        $roleProvider
            ->setEntityManager($em)
            ->setServiceStatut($container->get(StatutService::class))
            ->setServiceContext($container->get(ContextService::class));

        return $roleProvider;
    }
}