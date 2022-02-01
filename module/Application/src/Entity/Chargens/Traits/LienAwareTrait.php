<?php

namespace Application\Entity\Chargens\Traits;

use Application\Entity\Chargens\Lien;

/**
 * Description of LienAwareTrait
 *
 * @author UnicaenCode
 */
trait LienAwareTrait
{
    protected ?Lien $lien;



    /**
     * @param Lien|null $lien
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