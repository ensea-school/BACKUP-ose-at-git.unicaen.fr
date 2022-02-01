<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\ModificationServiceDuForm;

/**
 * Description of ModificationServiceDuFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ModificationServiceDuFormAwareTrait
{
    protected ?ModificationServiceDuForm $formIntervenantModificationServiceDu = null;



    /**
     * @param ModificationServiceDuForm $formIntervenantModificationServiceDu
     *
     * @return self
     */
    public function setFormIntervenantModificationServiceDu( ?ModificationServiceDuForm $formIntervenantModificationServiceDu )
    {
        $this->formIntervenantModificationServiceDu = $formIntervenantModificationServiceDu;

        return $this;
    }



    public function getFormIntervenantModificationServiceDu(): ?ModificationServiceDuForm
    {
        if (empty($this->formIntervenantModificationServiceDu)){
            $this->formIntervenantModificationServiceDu = \Application::$container->get('FormElementManager')->get(ModificationServiceDuForm::class);
        }

        return $this->formIntervenantModificationServiceDu;
    }
}