<?php

namespace Paiement\Form;


/**
 * Description of JourFerieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait JourFerieFormAwareTrait
{
    protected ?JourFerieForm $formJourFerie = null;



    /**
     * @param JourFerieForm $formJourFerie
     *
     * @return self
     */
    public function setFormJourFerie(?JourFerieForm $formJourFerie)
    {
        $this->formJourFerie = $formJourFerie;

        return $this;
    }



    public function getFormJourFerie(): ?JourFerieForm
    {
        if (!empty($this->formJourFerie)) {
            return $this->formJourFerie;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(JourFerieForm::class);
    }
}