<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agrement
 *
 * @ORM\Table(name="AGREMENT", indexes={@ORM\Index(name="IDX_CD3F1085884B0F7B", columns={"STRUCTURE_ID"}), @ORM\Index(name="IDX_CD3F108578FF2BCB", columns={"INTERVENANT_ID"})})
 * @ORM\Entity
 */
class Agrement
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DEBUT_VALIDITE", type="datetime", nullable=true)
     */
    private $debutValidite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FIN_VALIDITE", type="datetime", nullable=false)
     */
    private $finValidite;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="AGREMENT_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var \Application\Entity\Db\Structure
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Structure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="STRUCTURE_ID", referencedColumnName="ID")
     * })
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\IntervenantExterieur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="INTERVENANT_ID", referencedColumnName="INTERVENANT_ID")
     * })
     */
    private $intervenant;



    /**
     * Set debutValidite
     *
     * @param \DateTime $debutValidite
     * @return Agrement
     */
    public function setDebutValidite($debutValidite)
    {
        $this->debutValidite = $debutValidite;

        return $this;
    }

    /**
     * Get debutValidite
     *
     * @return \DateTime 
     */
    public function getDebutValidite()
    {
        return $this->debutValidite;
    }

    /**
     * Set finValidite
     *
     * @param \DateTime $finValidite
     * @return Agrement
     */
    public function setFinValidite($finValidite)
    {
        $this->finValidite = $finValidite;

        return $this;
    }

    /**
     * Get finValidite
     *
     * @return \DateTime 
     */
    public function getFinValidite()
    {
        return $this->finValidite;
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

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\IntervenantExterieur $intervenant
     * @return Agrement
     */
    public function setIntervenant(\Application\Entity\Db\IntervenantExterieur $intervenant = null)
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
}
