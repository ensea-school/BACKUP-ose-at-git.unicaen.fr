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
    private $heuresReferentielPrev = 0;

    /**
     * @var float
     */
    private $heuresReferentielReal = 0;

    /**
     * @var float
     */
    private $heuresServicePrev = 0;

    /**
     * @var float
     */
    private $heuresServiceReal = 0;

    /**
     * @var boolean
     */
    private $referentiel = false;

    /**
     * @var boolean
     */
    private $peutSaisirService = false;

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
     * Get heuresReferentielPrev
     *
     * @return float
     */
    public function getHeuresReferentielPrev()
    {
        return $this->heuresReferentielPrev;
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
     * Get heuresServicePrev
     *
     * @return float
     */
    public function getHeuresServicePrev()
    {
        return $this->heuresServicePrev;
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
     * @return bool
     */
    public function getReferentiel(): bool
    {
        return $this->referentiel;
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Get annee
     *
     * @return \Application\Entity\Db\Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}

