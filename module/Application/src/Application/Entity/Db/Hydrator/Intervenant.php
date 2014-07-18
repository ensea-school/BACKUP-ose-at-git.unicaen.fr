<?php

namespace Application\Entity\Db\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods;
use Application\Entity\Db\Hydrator\DateInfSupStrategy;

/**
 * Description of Intervenant
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Intervenant extends ClassMethods
{
    /**
     * Define if extract values will use camel case or name with underscore
     * @param bool|array $underscoreSeparatedKeys
     */
    public function __construct($underscoreSeparatedKeys = true)
    {
        parent::__construct($underscoreSeparatedKeys);
        
        $this->addStrategy('dateNaissance', new DateInfSupStrategy());
    }
}