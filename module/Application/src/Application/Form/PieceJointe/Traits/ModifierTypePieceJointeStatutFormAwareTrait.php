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
    /**
     * @var ModifierTypePieceJointeStatutForm
     */
    private $formModifierTypePieceJointeStatut;



    /**
     * @param ModifierTypePieceJointeStatutForm $formModifierTypePieceJointeStatut
     *
     * @return self
     */
    public function setFormModifierTypePieceJointeStatut(ModifierTypePieceJointeStatutForm $formModifierTypePieceJointeStatut)
    {
        $this->formModifierTypePieceJointeStatut = $formModifierTypePieceJointeStatut;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ModifierTypePieceJointeStatutForm
     */
    public function getFormModifierTypePieceJointeStatut()
    {
        if (!empty($this->formModifierTypePieceJointeStatut)) {
            return $this->formModifierTypePieceJointeStatut;
        }

        return \Application::$container->get('FormElementManager')->get(ModifierTypePieceJointeStatutForm::class);
    }
}
