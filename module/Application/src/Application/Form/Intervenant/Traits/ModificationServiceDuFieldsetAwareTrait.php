<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\ModificationServiceDuFieldset;

/**
 * Description of ModificationServiceDuFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait ModificationServiceDuFieldsetAwareTrait
{
    /**
     * @var ModificationServiceDuFieldset
     */
    private $fieldsetIntervenantModificationServiceDu;



    /**
     * @param ModificationServiceDuFieldset $fieldsetIntervenantModificationServiceDu
     *
     * @return self
     */
    public function setFieldsetIntervenantModificationServiceDu(ModificationServiceDuFieldset $fieldsetIntervenantModificationServiceDu)
    {
        $this->fieldsetIntervenantModificationServiceDu = $fieldsetIntervenantModificationServiceDu;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ModificationServiceDuFieldset
     */
    public function getFieldsetIntervenantModificationServiceDu()
    {
        if (!empty($this->fieldsetIntervenantModificationServiceDu)) {
            return $this->fieldsetIntervenantModificationServiceDu;
        }

        return \Application::$container->get('FormElementManager')->get('IntervenantModificationServiceDuFieldset');
    }
}