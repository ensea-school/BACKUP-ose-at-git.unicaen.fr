<?php

namespace Intervenant\Form;


/**
 * Description of MailerIntervenantFormAwareTrait
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
        if (!empty($this->formMailerIntervenant)) {
            return $this->formMailerIntervenant;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(MailerIntervenantForm::class);
    }
}