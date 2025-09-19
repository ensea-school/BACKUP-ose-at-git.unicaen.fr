<?php

namespace PieceJointe\Form\Traits;

use PieceJointe\Form\ModifierTypePieceJointeStatutForm;

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
    public function setFormPieceJointeModifierTypePieceJointeStatut(?ModifierTypePieceJointeStatutForm $formPieceJointeModifierTypePieceJointeStatut)
    {
        $this->formPieceJointeModifierTypePieceJointeStatut = $formPieceJointeModifierTypePieceJointeStatut;

        return $this;
    }



    public function getFormPieceJointeModifierTypePieceJointeStatut(): ?ModifierTypePieceJointeStatutForm
    {
        if (!empty($this->formPieceJointeModifierTypePieceJointeStatut)) {
            return $this->formPieceJointeModifierTypePieceJointeStatut;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(ModifierTypePieceJointeStatutForm::class);
    }
}