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
    protected ?SaisieFieldset $formServiceReferentielSaisieFieldset;



    /**
     * @param SaisieFieldset|null $formServiceReferentielSaisieFieldset
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
        if (!$this->formServiceReferentielSaisieFieldset){
            $this->formServiceReferentielSaisieFieldset = \Application::$container->get('FormElementManager')->get(SaisieFieldset::class);
        }

        return $this->formServiceReferentielSaisieFieldset;
    }
}