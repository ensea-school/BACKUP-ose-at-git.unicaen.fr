<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\CcActivite;

/**
 * Description of CcActiviteAwareTrait
 *
 * @author UnicaenCode
 */
trait CcActiviteAwareTrait
{
    /**
     * @var CcActivite
     */
    private $ccActivite;





    /**
     * @param CcActivite $ccActivite
     * @return self
     */
    public function setCcActivite( CcActivite $ccActivite = null )
    {
        $this->ccActivite = $ccActivite;
        return $this;
    }



    /**
     * @return CcActivite
     */
    public function getCcActivite()
    {
        return $this->ccActivite;
    }
}