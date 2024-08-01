<?php

namespace Intervenant\Entity\Db;

/**
 * Description of SituationMatrimonialeAwareTrait
 *
 */
trait SituationMatrimonialeAwareTrait
{
    protected ?SituationMatrimoniale $situationMatrimoniale     = null;

    protected ?\DateTime             $dateSituationMatrimoniale = null;



    /**
     * @param SituationMatrimoniale $situationMatrimoniale
     *
     * @return self
     */
    public function setSituationMatrimoniale(?SituationMatrimoniale $situationMatrimoniale): self
    {
        $this->situationMatrimoniale = $situationMatrimoniale;

        return $this;
    }



    public function getSituationMatrimoniale(): ?SituationMatrimoniale
    {
        return $this->situationMatrimoniale;
    }



    public function getDateSituationMatrimoniale(): ?\DateTime
    {
        return $this->dateSituationMatrimoniale;
    }



    public function setDateSituationMatrimoniale(?\DateTime $dateSituationMatrimoniale): self
    {
        $this->dateSituationMatrimoniale = $dateSituationMatrimoniale;

        return $this;
    }

}