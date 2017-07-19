<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Personnel;

/**
 * Description of PersonnelAwareTrait
 *
 * @author UnicaenCode
 */
trait PersonnelAwareTrait
{
    /**
     * @var Personnel
     */
    private $personnel;





    /**
     * @param Personnel $personnel
     * @return self
     */
    public function setPersonnel( Personnel $personnel = null )
    {
        $this->personnel = $personnel;
        return $this;
    }



    /**
     * @return Personnel
     */
    public function getPersonnel()
    {
        return $this->personnel;
    }
}