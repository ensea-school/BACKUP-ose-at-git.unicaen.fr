<?php

namespace Agrement\Form\Factory;

use Agrement\Form\Saisie;
use Psr\Container\ContainerInterface;


/**
 * Description of ModeleFormFactory
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class AgrementFormFactory
{

    protected $options;



    public function __invoke(ContainerInterface $container, $requestedName, $options = null): Saisie
    {
        $form = new Saisie();

        return $form;
    }

}