<?php

namespace Formule\Entity\Db;

/**
 * Description of FormuleResultatAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatAwareTrait
{
    protected ?FormuleResultat $formuleResultat = null;



    /**
     * @param FormuleResultat $formuleResultat
     *
     * @return self
     */
    public function setFormuleResultat( ?FormuleResultat $formuleResultat )
    {
        $this->formuleResultat = $formuleResultat;

        return $this;
    }



    public function getFormuleResultat(): ?FormuleResultat
    {
        return $this->formuleResultat;
    }
}