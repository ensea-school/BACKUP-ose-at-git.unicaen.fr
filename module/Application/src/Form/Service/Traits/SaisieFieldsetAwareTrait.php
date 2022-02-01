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
    protected ?SaisieFieldset $fieldsetServiceSaisie = null;



    /**
     * @param SaisieFieldset $fieldsetServiceSaisie
     *
     * @return self
     */
    public function setFieldsetServiceSaisie( ?SaisieFieldset $fieldsetServiceSaisie )
    {
        $this->fieldsetServiceSaisie = $fieldsetServiceSaisie;

        return $this;
    }



    public function getFieldsetServiceSaisie(): ?SaisieFieldset
    {
        if (empty($this->fieldsetServiceSaisie)){
            $this->fieldsetServiceSaisie = \Application::$container->get('FormElementManager')->get(SaisieFieldset::class);
        }

        return $this->fieldsetServiceSaisie;
    }
}