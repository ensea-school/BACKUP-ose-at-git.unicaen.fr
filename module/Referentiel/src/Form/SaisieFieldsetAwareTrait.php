<?php

namespace Referentiel\Form;

use Referentiel\Form\SaisieFieldset;

/**
 * Description of SaisieFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieFieldsetAwareTrait
{
    protected ?SaisieFieldset $fieldsetServiceReferentielSaisie = null;



    /**
     * @param SaisieFieldset $fieldsetServiceReferentielSaisie
     *
     * @return self
     */
    public function setFieldsetServiceReferentielSaisie(?SaisieFieldset $fieldsetServiceReferentielSaisie)
    {
        $this->fieldsetServiceReferentielSaisie = $fieldsetServiceReferentielSaisie;

        return $this;
    }



    public function getFieldsetServiceReferentielSaisie(): ?SaisieFieldset
    {
        if (!empty($this->fieldsetServiceReferentielSaisie)) {
            return $this->fieldsetServiceReferentielSaisie;
        }

        return \AppAdmin::container()->get('FormElementManager')->get(SaisieFieldset::class);
    }
}