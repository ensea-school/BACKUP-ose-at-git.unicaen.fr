<?php

namespace Application\Entity\Db\Indicateur;

use UnicaenApp\Util;

class Indicateur520 extends AbstractIndicateur
{
    /**
     * @var float
     */
    private $plafond;

    /**
     * @var float
     */
    private $heures;



    /**
     * @return float
     */
    public function getPlafond()
    {
        return $this->plafond;
    }



    /**
     * @param float $plafond
     *
     * @return Indicateur520
     */
    public function setPlafond($plafond)
    {
        $this->plafond = $plafond;

        return $this;
    }



    /**
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
    }



    /**
     * @param float $heures
     *
     * @return Indicateur520
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }



    /**
     * Retourne les détails concernant l'indicateur
     *
     * @return string|null
     */
    public function getDetails()
    {
        if ($this->getPlafond() == 0){
            return sprintf(
                'total HC = %s (HC interdites)',
                Util::formattedNumber($this->getHeures())
            );
        }else{
            return sprintf(
                'total HC = %s (plafond = %s)',
                Util::formattedNumber($this->getHeures()),
                Util::formattedNumber($this->getPlafond())
            );
        }
    }

}
