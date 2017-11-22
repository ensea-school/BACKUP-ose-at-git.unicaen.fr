<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\PlafondAwareTrait;
use Application\Entity\Db\Traits\PlafondEtatAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;

/**
 * PlafondApplication
 */
class PlafondApplication
{
    use TypeVolumeHoraireAwareTrait;
    use PlafondAwareTrait;
    use PlafondEtatAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Annee
     */
    protected $anneeDebut;

    /**
     * @var Annee
     */
    protected $anneeFin;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return Annee
     */
    public function getAnneeDebut()
    {
        return $this->anneeDebut;
    }



    /**
     * @param Annee $anneeDebut
     *
     * @return PlafondApplication
     */
    public function setAnneeDebut($anneeDebut)
    {
        $this->anneeDebut = $anneeDebut;

        return $this;
    }



    /**
     * @return Annee
     */
    public function getAnneeFin()
    {
        return $this->anneeFin;
    }



    /**
     * @param Annee $anneeFin
     *
     * @return PlafondApplication
     */
    public function setAnneeFin($anneeFin)
    {
        $this->anneeFin = $anneeFin;

        return $this;
    }



    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        return (string)$this->getPlafondEtat();
    }

}
