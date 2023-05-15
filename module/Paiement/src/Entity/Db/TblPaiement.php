<?php

namespace Paiement\Entity\Db;

use Application\Entity\Db\FormuleResultatService;
use Application\Entity\Db\FormuleResultatServiceReferentiel;

/**
 * TblPaiement
 */
class TblPaiement
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var FormuleResultatService
     */
    private $formuleResultatService;

    /**
     * @var FormuleResultatServiceReferentiel
     */
    private $formuleResultatServiceReferentiel;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Periode
     */
    private $periodePaiement;

    /**
     * @var \Paiement\Entity\Db\MiseEnPaiement
     */
    private $miseEnPaiement;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;

    /**
     * @var float
     */
    private $heuresAPayer;

    /**
     * @var float
     */
    private $heuresAPayerPond;

    /**
     * @var float
     */
    private $heuresDemandees;

    /**
     * @var float
     */
    private $heuresPayees;



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
     * @return FormuleResultatService
     */
    public function getFormuleResultatService()
    {
        return $this->formuleResultatService;
    }



    /**
     * @return FormuleResultatServiceReferentiel
     */
    public function getFormuleResultatServiceReferentiel()
    {
        return $this->formuleResultatServiceReferentiel;
    }



    /**
     *
     * @return ServiceAPayerInterface
     */
    public function getServiceAPayer()
    {
        if ($this->formuleResultatService) return $this->formuleResultatService;
        if ($this->formuleResultatServiceReferentiel) return $this->formuleResultatServiceReferentiel;

        return null;
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
     * Get periodePaiement
     *
     * @return \Application\Entity\Db\Periode
     */
    public function getPeriodePaiement()
    {
        return $this->periodePaiement;
    }



    /**
     * Get miseEnPaiement
     *
     * @return \Paiement\Entity\Db\MiseEnPaiement
     */
    public function getMiseEnPaiement()
    {
        return $this->miseEnPaiement;
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



    /**
     * @return float
     */
    public function getHeuresAPayer()
    {
        return $this->heuresAPayer;
    }



    /**
     * @return float
     */
    public function getHeuresAPayerPond()
    {
        return $this->heuresAPayerPond;
    }



    /**
     * @return float
     */
    public function getHeuresDemandees()
    {
        return $this->heuresDemandees;
    }



    /**
     * @return float
     */
    public function getHeuresPayees()
    {
        return $this->heuresPayees;
    }

}