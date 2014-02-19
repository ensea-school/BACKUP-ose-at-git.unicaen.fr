<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agrement
 */
class Agrement implements HistoriqueAwareInterface
{
    /**
     * @var integer
     */
    private $intervenantId;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    private $intervenantExterieur;

    /**
     * @var \Application\Entity\Db\Historique
     */
    private $historique;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;


    /**
     * Set intervenantId
     *
     * @param integer $intervenantId
     * @return Agrement
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
     * Set date
     *
     * @param \DateTime $date
     * @return Agrement
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
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
     * Set intervenantExterieur
     *
     * @param \Application\Entity\Db\IntervenantExterieur $intervenantExterieur
     * @return Agrement
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

    /**
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return Agrement
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
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return Agrement
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

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return Agrement
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
}
