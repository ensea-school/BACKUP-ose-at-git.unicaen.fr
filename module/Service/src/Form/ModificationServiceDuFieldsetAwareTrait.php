<?php

namespace Service\Form;

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
    public function setFieldsetIntervenantModificationServiceDu(?ModificationServiceDuFieldset $fieldsetIntervenantModificationServiceDu)
    {
        $this->fieldsetIntervenantModificationServiceDu = $fieldsetIntervenantModificationServiceDu;

        return $this;
    }



    public function getFieldsetIntervenantModificationServiceDu(): ?ModificationServiceDuFieldset
    {
        if (!empty($this->fieldsetIntervenantModificationServiceDu)) {
            return $this->fieldsetIntervenantModificationServiceDu;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(ModificationServiceDuFieldset::class);
    }
}