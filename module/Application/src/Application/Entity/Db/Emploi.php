<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Emploi
 */
class Emploi implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var \DateTime
     */
    protected $dateDebut;

    /**
     * @var \DateTime
     */
    protected $dateFin;

    /**
     * @var integer
     */
    protected $intervenantId;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Employeur
     */
    protected $employeur;

    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    protected $intervenantExterieur;



    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Emploi
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }



    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }



    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Emploi
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }



    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }



    /**
     * Set intervenantId
     *
     * @param integer $intervenantId
     *
     * @return Emploi
     */
    public function setIntervenantId($intervenantId)
    {
        $this->intervenantId = $intervenantId;

        return $this;
    }



    /**
     * Get intervenantId
     *
     * @return integer
     */
    public function getIntervenantId()
    {
        return $this->intervenantId;
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
     * Set employeur
     *
     * @param \Application\Entity\Db\Employeur $employeur
     *
     * @return Emploi
     */
    public function setEmployeur(\Application\Entity\Db\Employeur $employeur = null)
    {
        $this->employeur = $employeur;

        return $this;
    }



    /**
     * Get employeur
     *
     * @return \Application\Entity\Db\Employeur
     */
    public function getEmployeur()
    {
        return $this->employeur;
    }



    /**
     * Set intervenantExterieur
     *
     * @param \Application\Entity\Db\IntervenantExterieur $intervenantExterieur
     *
     * @return Emploi
     */
    public function setIntervenantExterieur(\Application\Entity\Db\IntervenantExterieur $intervenantExterieur = null)
    {
        $this->intervenantExterieur = $intervenantExterieur;

        return $this;
    }



    /**
     * Get intervenantExterieur
     *
     * @return \Application\Entity\Db\IntervenantExterieur
     */
    public function getIntervenantExterieur()
    {
        return $this->intervenantExterieur;
    }
}
