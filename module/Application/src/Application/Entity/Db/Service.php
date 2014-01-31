<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="SERVICE", indexes={@ORM\Index(name="IDX_DED864C878FF2BCB", columns={"INTERVENANT_ID"}), @ORM\Index(name="IDX_DED864C8884B0F7B", columns={"STRUCTURE_ID"}), @ORM\Index(name="IDX_DED864C8AA401F5C", columns={"ANNEE_ID"}), @ORM\Index(name="IDX_DED864C86D76003C", columns={"CADRE_ID"}), @ORM\Index(name="IDX_DED864C88AB4A516", columns={"ELEMENT_PEDAGOGIQUE_ID"})})
 * @ORM\Entity
 */
class Service
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="SERVICE_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Intervenant
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Intervenant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="INTERVENANT_ID", referencedColumnName="ID")
     * })
     */
    private $intervenant;

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
     * @var \Application\Entity\Db\Annee
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Annee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ANNEE_ID", referencedColumnName="ID")
     * })
     */
    private $annee;

    /**
     * @var \Application\Entity\Db\CadreService
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\CadreService")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CADRE_ID", referencedColumnName="ID")
     * })
     */
    private $cadre;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\ElementPedagogique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ELEMENT_PEDAGOGIQUE_ID", referencedColumnName="ID")
     * })
     */
    private $elementPedagogique;



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
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return Service
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return Service
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
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return Service
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee = null)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return \Application\Entity\Db\Annee 
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set cadre
     *
     * @param \Application\Entity\Db\CadreService $cadre
     * @return Service
     */
    public function setCadre(\Application\Entity\Db\CadreService $cadre = null)
    {
        $this->cadre = $cadre;

        return $this;
    }

    /**
     * Get cadre
     *
     * @return \Application\Entity\Db\CadreService 
     */
    public function getCadre()
    {
        return $this->cadre;
    }

    /**
     * Set elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     * @return Service
     */
    public function setElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique = null)
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
