<?php

namespace Application\Entity\Charge\Traits;

use Application\Entity\Charge\Lien;

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
     * @return self
     */
    public function setLien( Lien $lien = null )
    {
        $this->lien = $lien;
        return $this;
    }



    /**
     * @return Lien
     */
    public function getLien()
    {
        return $this->lien;
    }
}