<?php

namespace Enseignement\Form;

/**
 * Description of SaisieFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait EnseignementSaisieFieldsetAwareTrait
{
    protected ?EnseignementSaisieFieldset $fieldsetEnseignementSaisie = null;



    /**
     * @param SaisieFieldset $fieldsetEnseignementSaisie
     *
     * @return self
     */
    public function setFieldsetEnseignementSaisie(?EnseignementSaisieFieldset $fieldsetEnseignementSaisie)
    {
        $this->fieldsetEnseignementSaisie = $fieldsetEnseignementSaisie;

        return $this;
    }



    public function getFieldsetEnseignementSaisie(): ?EnseignementSaisieFieldset
    {
        if (!empty($this->fieldsetEnseignementSaisie)) {
            return $this->fieldsetEnseignementSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(EnseignementSaisieFieldset::class);
    }
}