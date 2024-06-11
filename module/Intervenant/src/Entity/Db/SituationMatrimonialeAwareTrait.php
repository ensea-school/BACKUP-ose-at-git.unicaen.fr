<?php

namespace Intervenant\Entity\Db;

/**
 * Description of SituationMatrimonialeAwareTrait
 *
 */
trait SituationMatrimonialeAwareTrait
{
    protected ?SituationMatrimoniale $situationMatrimoniale = null;



    /**
     * @param SituationMatrimoniale $situationMatrimoniale
     *
     * @return self
     */
    public function setSituationMatrimoniale(?SituationMatrimoniale $situationMatrimoniale)
    {
        $this->situationMatrimoniale = $situationMatrimoniale;

        return $this;
    }



    public function getSituationMatrimoniale(): ?SituationMatrimoniale
    {
        return $this->situationMatrimoniale;
    }
}