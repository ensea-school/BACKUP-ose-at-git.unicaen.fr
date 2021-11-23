<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeRessource;

/**
 * Description of TypeRessourceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeRessourceAwareTrait
{
    /**
     * @var TypeRessource
     */
    private $typeRessource;





    /**
     * @param TypeRessource $typeRessource
     * @return self
     */
    public function setTypeRessource( TypeRessource $typeRessource = null )
    {
        $this->typeRessource = $typeRessource;
        return $this;
    }



    /**
     * @return TypeRessource
     */
    public function getTypeRessource()
    {
        return $this->typeRessource;
    }
}