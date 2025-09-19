<?php

namespace Service\Form;

/**
 * Description of MotifModificationServiceDuFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifModificationServiceDuFieldsetAwareTrait
{
    protected ?MotifModificationServiceDuFieldset $fieldsetIntervenantMotifModificationServiceDu = null;



    /**
     * @param MotifModificationServiceDuFieldset $fieldsetIntervenantMotifModificationServiceDu
     *
     * @return self
     */
    public function setFieldsetIntervenantMotifModificationServiceDu(?MotifModificationServiceDuFieldset $fieldsetIntervenantMotifModificationServiceDu)
    {
        $this->fieldsetIntervenantMotifModificationServiceDu = $fieldsetIntervenantMotifModificationServiceDu;

        return $this;
    }



    public function getFieldsetIntervenantMotifModificationServiceDu(): ?MotifModificationServiceDuFieldset
    {
        if (!empty($this->fieldsetIntervenantMotifModificationServiceDu)) {
            return $this->fieldsetIntervenantMotifModificationServiceDu;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(MotifModificationServiceDuFieldset::class);
    }
}