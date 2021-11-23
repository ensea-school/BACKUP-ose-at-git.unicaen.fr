<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Lien;

/**
 * Description of LienAwareTrait
 *
 * @author UnicaenCode
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