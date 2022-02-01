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
    protected ?ModificationServiceDuFieldset $formIntervenantModificationServiceDuFieldset = null;



    /**
     * @param ModificationServiceDuFieldset $formIntervenantModificationServiceDuFieldset
     *
     * @return self
     */
    public function setFormIntervenantModificationServiceDuFieldset( ?ModificationServiceDuFieldset $formIntervenantModificationServiceDuFieldset )
    {
        $this->formIntervenantModificationServiceDuFieldset = $formIntervenantModificationServiceDuFieldset;

        return $this;
    }



    public function getFormIntervenantModificationServiceDuFieldset(): ?ModificationServiceDuFieldset
    {
        if (empty($this->formIntervenantModificationServiceDuFieldset)){
            $this->formIntervenantModificationServiceDuFieldset = \Application::$container->get('FormElementManager')->get(ModificationServiceDuFieldset::class);
        }

        return $this->formIntervenantModificationServiceDuFieldset;
    }
}