<?php

namespace Application\Entity\Chargens\Traits;

use Application\Entity\Chargens\Lien;

/**
 * Description of LienAwareTrait
 *
 */
trait LienAwareTrait
{
    /**
     * @var Lien
     */
    private $lien;



    /**
     * @param Lien $lien
     *
     * @return self
     */
    public function setLien(Lien $lien = null)
    {
        $this->lien = $lien;

        return $this;
    }



    public function getLien(): Lien
    {
        return $this->lien;
    }
}