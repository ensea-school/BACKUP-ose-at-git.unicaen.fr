<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntervenantExterieur
 *
 * @ORM\Table(name="INTERVENANT_EXTERIEUR", indexes={@ORM\Index(name="IDX_8FEA72F0B407ABDC", columns={"REGIME_SECU_ID"}), @ORM\Index(name="IDX_8FEA72F0C2443469", columns={"TYPE_ID"}), @ORM\Index(name="IDX_8FEA72F05E37C346", columns={"SITUATION_FAMILIALE_ID"})})
 * @ORM\Entity
 */
class IntervenantExterieur extends Intervenant
{
    /**
     * @var string
     *
     * @ORM\Column(name="PROFESSION", type="string", length=60, nullable=false)
     */
    private $profession;

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
     * @var \Application\Entity\Db\RegimeSecu
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\RegimeSecu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="REGIME_SECU_ID", referencedColumnName="ID")
     * })
     */
    private $regimeSecu;

    /**
     * @var \Application\Entity\Db\TypeIntervenantExterieur
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\TypeIntervenantExterieur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TYPE_ID", referencedColumnName="ID")
     * })
     */
    private $type;

    /**
     * @var \Application\Entity\Db\SituationFamiliale
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\SituationFamiliale")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITUATION_FAMILIALE_ID", referencedColumnName="ID")
     * })
     */
    private $situationFamiliale;



    /**
     * Set profession
     *
     * @param string $profession
     * @return IntervenantExterieur
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession
     *
     * @return string 
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return IntervenantExterieur
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
     * Set regimeSecu
     *
     * @param \Application\Entity\Db\RegimeSecu $regimeSecu
     * @return IntervenantExterieur
     */
    public function setRegimeSecu(\Application\Entity\Db\RegimeSecu $regimeSecu = null)
    {
        $this->regimeSecu = $regimeSecu;

        return $this;
    }

    /**
     * Get regimeSecu
     *
     * @return \Application\Entity\Db\RegimeSecu 
     */
    public function getRegimeSecu()
    {
        return $this->regimeSecu;
    }

    /**
     * Set type
     *
     * @param \Application\Entity\Db\TypeIntervenantExterieur $type
     * @return IntervenantExterieur
     */
    public function setType(\Application\Entity\Db\TypeIntervenantExterieur $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeIntervenantExterieur 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set situationFamiliale
     *
     * @param \Application\Entity\Db\SituationFamiliale $situationFamiliale
     * @return IntervenantExterieur
     */
    public function setSituationFamiliale(\Application\Entity\Db\SituationFamiliale $situationFamiliale = null)
    {
        $this->situationFamiliale = $situationFamiliale;

        return $this;
    }

    /**
     * Get situationFamiliale
     *
     * @return \Application\Entity\Db\SituationFamiliale 
     */
    public function getSituationFamiliale()
    {
        return $this->situationFamiliale;
    }
}
