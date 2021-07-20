<?php

namespace ExportRh\Form\Factory;


use ExportRh\Connecteur\Siham\SihamConnecteur;
use ExportRh\Form\ExportRhForm;
use Psr\Container\ContainerInterface;

/**
 * Description of ExportRhFormFactory
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class ExportRhFormFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $config     = $container->get('Config');
        $connecteur = '';

        switch ($config['export-rh']['connecteur']) {
            case 'siham':
                $connecteur = $container->get(SihamConnecteur::class);
            break;
        }


        $fieldset = $connecteur->recupererFieldsetConnecteur();


        $form = new ExportRhForm($fieldset);

        return $form;
    }

}