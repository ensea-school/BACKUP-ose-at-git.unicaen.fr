<?php

namespace OffreFormation\Entity\Db;

/**
 * NiveauFormation
 */
class NiveauFormation
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelleLong;

    /**
     * @var integer
     */
    private $niveau;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \OffreFormation\Entity\Db\GroupeTypeFormation
     */
    private $groupeTypeFormation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $etape;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etape = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get libelleLong
     *
     * @return string 
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }

    /**
     * Get niveau
     *
     * @return integer 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return NiveauFormation
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Get groupeTypeFormation
     *
     * @return \OffreFormation\Entity\Db\GroupeTypeFormation
     */
    public function getGroupeTypeFormation()
    {
        return $this->groupeTypeFormation;
    }

    /**
     * Get etape
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEtape()
    {
        return $this->etape;
    }
}
