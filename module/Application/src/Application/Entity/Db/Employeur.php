<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employeur
 *
 * @ORM\Table(name="EMPLOYEUR", indexes={@ORM\Index(name="IDX_C385FF2814FA4434", columns={"EMPLOYEUR_PERE_ID"})})
 * @ORM\Entity
 */
class Employeur
{
    /**
     * @var string
     *
     * @ORM\Column(name="CODE_NAF", type="string", length=4, nullable=false)
     */
    private $codeNaf;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="EMPLOYEUR_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE", type="string", length=60, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="SIRET", type="string", length=14, nullable=false)
     */
    private $siret;

    /**
     * @var \Application\Entity\Db\Employeur
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Employeur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EMPLOYEUR_PERE_ID", referencedColumnName="ID")
     * })
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
