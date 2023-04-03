<?php

namespace PieceJointe\Form\PieceJointe\Traits;

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

        return \Application::$container->get('FormElementManager')->get(TypePieceJointeSaisieForm::class);
    }
}