<?php

namespace Plafond\Entity\Db;

use Intervenant\Entity\Db\IntervenantAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * PlafondDerogation
 */
class PlafondDerogation implements HistoriqueAwareInterface
{
    use IntervenantAwareTrait;
    use PlafondAwareTrait;
    use HistoriqueAwareTrait;

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
