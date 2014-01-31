<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceReferentiel
 *
 * @ORM\Table(name="SERVICE_REFERENTIEL", indexes={@ORM\Index(name="IDX_42741E7DAA401F5C", columns={"ANNEE_ID"}), @ORM\Index(name="IDX_42741E7DE2C64D28", columns={"FONCTION_ID"}), @ORM\Index(name="IDX_42741E7D78FF2BCB", columns={"INTERVENANT_ID"}), @ORM\Index(name="IDX_42741E7D884B0F7B", columns={"STRUCTURE_ID"})})
 * @ORM\Entity
 */
class ServiceReferentiel
{
    /**
     * @var float
     *
     * @ORM\Column(name="HEURES", type="float", precision=126, scale=0, nullable=false)
     */
    private $heures;

    /**
     * @var \Application\Entity\Db\Annee
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\Annee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ANNEE_ID", referencedColumnName="ID")
     * })
     */
    private $annee;

    /**
     * @var \Application\Entity\Db\FonctionReferentiel
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\FonctionReferentiel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FONCTION_ID", referencedColumnName="ID")
     * })
     */
    private $fonction;

    /**
     * @var \Application\Entity\Db\Intervenant
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\Intervenant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="INTERVENANT_ID", referencedColumnName="ID")
     * })
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Structure
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\Structure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="STRUCTURE_ID", referencedColumnName="ID")
     * })
     */
    private $structure;



    /**
     * Set heures
     *
     * @param float $heures
     * @return ServiceReferentiel
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }

    /**
     * Get heures
     *
     * @return float 
     */
    public function getHeures()
    {
        return $this->heures;
    }

    /**
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return ServiceReferentiel
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee)
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
     * Set fonction
     *
     * @param \Application\Entity\Db\FonctionReferentiel $fonction
     * @return ServiceReferentiel
     */
    public function setFonction(\Application\Entity\Db\FonctionReferentiel $fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return \Application\Entity\Db\FonctionReferentiel 
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return ServiceReferentiel
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant)
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
     * @return ServiceReferentiel
     */
    public function setStructure(\Application\Entity\Db\Structure $structure)
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
}
