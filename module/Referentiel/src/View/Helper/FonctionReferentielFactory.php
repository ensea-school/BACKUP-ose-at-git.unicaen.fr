<?php

namespace Referentiel\View\Helper;


use Psr\Container\ContainerInterface;

/**
 * Description of FonctionReferentielFactory
 *
 */
class FonctionReferentielFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $helper = new FonctionReferentielViewHelper();

        return $helper;
    }
}