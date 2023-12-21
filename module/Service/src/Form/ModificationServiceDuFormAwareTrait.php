<?php

namespace Service\Form;


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
    public function setFormIntervenantModificationServiceDu(?ModificationServiceDuForm $formIntervenantModificationServiceDu)
    {
        $this->formIntervenantModificationServiceDu = $formIntervenantModificationServiceDu;

        return $this;
    }



    public function getFormIntervenantModificationServiceDu(): ?ModificationServiceDuForm
    {
        if (!empty($this->formIntervenantModificationServiceDu)) {
            return $this->formIntervenantModificationServiceDu;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(ModificationServiceDuForm::class);
    }
}