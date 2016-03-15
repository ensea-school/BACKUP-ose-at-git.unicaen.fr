<?php

namespace Application\Entity\Db;

/**
 * TblServiceSaisie
 */
class TblServiceSaisie
{
    /**
     * @var float
     */
    private $heuresReferentielPrev = '0';

    /**
     * @var float
     */
    private $heuresReferentielReal = '0';

    /**
     * @var float
     */
    private $heuresServicePrev = '0';

    /**
     * @var float
     */
    private $heuresServiceReal = '0';

    /**
     * @var boolean
     */
    private $peutSaisirReferentiel = '0';

    /**
     * @var boolean
     */
    private $peutSaisirService = '0';

    /**
     * @var boolean
     */
    private $toDelete = '0';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;


    /**
     * Set heuresReferentielPrev
     *
     * @param float $heuresReferentielPrev
     *
     * @return TblServiceSaisie
     */
    public function setHeuresReferentielPrev($heuresReferentielPrev)
    {
        $this->heuresReferentielPrev = $heuresReferentielPrev;

        return $this;
    }

    /**
     * Get heuresReferentielPrev
     *
     * @return float
     */
    public function getHeuresReferentielPrev()
    {
        return $this->heuresReferentielPrev;
    }

    /**
     * Set heuresReferentielReal
     *
     * @param float $heuresReferentielReal
     *
     * @return TblServiceSaisie
     */
    public function setHeuresReferentielReal($heuresReferentielReal)
    {
        $this->heuresReferentielReal = $heuresReferentielReal;

        return $this;
    }

    /**
     * Get heuresReferentielReal
     *
     * @return float
     */
    public function getHeuresReferentielReal()
    {
        return $this->heuresReferentielReal;
    }

    /**
     * Set heuresServicePrev
     *
     * @param float $heuresServicePrev
     *
     * @return TblServiceSaisie
     */
    public function setHeuresServicePrev($heuresServicePrev)
    {
        $this->heuresServicePrev = $heuresServicePrev;

        return $this;
    }

    /**
     * Get heuresServicePrev
     *
     * @return float
     */
    public function getHeuresServicePrev()
    {
        return $this->heuresServicePrev;
    }

    /**
     * Set heuresServiceReal
     *
     * @param float $heuresServiceReal
     *
     * @return TblServiceSaisie
     */
    public function setHeuresServiceReal($heuresServiceReal)
    {
        $this->heuresServiceReal = $heuresServiceReal;

        return $this;
    }

    /**
     * Get heuresServiceReal
     *
     * @return float
     */
    public function getHeuresServiceReal()
    {
        return $this->heuresServiceReal;
    }

    /**
     * Set peutSaisirReferentiel
     *
     * @param boolean $peutSaisirReferentiel
     *
     * @return TblServiceSaisie
     */
    public function setPeutSaisirReferentiel($peutSaisirReferentiel)
    {
        $this->peutSaisirReferentiel = $peutSaisirReferentiel;

        return $this;
    }

    /**
     * Get peutSaisirReferentiel
     *
     * @return boolean
     */
    public function getPeutSaisirReferentiel()
    {
        return $this->peutSaisirReferentiel;
    }

    /**
     * Set peutSaisirService
     *
     * @param boolean $peutSaisirService
     *
     * @return TblServiceSaisie
     */
    public function setPeutSaisirService($peutSaisirService)
    {
        $this->peutSaisirService = $peutSaisirService;

        return $this;
    }

    /**
     * Get peutSaisirService
     *
     * @return boolean
     */
    public function getPeutSaisirService()
    {
        return $this->peutSaisirService;
    }

    /**
     * Set toDelete
     *
     * @param boolean $toDelete
     *
     * @return TblServiceSaisie
     */
    public function setToDelete($toDelete)
    {
        $this->toDelete = $toDelete;

        return $this;
    }

    /**
     * Get toDelete
     *
     * @return boolean
     */
    public function getToDelete()
    {
        return $this->toDelete;
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
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return TblServiceSaisie
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
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     *
     * @return TblServiceSaisie
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
}

