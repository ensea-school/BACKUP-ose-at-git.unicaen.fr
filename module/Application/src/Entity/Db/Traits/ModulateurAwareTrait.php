<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Modulateur;

/**
 * Description of ModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait ModulateurAwareTrait
{
    /**
     * @var Modulateur
     */
    private $modulateur;





    /**
     * @param Modulateur $modulateur
     * @return self
     */
    public function setModulateur( Modulateur $modulateur = null )
    {
        $this->modulateur = $modulateur;
        return $this;
    }



    /**
     * @return Modulateur
     */
    public function getModulateur()
    {
        return $this->modulateur;
    }
}