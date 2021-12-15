<?php

namespace Application\Form\Contrat\Factory;

use Application\Form\Contrat\EnvoiMailContratForm;
use Psr\Container\ContainerInterface;


class EnvoiMailContratFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EnvoiMailContratForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new EnvoiMailContratForm();

        return $form;
    }
}