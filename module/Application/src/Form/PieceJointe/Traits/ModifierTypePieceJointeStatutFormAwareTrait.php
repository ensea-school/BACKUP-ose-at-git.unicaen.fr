<?php

namespace Application\Form\PieceJointe\Traits;

use Application\Form\PieceJointe\ModifierTypePieceJointeStatutForm;

/**
 * Description of ModifierTypePieceJointeStatutFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ModifierTypePieceJointeStatutFormAwareTrait
{
    protected ?ModifierTypePieceJointeStatutForm $formPieceJointeModifierTypePieceJointeStatut = null;



    /**
     * @param ModifierTypePieceJointeStatutForm $formPieceJointeModifierTypePieceJointeStatut
     *
     * @return self
     */
    public function setFormPieceJointeModifierTypePieceJointeStatut( ModifierTypePieceJointeStatutForm $formPieceJointeModifierTypePieceJointeStatut )
    {
        $this->formPieceJointeModifierTypePieceJointeStatut = $formPieceJointeModifierTypePieceJointeStatut;

        return $this;
    }



    public function getFormPieceJointeModifierTypePieceJointeStatut(): ?ModifierTypePieceJointeStatutForm
    {
        if (empty($this->formPieceJointeModifierTypePieceJointeStatut)){
            $this->formPieceJointeModifierTypePieceJointeStatut = \Application::$container->get('FormElementManager')->get(ModifierTypePieceJointeStatutForm::class);
        }

        return $this->formPieceJointeModifierTypePieceJointeStatut;
    }
}