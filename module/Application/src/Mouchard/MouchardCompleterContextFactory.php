<?php

namespace Application\Mouchard;

use Psr\Container\ContainerInterface;

/**
 * Description of MouchardCompleterContextFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MouchardCompleterContextFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $mouchardCompleterContext = new MouchardCompleterContext();

        return $mouchardCompleterContext;
    }
}