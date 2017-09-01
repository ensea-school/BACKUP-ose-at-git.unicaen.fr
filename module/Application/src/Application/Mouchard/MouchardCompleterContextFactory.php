<?php

namespace Application\Mouchard;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of MouchardCompleterContextFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MouchardCompleterContextFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return MouchardCompleterContext
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mouchardCompleterContext = new MouchardCompleterContext();
        $mouchardCompleterContext->setServiceContext( $serviceLocator->get('ApplicationContext'));

        return $mouchardCompleterContext;
    }
}