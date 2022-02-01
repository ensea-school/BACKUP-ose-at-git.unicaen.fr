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
    protected ?Modulateur $modulateur;



    /**
     * @param Modulateur|null $modulateur
     *
     * @return self
     */
    public function setModulateur( ?Modulateur $modulateur )
    {
        $this->modulateur = $modulateur;

        return $this;
    }



    public function getModulateur(): ?Modulateur
    {
        return $this->modulateur;
    }
}