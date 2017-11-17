<?php

namespace Application\Form\PieceJointe\Traits;

use Application\Form\PieceJointe\TypePieceJointeSaisieForm;

/**
 * Description of TypePieceJointeSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeSaisieFormAwareTrait
{
    /**
     * @var TypePieceJointeSaisieForm
     */
    private $formTypePieceJointeSaisie;



    /**
     * @param TypePieceJointeSaisieForm $formTypePieceJointeSaisie
     *
     * @return self
     */
    public function setFormTypePieceJointeSaisie(TypePieceJointeSaisieForm $formTypePieceJointeSaisie)
    {
        $this->formTypePieceJointeSaisie = $formTypePieceJointeSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypePieceJointeSaisieForm
     */
    public function getFormTypePieceJointeSaisie()
    {
        if (!empty($this->formTypePieceJointeSaisie)) {
            return $this->formTypePieceJointeSaisie;
        }

        return \Application::$container->get('FormElementManager')->get('typePieceJointeSaisie');
    }
}
