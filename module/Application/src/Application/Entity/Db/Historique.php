<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Historique
 */
class Historique
{
    /**
     * @var \DateTime
     */
    private $debut;

    /**
     * @var \DateTime
     */
    private $fin;

    /**
     * @var \DateTime
     */
    private $modification;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $modificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $destructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $createur;


    /**
     * Set debut
     *
     * @param \DateTime $debut
     * @return Historique
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;

        return $this;
    }

    /**
     * Get debut
     *
     * @return \DateTime 
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set fin
     *
     * @param \DateTime $fin
     * @return Historique
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime 
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set modification
     *
     * @param \DateTime $modification
     * @return Historique
     */
    public function setModification($modification)
    {
        $this->modification = $modification;

        return $this;
    }

    /**
     * Get modification
     *
     * @return \DateTime 
     */
    public function getModification()
    {
        return $this->modification;
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
     * Set modificateur
     *
     * @param \Application\Entity\Db\Utilisateur $modificateur
     * @return Historique
     */
    public function setModificateur(\Application\Entity\Db\Utilisateur $modificateur = null)
    {
        $this->modificateur = $modificateur;

        return $this;
    }

    /**
     * Get modificateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getModificateur()
    {
        return $this->modificateur;
    }

    /**
     * Set destructeur
     *
     * @param \Application\Entity\Db\Utilisateur $destructeur
     * @return Historique
     */
    public function setDestructeur(\Application\Entity\Db\Utilisateur $destructeur = null)
    {
        $this->destructeur = $destructeur;

        return $this;
    }

    /**
     * Get destructeur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getDestructeur()
    {
        return $this->destructeur;
    }

    /**
     * Set createur
     *
     * @param \Application\Entity\Db\Utilisateur $createur
     * @return Historique
     */
    public function setCreateur(\Application\Entity\Db\Utilisateur $createur = null)
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * Get createur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getCreateur()
    {
        return $this->createur;
    }
}
