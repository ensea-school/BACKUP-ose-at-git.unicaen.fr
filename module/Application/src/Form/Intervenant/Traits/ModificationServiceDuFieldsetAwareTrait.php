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
    protected ?ModificationServiceDuFieldset $fieldsetIntervenantModificationServiceDu = null;



    /**
     * @param ModificationServiceDuFieldset $fieldsetIntervenantModificationServiceDu
     *
     * @return self
     */
    public function setFieldsetIntervenantModificationServiceDu( ?ModificationServiceDuFieldset $fieldsetIntervenantModificationServiceDu )
    {
        $this->fieldsetIntervenantModificationServiceDu = $fieldsetIntervenantModificationServiceDu;

        return $this;
    }



    public function getFieldsetIntervenantModificationServiceDu(): ?ModificationServiceDuFieldset
    {
        if (empty($this->fieldsetIntervenantModificationServiceDu)){
            $this->fieldsetIntervenantModificationServiceDu = \Application::$container->get('FormElementManager')->get(ModificationServiceDuFieldset::class);
        }

        return $this->fieldsetIntervenantModificationServiceDu;
    }
}