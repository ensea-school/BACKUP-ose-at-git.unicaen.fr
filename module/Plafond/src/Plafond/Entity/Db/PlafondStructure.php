<?php

namespace Plafond\Entity\Db;

use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Interfaces\ParametreEntityInterface;
use Application\Traits\ParametreEntityTrait;

/**
 * PlafondStructure
 */
class PlafondStructure implements ParametreEntityInterface
{
    use ParametreEntityTrait;
    use StructureAwareTrait;
    use PlafondAwareTrait;

    /**
     * @var float
     */
    protected float $heures = 0;



    /**
     * @return float
     */
    public function getHeures(): float
    {
        return $this->heures;
    }



    /**
     * @param float $heures
     *
     * @return PlafondStructure
     */
    public function setHeures(float $heures)
    {
        $this->heures = $heures;

        return $this;
    }
}
