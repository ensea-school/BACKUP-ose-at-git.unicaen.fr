<?php

namespace Paiement\Form\DomaineFonctionnel;

use Psr\Container\ContainerInterface;


class DomaineFonctionnelSaisieFormFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): DomaineFonctionnelSaisieForm
    {
        $form = new DomaineFonctionnelSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}