<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * GroupeTypeFormation
 */
class GroupeTypeFormation implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

    /**
     * @var integer
     */
    protected $ordre;

    /**
     * @var boolean
     */
    protected $pertinenceNiveau;

    /**
     * @var integer
     */
    protected $id;



    /**
     * Set libelleCourt
     *
     * @param string $libelleCourt
     *
     * @return GroupeTypeFormation
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
     * @return GroupeTypeFormation
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
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return GroupeTypeFormation
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }



    /**
     * Set pertinenceNiveau
     *
     * @param boolean $pertinenceNiveau
     *
     * @return GroupeTypeFormation
     */
    public function setPertinenceNiveau($pertinenceNiveau)
    {
        $this->pertinenceNiveau = $pertinenceNiveau;

        return $this;
    }



    /**
     * Get pertinenceNiveau
     *
     * @return boolean
     */
    public function getPertinenceNiveau()
    {
        return $this->pertinenceNiveau;
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



    public function __toString()
    {
        return $this->getLibelleLong();
    }

}
