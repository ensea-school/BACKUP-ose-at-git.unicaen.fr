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
    /**
     * @var SaisieFieldset
     */
    private $fieldsetServiceSaisie;



    /**
     * @param SaisieFieldset $fieldsetServiceSaisie
     *
     * @return self
     */
    public function setFieldsetServiceSaisie(SaisieFieldset $fieldsetServiceSaisie)
    {
        $this->fieldsetServiceSaisie = $fieldsetServiceSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return SaisieFieldset
     */
    public function getFieldsetServiceSaisie()
    {
        if (!empty($this->fieldsetServiceSaisie)) {
            return $this->fieldsetServiceSaisie;
        }

        return \Application::$container->get('FormElementManager')->get('ServiceSaisieFieldset');
    }
}