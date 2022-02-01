<?php

namespace Application\Form\Service\Traits;

use Application\Form\Service\SaisieFieldset;

/**
 * Description of SaisieFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieFieldsetAwareTrait
{
    protected ?SaisieFieldset $formServiceSaisieFieldset = null;



    /**
     * @param SaisieFieldset $formServiceSaisieFieldset
     *
     * @return self
     */
    public function setFormServiceSaisieFieldset( ?SaisieFieldset $formServiceSaisieFieldset )
    {
        $this->formServiceSaisieFieldset = $formServiceSaisieFieldset;

        return $this;
    }



    public function getFormServiceSaisieFieldset(): ?SaisieFieldset
    {
        if (empty($this->formServiceSaisieFieldset)){
            $this->formServiceSaisieFieldset = \Application::$container->get('FormElementManager')->get(SaisieFieldset::class);
        }

        return $this->formServiceSaisieFieldset;
    }
}