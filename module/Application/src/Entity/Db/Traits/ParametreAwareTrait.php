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
    protected ?Parametre $parametre = null;



    /**
     * @param Parametre $parametre
     *
     * @return self
     */
    public function setParametre( ?Parametre $parametre )
    {
        $this->parametre = $parametre;

        return $this;
    }



    public function getParametre(): ?Parametre
    {
        return $this->parametre;
    }
}