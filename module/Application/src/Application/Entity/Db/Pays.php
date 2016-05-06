<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Pays
 */
class Pays implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    const CODE_FRANCE = "100";

    /**
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

    /**
     * @var boolean
     */
    protected $temoinUe;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $validiteDebut;

    /**
     * @var \DateTime
     */
    protected $validiteFin;



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleLong();
    }



    /**
     * Set libelleCourt
     *
     * @param string $libelleCourt
     *
     * @return Structure
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }



    /**
     * Get libelleCourt
     *
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }



    /**
     * Set libelleLong
     *
     * @param string $libelleLong
     *
     * @return Structure
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }



    /**
     * Get libelleLong
     *
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }



    /**
     * Set temoinUe
     *
     * @param boolean $temoinUe
     *
     * @return Structure
     */
    public function setTemoinUe($temoinUe)
    {
        $this->temoinUe = $temoinUe;

        return $this;
    }



    /**
     * Get temoinUe
     *
     * @return boolean
     */
    public function getTemoinUe()
    {
        return $this->temoinUe;
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     *
     * @return self
     */
    public function setValiditeDebut($validiteDebut)
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }



    /**
     * Get validiteDebut
     *
     * @return \DateTime
     */
    public function getValiditeDebut()
    {
        return $this->validiteDebut;
    }



    /**
     * Set validiteFin
     *
     * @param \DateTime $validiteFin
     *
     * @return self
     */
    public function setValiditeFin($validiteFin)
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }



    /**
     * Get validiteFin
     *
     * @return \DateTime
     */
    public function getValiditeFin()
    {
        return $this->validiteFin;
    }
}
