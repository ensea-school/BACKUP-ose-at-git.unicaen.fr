<?php

namespace Application\Form\PieceJointe\Traits;

use Application\Form\PieceJointe\ModifierTypePieceJointeStatutForm;
use Application\Module;
use RuntimeException;

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
     * @return ModfifierTypePieceJointeStatutForm
     * @throws RuntimeException
     */
    public function getFormModifierTypePieceJointeStatut()
    {
        if (!empty($this->formModifierTypePieceJointeStatut)) {
            return $this->formModifierTypePieceJointeStatut;
        }

        $serviceLocator = Module::$serviceLocator;
        if (!$serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        return $serviceLocator->get('FormElementManager')->get('modifierTypePieceJointeStatut');
    }
}
