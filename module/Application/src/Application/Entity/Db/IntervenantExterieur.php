<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntervenantExterieur
 */
class IntervenantExterieur extends Intervenant
{
    /**
     * @var \DateTime
     */
    protected $validiteDebut;

    /**
     * @var \DateTime
     */
    protected $validiteFin;

    /**
     * @var \Application\Entity\Db\TypePoste
     */
    protected $typePoste;

    /**
     * @var \Application\Entity\Db\RegimeSecu
     */
    protected $regimeSecu;

    /**
     * @var \Application\Entity\Db\TypeIntervenantExterieur
     */
    protected $typeIntervenantExterieur;

    /**
     * @var \Application\Entity\Db\SituationFamiliale
     */
    protected $situationFamiliale;


    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return IntervenantExterieur
     */
    public function setValiditeDebut($validiteDebut)
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }

    /**
     * Get validiteDebut
     *
     * @return \DateTime 
     */
    public function getValiditeDebut()
    {
        return $this->validiteDebut;
    }

    /**
     * Set validiteFin
     *
     * @param \DateTime $validiteFin
     * @return IntervenantExterieur
     */
    public function setValiditeFin($validiteFin)
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }

    /**
     * Get validiteFin
     *
     * @return \DateTime 
     */
    public function getValiditeFin()
    {
        return $this->validiteFin;
    }

    /**
     * Set typePoste
     *
     * @param \Application\Entity\Db\TypePoste $typePoste
     * @return IntervenantExterieur
     */
    public function setTypePoste(\Application\Entity\Db\TypePoste $typePoste = null)
    {
        $this->typePoste = $typePoste;

        return $this;
    }

    /**
     * Get typePoste
     *
     * @return \Application\Entity\Db\TypePoste 
     */
    public function getTypePoste()
    {
        return $this->typePoste;
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
     * Set typeIntervenantExterieur
     *
     * @param \Application\Entity\Db\TypeIntervenantExterieur $typeIntervenantExterieur
     * @return IntervenantExterieur
     */
    public function setTypeIntervenantExterieur(\Application\Entity\Db\TypeIntervenantExterieur $typeIntervenantExterieur = null)
    {
        $this->typeIntervenantExterieur = $typeIntervenantExterieur;

        return $this;
    }

    /**
     * Get typeIntervenantExterieur
     *
     * @return \Application\Entity\Db\TypeIntervenantExterieur 
     */
    public function getTypeIntervenantExterieur()
    {
        return $this->typeIntervenantExterieur;
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