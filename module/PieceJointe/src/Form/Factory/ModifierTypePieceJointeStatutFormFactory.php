<?php

namespace PieceJointe\Form\Factory;

use PieceJointe\Form\ModifierTypePieceJointeStatutForm;
use Psr\Container\ContainerInterface;


/**
 * Description of ModeleFormFactory
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class ModifierTypePieceJointeStatutFormFactory
{

    protected $options;



    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new ModifierTypePieceJointeStatutForm();

        return $form;
    }

}