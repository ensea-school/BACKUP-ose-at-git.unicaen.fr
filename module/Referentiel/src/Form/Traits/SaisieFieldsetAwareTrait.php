<?php

namespace Application\Form\ServiceReferentiel\Traits;

use Application\Form\ServiceReferentiel\SaisieFieldset;

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

        return \Application::$container->get('FormElementManager')->get(SaisieFieldset::class);
    }
}