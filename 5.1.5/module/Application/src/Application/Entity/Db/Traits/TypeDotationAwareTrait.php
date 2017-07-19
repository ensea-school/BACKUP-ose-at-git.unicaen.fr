<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeDotation;

/**
 * Description of TypeDotationAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeDotationAwareTrait
{
    /**
     * @var TypeDotation
     */
    private $typeDotation;





    /**
     * @param TypeDotation $typeDotation
     * @return self
     */
    public function setTypeDotation( TypeDotation $typeDotation = null )
    {
        $this->typeDotation = $typeDotation;
        return $this;
    }



    /**
     * @return TypeDotation
     */
    public function getTypeDotation()
    {
        return $this->typeDotation;
    }
}