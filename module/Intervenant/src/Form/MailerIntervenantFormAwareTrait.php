<?php

namespace Intervenant\Form;


/**
 * Description of NoteSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MailerIntervenantFormAwareTrait
{
    protected ?MailerIntervenantForm $formMailerIntervenant = null;


    /**
     * @param MailerIntervenantForm $formMailerIntervenant
     *
     * @return self
     */
    public function setFormMailerIntervenant(?MailerIntervenantForm $formMailerIntervenant)
    {
        $this->formMailerIntervenant = $formMailerIntervenant;

        return $this;
    }


    public function getFormMailerIntervenant(): ?MailerIntervenantForm
    {
        if (empty($this->formMailerIntervenant)) {
            $this->formMailerIntervenant = \Application::$container->get('FormElementManager')->get(MailerIntervenantForm::class);
        }

        return $this->formMailerIntervenant;
    }
}