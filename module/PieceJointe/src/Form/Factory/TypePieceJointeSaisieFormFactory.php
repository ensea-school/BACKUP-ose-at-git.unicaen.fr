<?php

namespace PieceJointe\Form\Factory;

use PieceJointe\Form\TypePieceJointeSaisieForm;
use Psr\Container\ContainerInterface;


/**
 * Description of ModeleFormFactory
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class TypePieceJointeSaisieFormFactory
{

    protected $options;



    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new TypePieceJointeSaisieForm();

        return $form;
    }

}