<?php

namespace Paiement\Form;


/**
 * Description of TauxFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TauxFormAwareTrait
{
    protected ?TauxForm $formTaux = null;



    /**
     * @param TauxForm $formTaux
     *
     * @return self
     */
    public function setFormTaux(?TauxForm $formTaux)
    {
        $this->formTaux = $formTaux;

        return $this;
    }



    public function getFormTaux(): ?TauxForm
    {
        if (!empty($this->formTaux)) {
            return $this->formTaux;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TauxForm::class);
    }
}