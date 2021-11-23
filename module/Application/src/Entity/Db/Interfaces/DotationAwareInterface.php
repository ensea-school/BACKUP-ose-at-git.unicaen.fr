<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Dotation;

/**
 * Description of DotationAwareInterface
 *
 * @author UnicaenCode
 */
interface DotationAwareInterface
{
    /**
     * @param Dotation $dotation
     * @return self
     */
    public function setDotation( Dotation $dotation = null );



    /**
     * @return Dotation
     */
    public function getDotation();
}