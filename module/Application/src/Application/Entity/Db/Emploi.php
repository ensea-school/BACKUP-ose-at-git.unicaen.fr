<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Emploi
 */
class Emploi implements HistoriqueAwareInterface
{
    /**
     * @var \DateTime
     */
    private $dateDebut;

    /**
     * @var \DateTime
     */
    private $dateFin;

    /**
     * @var integer
     */
    private $intervenantId;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Historique
     */
    private $historique;

    /**
     * @var \Application\Entity\Db\Employeur
     */
    private $employeur;

    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    private $intervenantExterieur;


    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
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
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return Emploi
     */
    public function setHistorique(\Application\Entity\Db\Historique $historique = null)
    {
        $this->historique = $historique;

        return $this;
    }

    /**
     * Get historique
     *
     * @return \Application\Entity\Db\Historique 
     */
    public function getHistorique()
    {
        return $this->historique;
    }

    /**
     * Set employeur
     *
     * @param \Application\Entity\Db\Employeur $employeur
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
