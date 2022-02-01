<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\MotifModificationServiceDuFieldset;

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
    public function setFieldsetIntervenantMotifModificationServiceDu( ?MotifModificationServiceDuFieldset $fieldsetIntervenantMotifModificationServiceDu )
    {
        $this->fieldsetIntervenantMotifModificationServiceDu = $fieldsetIntervenantMotifModificationServiceDu;

        return $this;
    }



    public function getFieldsetIntervenantMotifModificationServiceDu(): ?MotifModificationServiceDuFieldset
    {
        if (empty($this->fieldsetIntervenantMotifModificationServiceDu)){
            $this->fieldsetIntervenantMotifModificationServiceDu = \Application::$container->get('FormElementManager')->get(MotifModificationServiceDuFieldset::class);
        }

        return $this->fieldsetIntervenantMotifModificationServiceDu;
    }
}