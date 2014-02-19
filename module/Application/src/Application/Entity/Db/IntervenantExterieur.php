<?php

namespace Application\Entity\Db;

/**
 * IntervenantExterieur
 */
class IntervenantExterieur extends Intervenant
{
    /**
     * @var \Application\Entity\Db\SituationFamiliale
     */
    private $situationFamiliale;

    /**
     * @var \Application\Entity\Db\TypeIntervenantExterieur
     */
    private $typeIntervenantExterieur;

    /**
     * @var \Application\Entity\Db\TypePoste
     */
    private $typePoste;

    /**
     * @var \Application\Entity\Db\RegimeSecu
     */
    private $regimeSecu;


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
}