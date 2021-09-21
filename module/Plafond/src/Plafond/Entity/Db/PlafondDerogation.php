<?php

namespace Plafond\Entity\Db;

use Application\Entity\Db\Traits\IntervenantAwareTrait;

/**
 * PlafondDerogation
 */
class PlafondDerogation
{
    use IntervenantAwareTrait;
    use PlafondAwareTrait;

    /**
     * @var integer
     */
    protected int $id;

    /**
     * @var float
     */
    protected float $heures = 0;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



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
