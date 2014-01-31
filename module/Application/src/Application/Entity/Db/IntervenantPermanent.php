<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntervenantPermanent
 *
 * @ORM\Table(name="INTERVENANT_PERMANENT", indexes={@ORM\Index(name="IDX_FB425D27E774C1C4", columns={"CORPS_ID"}), @ORM\Index(name="IDX_FB425D27DF6D9E85", columns={"UNITE_RECHERCHE_ID"})})
 * @ORM\Entity
 */
class IntervenantPermanent extends Intervenant
{
    /**
     * @var \Application\Entity\Db\Corps
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Corps")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CORPS_ID", referencedColumnName="ID")
     * })
     */
    private $corps;

    /**
     * @var \Application\Entity\Db\Intervenant
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\Intervenant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID", referencedColumnName="ID")
     * })
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Structure
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Structure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="UNITE_RECHERCHE_ID", referencedColumnName="ID")
     * })
     */
    private $uniteRecherche;



    /**
     * Set corps
     *
     * @param \Application\Entity\Db\Corps $corps
     * @return IntervenantPermanent
     */
    public function setCorps(\Application\Entity\Db\Corps $corps = null)
    {
        $this->corps = $corps;

        return $this;
    }

    /**
     * Get corps
     *
     * @return \Application\Entity\Db\Corps 
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * Set id
     *
     * @param \Application\Entity\Db\Intervenant $id
     * @return IntervenantPermanent
     */
    public function setId(\Application\Entity\Db\Intervenant $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return \Application\Entity\Db\Intervenant 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uniteRecherche
     *
     * @param \Application\Entity\Db\Structure $uniteRecherche
     * @return IntervenantPermanent
     */
    public function setUniteRecherche(\Application\Entity\Db\Structure $uniteRecherche = null)
    {
        $this->uniteRecherche = $uniteRecherche;

        return $this;
    }

    /**
     * Get uniteRecherche
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getUniteRecherche()
    {
        return $this->uniteRecherche;
    }
}
