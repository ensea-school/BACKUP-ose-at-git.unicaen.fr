<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employeur
 */
class Employeur implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $codeNaf;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var string
     */
    protected $siret;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Employeur
     */
    protected $employeurPere;



    /**
     * Set codeNaf
     *
     * @param string $codeNaf
     *
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
     *
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
     *
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
     * Set employeurPere
     *
     * @param \Application\Entity\Db\Employeur $employeurPere
     *
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
