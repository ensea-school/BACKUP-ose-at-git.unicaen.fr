<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeModulateur;

/**
 * Description of TypeModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurAwareTrait
{
    /**
     * @var TypeModulateur
     */
    private $typeModulateur;





    /**
     * @param TypeModulateur $typeModulateur
     * @return self
     */
    public function setTypeModulateur( TypeModulateur $typeModulateur = null )
    {
        $this->typeModulateur = $typeModulateur;
        return $this;
    }



    /**
     * @return TypeModulateur
     */
    public function getTypeModulateur()
    {
        return $this->typeModulateur;
    }
}