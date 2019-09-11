<?php

namespace Application\Mouchard;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of MouchardCompleterContextFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MouchardCompleterContextFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mouchardCompleterContext = new MouchardCompleterContext();

        return $mouchardCompleterContext;
    }
}