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
    protected ?TypePieceJointeSaisieForm $formPieceJointeTypePieceJointeSaisie;



    /**
     * @param TypePieceJointeSaisieForm|null $formPieceJointeTypePieceJointeSaisie
     *
     * @return self
     */
    public function setFormPieceJointeTypePieceJointeSaisie( ?TypePieceJointeSaisieForm $formPieceJointeTypePieceJointeSaisie )
    {
        $this->formPieceJointeTypePieceJointeSaisie = $formPieceJointeTypePieceJointeSaisie;

        return $this;
    }



    public function getFormPieceJointeTypePieceJointeSaisie(): ?TypePieceJointeSaisieForm
    {
        if (!$this->formPieceJointeTypePieceJointeSaisie){
            $this->formPieceJointeTypePieceJointeSaisie = \Application::$container->get('FormElementManager')->get(TypePieceJointeSaisieForm::class);
        }

        return $this->formPieceJointeTypePieceJointeSaisie;
    }
}