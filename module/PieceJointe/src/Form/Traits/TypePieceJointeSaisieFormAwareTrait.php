<?php

namespace PieceJointe\Form\Traits;

use PieceJointe\Form\TypePieceJointeSaisieForm;

/**
 * Description of TypePieceJointeSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeSaisieFormAwareTrait
{
    protected ?TypePieceJointeSaisieForm $formPieceJointeTypePieceJointeSaisie = null;



    /**
     * @param TypePieceJointeSaisieForm $formPieceJointeTypePieceJointeSaisie
     *
     * @return self
     */
    public function setFormPieceJointeTypePieceJointeSaisie(?TypePieceJointeSaisieForm $formPieceJointeTypePieceJointeSaisie)
    {
        $this->formPieceJointeTypePieceJointeSaisie = $formPieceJointeTypePieceJointeSaisie;

        return $this;
    }



    public function getFormPieceJointeTypePieceJointeSaisie(): ?TypePieceJointeSaisieForm
    {
        if (!empty($this->formPieceJointeTypePieceJointeSaisie)) {
            return $this->formPieceJointeTypePieceJointeSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TypePieceJointeSaisieForm::class);
    }
}