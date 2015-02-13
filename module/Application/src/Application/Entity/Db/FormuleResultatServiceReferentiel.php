<?php

namespace Application\Entity\Db;

use Application\Entity\MiseEnPaiementListe;

/**
 * FormuleResultatServiceReferentiel
 */
class FormuleResultatServiceReferentiel implements ServiceAPayerInterface
{
    /**
     * @var float
     */
    private $serviceAssure;

    /**
     * @var float
     */
    private $heuresService;

    /**
     * @var float
     */
    private $heuresComplReferentiel;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $miseEnPaiement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $centreCout;

    /**
     * @var \Application\Entity\Db\FormuleResultat
     */
    private $formuleResultat;

    /**
     * @var \Application\Entity\Db\ServiceReferentiel
     */
    private $serviceReferentiel;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->miseEnPaiement = new \Doctrine\Common\Collections\ArrayCollection();
        $this->centreCout = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set serviceAssure
     *
     * @param float $serviceAssure
     * @return FormuleResultatServiceReferentiel
     */
    public function setServiceAssure($serviceAssure)
    {
        $this->serviceAssure = $serviceAssure;

        return $this;
    }

    /**
     * Get serviceAssure
     *
     * @return float 
     */
    public function getServiceAssure()
    {
        return $this->serviceAssure;
    }

    /**
     * Set heuresService
     *
     * @param float $heuresService
     * @return FormuleResultatServiceReferentiel
     */
    public function setHeuresService($heuresService)
    {
        $this->heuresService = $heuresService;

        return $this;
    }

    /**
     * Get heuresService
     *
     * @return float 
     */
    public function getHeuresService()
    {
        return $this->heuresService;
    }

    /**
     * Get heuresComplFi
     *
     * @return float
     */
    public function getHeuresComplFi()
    {
        return 0.0;
    }

    /**
     * Get heuresComplFa
     *
     * @return float
     */
    public function getHeuresComplFa()
    {
        return 0.0;
    }

    /**
     * Get heuresComplFc
     *
     * @return float
     */
    public function getHeuresComplFc()
    {
        return 0.0;
    }

    /**
     * Get heuresComplFcMajorees
     *
     * @return float
     */
    public function getHeuresComplFcMajorees()
    {
        return 0.0;
    }

    /**
     * Set heuresComplReferentiel
     *
     * @param float $heuresComplReferentiel
     * @return FormuleResultatServiceReferentiel
     */
    public function setHeuresComplReferentiel($heuresComplReferentiel)
    {
        $this->heuresComplReferentiel = $heuresComplReferentiel;

        return $this;
    }

    /**
     * Get heuresComplReferentiel
     *
     * @return float 
     */
    public function getHeuresComplReferentiel()
    {
        return $this->heuresComplReferentiel;
    }

    /**
     *
     * @param TypeHeures $typeHeures
     * @return float
     * @throws \Common\Exception\RuntimeException
     */
    public function getHeures( TypeHeures $typeHeures )
    {
        switch( $typeHeures->getCode() ){
            case TypeHeures::FI: return $this->getHeuresComplFi();
            case TypeHeures::FA: return $this->getHeuresComplFa();
            case TypeHeures::FC: return $this->getHeuresComplFc();
            case TypeHeures::FC_MAJOREES: return $this->getHeuresComplFcMajorees();
            case TypeHeures::REFERENTIEL: return $this->getHeuresComplReferentiel();
        }
        throw new \Common\Exception\RuntimeException('Type d\'heures inconnu');
    }

    /**
     *
     * @param TypeHeures $typeHeures
     * @param float $heures
     * @return self
     * @throws \Common\Exception\RuntimeException
     */
    public function setHeures( TypeHeures $typeHeures, $heures )
    {
        switch( $typeHeures->getCode() ){
            case TypeHeures::REFERENTIEL: return $this->setHeuresComplReferentiel( $heures );
        }
        throw new \Common\Exception\RuntimeException('Type d\'heures inconnu ou incorrect');
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return FormuleResultatServiceReferentiel
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Add miseEnPaiement
     *
     * @param \Application\Entity\Db\MiseEnPaiement $miseEnPaiement
     * @return FormuleResultatServiceReferentiel
     */
    public function addMiseEnPaiement(\Application\Entity\Db\MiseEnPaiement $miseEnPaiement)
    {
        $this->miseEnPaiement[] = $miseEnPaiement;

        return $this;
    }

    /**
     * Remove miseEnPaiement
     *
     * @param \Application\Entity\Db\MiseEnPaiement $miseEnPaiement
     */
    public function removeMiseEnPaiement(\Application\Entity\Db\MiseEnPaiement $miseEnPaiement)
    {
        $this->miseEnPaiement->removeElement($miseEnPaiement);
    }

    /**
     * Get miseEnPaiement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiement()
    {
        return $this->miseEnPaiement;
    }

    /**
     * Get centreCout
     *
     * @param TypeHeures $typeHeures
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCout( TypeHeures $typeHeures=null )
    {
        $filter = function( CentreCout $centreCout ) use ($typeHeures) {
            if ($typeHeures){
                return $centreCout->typeHeuresMatches( $typeHeures );
            }else{
                return true;
            }
        };
        return $this->centreCout->filter($filter);
    }

    /**
     *
     * @param TypeHeures $typeHeures
     * @return CentreCout|null
     */
    public function getDefaultCentreCout( TypeHeures $typeHeures )
    {
        return null; // pas encore de centre de cout par défaut
    }

    /**
     * @return MiseEnPaiementListe
     */
    public function getMiseEnPaiementListe( \DateTime $dateMiseEnPaiement=null, Periode $periodePaiement=null )
    {
        $liste = new MiseEnPaiementListe( $this );
        if ($dateMiseEnPaiement) $liste->setDateMiseEnPaiement( $dateMiseEnPaiement );
        if ($periodePaiement)    $liste->setPeriodePaiement( $periodePaiement );
        return $liste;
    }

    /**
     * Set formuleResultat
     *
     * @param \Application\Entity\Db\FormuleResultat $formuleResultat
     * @return FormuleResultatServiceReferentiel
     */
    public function setFormuleResultat(\Application\Entity\Db\FormuleResultat $formuleResultat = null)
    {
        $this->formuleResultat = $formuleResultat;

        return $this;
    }

    /**
     * Get formuleResultat
     *
     * @return \Application\Entity\Db\FormuleResultat 
     */
    public function getFormuleResultat()
    {
        return $this->formuleResultat;
    }

    /**
     * Set ServiceReferentiel
     *
     * @param \Application\Entity\Db\ServiceReferentiel $serviceReferentiel
     * @return FormuleResultatServiceReferentiel
     */
    public function setServiceReferentiel(\Application\Entity\Db\ServiceReferentiel $serviceReferentiel = null)
    {
        $this->serviceReferentiel = $serviceReferentiel;

        return $this;
    }

    /**
     * Get ServiceReferentiel
     *
     * @return \Application\Entity\Db\ServiceReferentiel 
     */
    public function getServiceReferentiel()
    {
        return $this->serviceReferentiel;
    }
}
