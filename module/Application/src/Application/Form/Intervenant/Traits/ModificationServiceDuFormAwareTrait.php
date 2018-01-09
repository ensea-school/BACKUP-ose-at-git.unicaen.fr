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
    /**
     * @var ModificationServiceDuForm
     */
    private $formIntervenantModificationServiceDu;



    /**
     * @param ModificationServiceDuForm $formIntervenantModificationServiceDu
     *
     * @return self
     */
    public function setFormIntervenantModificationServiceDu(ModificationServiceDuForm $formIntervenantModificationServiceDu)
    {
        $this->formIntervenantModificationServiceDu = $formIntervenantModificationServiceDu;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ModificationServiceDuForm
     */
    public function getFormIntervenantModificationServiceDu()
    {
        if (!empty($this->formIntervenantModificationServiceDu)) {
            return $this->formIntervenantModificationServiceDu;
        }

        return \Application::$container->get('FormElementManager')->get(ModificationServiceDuForm::class);
    }
}