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
    protected ?MotifModificationServiceDuFieldset $formIntervenantMotifModificationServiceDuFieldset = null;



    /**
     * @param MotifModificationServiceDuFieldset $formIntervenantMotifModificationServiceDuFieldset
     *
     * @return self
     */
    public function setFormIntervenantMotifModificationServiceDuFieldset( ?MotifModificationServiceDuFieldset $formIntervenantMotifModificationServiceDuFieldset )
    {
        $this->formIntervenantMotifModificationServiceDuFieldset = $formIntervenantMotifModificationServiceDuFieldset;

        return $this;
    }



    public function getFormIntervenantMotifModificationServiceDuFieldset(): ?MotifModificationServiceDuFieldset
    {
        if (empty($this->formIntervenantMotifModificationServiceDuFieldset)){
            $this->formIntervenantMotifModificationServiceDuFieldset = \Application::$container->get('FormElementManager')->get(MotifModificationServiceDuFieldset::class);
        }

        return $this->formIntervenantMotifModificationServiceDuFieldset;
    }
}