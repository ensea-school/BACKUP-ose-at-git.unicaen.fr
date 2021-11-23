<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Validation;

/**
 * Description of ValidationAwareInterface
 *
 * @author UnicaenCode
 */
interface ValidationAwareInterface
{
    /**
     * @param Validation $validation
     * @return self
     */
    public function setValidation( Validation $validation = null );



    /**
     * @return Validation
     */
    public function getValidation();
}