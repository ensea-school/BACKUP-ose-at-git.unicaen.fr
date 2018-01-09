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
    /**
     * @var SaisieFieldset
     */
    private $fieldsetServiceReferentielSaisie;



    /**
     * @param SaisieFieldset $fieldsetServiceReferentielSaisie
     *
     * @return self
     */
    public function setFieldsetServiceReferentielSaisie(SaisieFieldset $fieldsetServiceReferentielSaisie)
    {
        $this->fieldsetServiceReferentielSaisie = $fieldsetServiceReferentielSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return SaisieFieldset
     */
    public function getFieldsetServiceReferentielSaisie()
    {
        if (!empty($this->fieldsetServiceReferentielSaisie)) {
            return $this->fieldsetServiceReferentielSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(SaisieFieldset::class);
    }
}