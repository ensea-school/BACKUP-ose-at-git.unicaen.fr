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
    protected ?MotifModificationServiceDuFieldset $formIntervenantMotifModificationServiceDuFieldset;



    /**
     * @param MotifModificationServiceDuFieldset|null $formIntervenantMotifModificationServiceDuFieldset
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
        if (!$this->formIntervenantMotifModificationServiceDuFieldset){
            $this->formIntervenantMotifModificationServiceDuFieldset = \Application::$container->get('FormElementManager')->get(MotifModificationServiceDuFieldset::class);
        }

        return $this->formIntervenantMotifModificationServiceDuFieldset;
    }
}