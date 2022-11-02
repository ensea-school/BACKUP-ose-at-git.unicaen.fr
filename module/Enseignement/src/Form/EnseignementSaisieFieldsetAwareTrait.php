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

        return \Application::$container->get('FormElementManager')->get(EnseignementSaisieFieldset::class);
    }
}