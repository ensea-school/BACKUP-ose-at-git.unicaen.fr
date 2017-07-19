<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\NiveauFormation;

/**
 * Description of NiveauFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauFormationAwareTrait
{
    /**
     * @var NiveauFormation
     */
    private $niveauFormation;





    /**
     * @param NiveauFormation $niveauFormation
     * @return self
     */
    public function setNiveauFormation( NiveauFormation $niveauFormation = null )
    {
        $this->niveauFormation = $niveauFormation;
        return $this;
    }



    /**
     * @return NiveauFormation
     */
    public function getNiveauFormation()
    {
        return $this->niveauFormation;
    }
}