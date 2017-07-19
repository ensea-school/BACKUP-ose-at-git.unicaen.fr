<?php

namespace Application\Form\PieceJointe\Traits;

use Application\Form\PieceJointe\TypePieceJointeSaisieForm;
use Application\Module;
use RuntimeException;

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
     * @throws RuntimeException
     */
    public function getFormTypePieceJointeSaisie()
    {
        if (!empty($this->formTypePieceJointeSaisie)) {
            return $this->formTypePieceJointeSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('typePieceJointeSaisie');
    }
}
