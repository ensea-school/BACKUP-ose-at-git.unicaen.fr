<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeDotation;

/**
 * Description of TypeDotationAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeDotationAwareInterface
{
    /**
     * @param TypeDotation $typeDotation
     * @return self
     */
    public function setTypeDotation( TypeDotation $typeDotation = null );



    /**
     * @return TypeDotation
     */
    public function getTypeDotation();
}