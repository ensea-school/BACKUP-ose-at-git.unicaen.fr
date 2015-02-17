<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Privilege
 */
class Privilege
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Ressource
     */
    private $ressource;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $typeRole;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $statut;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->typeRole = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statut = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Privilege
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Privilege
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
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
     * Set ressource
     *
     * @param \Application\Entity\Db\Ressource $ressource
     * @return Privilege
     */
    public function setRessource(\Application\Entity\Db\Ressource $ressource = null)
    {
        $this->ressource = $ressource;

        return $this;
    }

    /**
     * Get ressource
     *
     * @return \Application\Entity\Db\Ressource 
     */
    public function getRessource()
    {
        return $this->ressource;
    }

    /**
     * Add typeRole
     *
     * @param \Application\Entity\Db\TypeRole $typeRole
     * @return Privilege
     */
    public function addTypeRole(\Application\Entity\Db\TypeRole $typeRole)
    {
        $this->typeRole[] = $typeRole;

        return $this;
    }

    /**
     * Remove typeRole
     *
     * @param \Application\Entity\Db\TypeRole $typeRole
     */
    public function removeTypeRole(\Application\Entity\Db\TypeRole $typeRole)
    {
        $this->typeRole->removeElement($typeRole);
    }

    /**
     * Get typeRole
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTypeRole()
    {
        return $this->typeRole;
    }

    /**
     * Add statut
     *
     * @param \Application\Entity\Db\StatutIntervenant $statut
     * @return Privilege
     */
    public function addStatut(\Application\Entity\Db\StatutIntervenant $statut)
    {
        $this->statut[] = $statut;

        return $this;
    }

    /**
     * Remove statut
     *
     * @param \Application\Entity\Db\StatutIntervenant $statut
     */
    public function removeStatut(\Application\Entity\Db\StatutIntervenant $statut)
    {
        $this->statut->removeElement($statut);
    }

    /**
     * Get statut
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStatut()
    {
        return $this->statut;
    }
}
