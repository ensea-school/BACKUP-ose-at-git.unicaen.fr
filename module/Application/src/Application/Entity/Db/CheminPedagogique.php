<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * CheminPedagogique
 *
 * @ORM\Table(name="CHEMIN_PEDAGOGIQUE", indexes={@ORM\Index(name="IDX_B1F283FEB4F27801", columns={"ETAPE_ID"}), @ORM\Index(name="IDX_B1F283FE8AB4A516", columns={"ELEMENT_PEDAGOGIQUE_ID"})})
 * @ORM\Entity
 */
class CheminPedagogique
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_DEBUT_ANNEE_UNIV", type="datetime", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $dateDebutAnneeUniv;

    /**
     * @var \Application\Entity\Db\Etape
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\Etape")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ETAPE_ID", referencedColumnName="ID")
     * })
     */
    private $etape;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\ElementPedagogique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ELEMENT_PEDAGOGIQUE_ID", referencedColumnName="ID")
     * })
     */
    private $elementPedagogique;



    /**
     * Set dateDebutAnneeUniv
     *
     * @param \DateTime $dateDebutAnneeUniv
     * @return CheminPedagogique
     */
    public function setDateDebutAnneeUniv($dateDebutAnneeUniv)
    {
        $this->dateDebutAnneeUniv = $dateDebutAnneeUniv;

        return $this;
    }

    /**
     * Get dateDebutAnneeUniv
     *
     * @return \DateTime 
     */
    public function getDateDebutAnneeUniv()
    {
        return $this->dateDebutAnneeUniv;
    }

    /**
     * Set etape
     *
     * @param \Application\Entity\Db\Etape $etape
     * @return CheminPedagogique
     */
    public function setEtape(\Application\Entity\Db\Etape $etape)
    {
        $this->etape = $etape;

        return $this;
    }

    /**
     * Get etape
     *
     * @return \Application\Entity\Db\Etape 
     */
    public function getEtape()
    {
        return $this->etape;
    }

    /**
     * Set elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     * @return CheminPedagogique
     */
    public function setElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique = $elementPedagogique;

        return $this;
    }

    /**
     * Get elementPedagogique
     *
     * @return \Application\Entity\Db\ElementPedagogique 
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }
}
