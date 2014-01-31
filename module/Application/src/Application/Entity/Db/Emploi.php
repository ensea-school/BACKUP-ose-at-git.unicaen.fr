<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Emploi
 *
 * @ORM\Table(name="EMPLOI", indexes={@ORM\Index(name="IDX_8458777C78FF2BCB", columns={"INTERVENANT_ID"}), @ORM\Index(name="IDX_8458777CF0035C1C", columns={"EMPLOYEUR_ID"})})
 * @ORM\Entity
 */
class Emploi
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_DEBUT", type="datetime", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_FIN", type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\IntervenantExterieur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="INTERVENANT_ID", referencedColumnName="INTERVENANT_ID")
     * })
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Employeur
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\Employeur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EMPLOYEUR_ID", referencedColumnName="ID")
     * })
     */
    private $employeur;



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
     * Set intervenant
     *
     * @param \Application\Entity\Db\IntervenantExterieur $intervenant
     * @return Emploi
     */
    public function setIntervenant(\Application\Entity\Db\IntervenantExterieur $intervenant)
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\IntervenantExterieur 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

    /**
     * Set employeur
     *
     * @param \Application\Entity\Db\Employeur $employeur
     * @return Emploi
     */
    public function setEmployeur(\Application\Entity\Db\Employeur $employeur)
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
}
