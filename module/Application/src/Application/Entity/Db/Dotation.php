<?php

namespace Application\Entity\Db;

use Application\Interfaces\AnneeAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Dotation
 */
class Dotation implements HistoriqueAwareInterface, AnneeAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var \DateTime
     */
    private $dateEffet;

    /**
     * @var float
     */
    private $heures;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\TypeDotation
     */
    private $type;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;



    /**
     * Set dateEffet
     *
     * @param \DateTime $dateEffet
     *
     * @return Dotation
     */
    public function setDateEffet($dateEffet)
    {
        $this->dateEffet = $dateEffet;

        return $this;
    }



    /**
     * Get dateEffet
     *
     * @return \DateTime
     */
    public function getDateEffet()
    {
        return $this->dateEffet;
    }



    /**
     * Set heures
     *
     * @param float $heures
     *
     * @return Dotation
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }



    /**
     * Get heures
     *
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return Dotation
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * Set type
     *
     * @param \Application\Entity\Db\TypeDotation $type
     *
     * @return Dotation
     */
    public function setType(\Application\Entity\Db\TypeDotation $type = null)
    {
        $this->type = $type;

        return $this;
    }



    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeDotation
     */
    public function getType()
    {
        return $this->type;
    }



    /**
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     *
     * @return Dotation
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee = null)
    {
        $this->annee = $annee;

        return $this;
    }



    /**
     * Get annee
     *
     * @return \Application\Entity\Db\Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}
