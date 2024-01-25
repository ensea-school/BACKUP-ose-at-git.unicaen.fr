<?php

namespace Formule\Entity\Db;

/**
 * Description of FormuleAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleAwareTrait
{
    protected ?Formule $formule = null;



    /**
     * @param Formule $formule
     *
     * @return self
     */
    public function setFormule( ?Formule $formule )
    {
        $this->formule = $formule;

        return $this;
    }



    public function getFormule(): ?Formule
    {
        return $this->formule;
    }
}