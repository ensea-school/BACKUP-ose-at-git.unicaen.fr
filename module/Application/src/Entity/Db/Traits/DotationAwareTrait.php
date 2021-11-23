<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Dotation;

/**
 * Description of DotationAwareTrait
 *
 * @author UnicaenCode
 */
trait DotationAwareTrait
{
    /**
     * @var Dotation
     */
    private $dotation;





    /**
     * @param Dotation $dotation
     * @return self
     */
    public function setDotation( Dotation $dotation = null )
    {
        $this->dotation = $dotation;
        return $this;
    }



    /**
     * @return Dotation
     */
    public function getDotation()
    {
        return $this->dotation;
    }
}