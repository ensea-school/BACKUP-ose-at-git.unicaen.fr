<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * SectionCnu
 *
 * @ORM\Table(name="SECTION_CNU")
 * @ORM\Entity
 */
class SectionCnu
{
    /**
     * @var string
     *
     * @ORM\Column(name="ID", type="string", length=2, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="SECTION_CNU_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE", type="string", length=60, nullable=false)
     */
    private $libelle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Db\Intervenant", mappedBy="sectionCnu")
     */
    private $intervenant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->intervenant = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return SectionCnu
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
     * Add intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return SectionCnu
     */
    public function addIntervenant(\Application\Entity\Db\Intervenant $intervenant)
    {
        $this->intervenant[] = $intervenant;

        return $this;
    }

    /**
     * Remove intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     */
    public function removeIntervenant(\Application\Entity\Db\Intervenant $intervenant)
    {
        $this->intervenant->removeElement($intervenant);
    }

    /**
     * Get intervenant
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }
}
