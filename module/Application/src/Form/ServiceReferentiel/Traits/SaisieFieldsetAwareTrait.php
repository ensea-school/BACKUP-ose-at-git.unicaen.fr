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
    protected ?SaisieFieldset $formServiceReferentielSaisieFieldset = null;



    /**
     * @param SaisieFieldset $formServiceReferentielSaisieFieldset
     *
     * @return self
     */
    public function setFormServiceReferentielSaisieFieldset( ?SaisieFieldset $formServiceReferentielSaisieFieldset )
    {
        $this->formServiceReferentielSaisieFieldset = $formServiceReferentielSaisieFieldset;

        return $this;
    }



    public function getFormServiceReferentielSaisieFieldset(): ?SaisieFieldset
    {
        if (empty($this->formServiceReferentielSaisieFieldset)){
            $this->formServiceReferentielSaisieFieldset = \Application::$container->get('FormElementManager')->get(SaisieFieldset::class);
        }

        return $this->formServiceReferentielSaisieFieldset;
    }
}