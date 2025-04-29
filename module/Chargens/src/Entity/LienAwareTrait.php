<?php

namespace Chargens\Entity;

/**
 * Description of LienAwareTrait
 *
 * @author UnicaenCode
 */
trait LienAwareTrait
{
    protected ?Lien $lien = null;



    /**
     * @param Lien $lien
     *
     * @return self
     */
    public function setLien( ?Lien $lien )
    {
        $this->lien = $lien;

        return $this;
    }



    public function getLien(): ?Lien
    {
        return $this->lien;
    }
}