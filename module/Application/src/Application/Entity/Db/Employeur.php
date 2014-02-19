<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employeur
 */
class Employeur
{
    /**
     * @var string
     */
    private $codeNaf;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $siret;

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
    private $employeurPere;


    /**
     * Set codeNaf
     *
     * @param string $codeNaf
     * @return Employeur
     */
    public function setCodeNaf($codeNaf)
    {
        $this->codeNaf = $codeNaf;

        return $this;
    }

    /**
     * Get codeNaf
     *
     * @return string 
     */
    public function getCodeNaf()
    {
        return $this->codeNaf;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Employeur
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
     * Set siret
     *
     * @param string $siret
     * @return Employeur
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string 
     */
    public function getSiret()
    {
        return $this->siret;
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
     * @return Employeur
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
     * Set employeurPere
     *
     * @param \Application\Entity\Db\Employeur $employeurPere
     * @return Employeur
     */
    public function setEmployeurPere(\Application\Entity\Db\Employeur $employeurPere = null)
    {
        $this->employeurPere = $employeurPere;

        return $this;
    }

    /**
     * Get employeurPere
     *
     * @return \Application\Entity\Db\Employeur 
     */
    public function getEmployeurPere()
    {
        return $this->employeurPere;
    }
}
