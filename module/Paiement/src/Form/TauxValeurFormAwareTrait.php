<?php

namespace Paiement\Form;


/**
 * Description of TauxFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TauxValeurFormAwareTrait
{
    protected ?TauxValeurForm $formTauxValeur = null;



    /**
     * @param TauxForm|null $formTauxValeur
     *
     * @return self
     */
    public function setFormTauxValeur(?TauxForm $formTauxValeur)
    {
        $this->formTauxValeur = $formTauxValeur;

        return $this;
    }



    public function getFormTauxValeur(): ?TauxValeurForm
    {
        if (!empty($this->formTauxValeur)) {
            return $this->formTauxValeur;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TauxValeurForm::class);
    }
}