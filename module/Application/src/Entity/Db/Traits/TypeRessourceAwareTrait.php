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
    protected ?TypeRessource $typeRessource;



    /**
     * @param TypeRessource|null $typeRessource
     *
     * @return self
     */
    public function setTypeRessource( ?TypeRessource $typeRessource )
    {
        $this->typeRessource = $typeRessource;

        return $this;
    }



    public function getTypeRessource(): ?TypeRessource
    {
        return $this->typeRessource;
    }
}