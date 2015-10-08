<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Parametre;

/**
 * Description of ParametreAwareTrait
 *
 * @author UnicaenCode
 */
trait ParametreAwareTrait
{
    /**
     * @var Parametre
     */
    private $parametre;





    /**
     * @param Parametre $parametre
     * @return self
     */
    public function setParametre( Parametre $parametre = null )
    {
        $this->parametre = $parametre;
        return $this;
    }



    /**
     * @return Parametre
     */
    public function getParametre()
    {
        return $this->parametre;
    }
}