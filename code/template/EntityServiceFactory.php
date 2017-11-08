<?php

namespace <namespace>;

use Application\Constants;
use Application\Service\ContextService;
use Zend\ServiceManager\ServiceLocatorInterface as ContainerInterface;
use <targetFullClass>;



/**
 * Description of <class>
 *
 * @author <author>
 */
class <class>
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return <targetClass>
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $<variable> = new <targetClass>;
        $<variable>->setServiceLocator($container);
        $<variable>->setEntityManager($container->get(Constants::BDD));
        $<variable>->setServiceContext($container->get(ContextService::class));

        return $<variable>;
    }
}