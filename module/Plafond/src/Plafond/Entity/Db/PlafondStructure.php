<?php

namespace Plafond\Entity\Db;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Traits\StructureAwareTrait;

/**
 * PlafondStructure
 */
class PlafondStructure
{
    use StructureAwareTrait;
    use PlafondAwareTrait;

    /**
     * @var integer
     */
    protected int $id;

    /**
     * @var Annee|null
     */
    protected ?Annee $anneeDebut;

    /**
     * @var Annee|null
     */
    protected ?Annee $anneeFin;

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
     * @return Annee|null
     */
    public function getAnneeDebut(): ?Annee
    {
        return $this->anneeDebut;
    }



    /**
     * @param Annee|null $anneeDebut
     *
     * @return PlafondStructure
     */
    public function setAnneeDebut(?Annee $anneeDebut): PlafondStructure
    {
        $this->anneeDebut = $anneeDebut;

        return $this;
    }



    /**
     * @return Annee|null
     */
    public function getAnneeFin(): ?Annee
    {
        return $this->anneeFin;
    }



    /**
     * @param Annee|null $anneeFin
     *
     * @return PlafondStructure
     */
    public function setAnneeFin(?Annee $anneeFin): PlafondStructure
    {
        $this->anneeFin = $anneeFin;

        return $this;
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
