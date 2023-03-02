<?php

namespace Dossier\Form\Factory;

use Dossier\Form\AutresForm;
use Psr\Container\ContainerInterface;


/**
 * Description of ModeleFormFactory
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class AutresFormFactory
{

    protected $options;



    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new AutresForm();

        return $form;
    }

}