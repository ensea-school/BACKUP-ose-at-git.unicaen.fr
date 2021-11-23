<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeCentreCout;

/**
 * Description of TypeCentreCoutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeCentreCoutAwareTrait
{
    /**
     * @var TypeCentreCout
     */
    private $typeCentreCout;





    /**
     * @param TypeCentreCout $typeCentreCout
     * @return self
     */
    public function setTypeCentreCout( TypeCentreCout $typeCentreCout = null )
    {
        $this->typeCentreCout = $typeCentreCout;
        return $this;
    }



    /**
     * @return TypeCentreCout
     */
    public function getTypeCentreCout()
    {
        return $this->typeCentreCout;
    }
}