<?php

namespace Application\Entity\Db;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use OffreFormation\Entity\Db\TypeHeures;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * MiseEnPaiement
 */
class MiseEnPaiement implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    const A_METTRE_EN_PAIEMENT = 'a-mettre-en-paiement';
    const MIS_EN_PAIEMENT      = 'mis-en-paiement';
    
    /**
     * @var \DateTime
     */
    private $dateMiseEnPaiement;

    /**
     * @var \DateTime
     */
    private $dateValidation;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Periode
     */
    private $periodePaiement;

    /**
     * @var float
     */
    private $heures;

    /**
     * @var TypeHeures
     */
    private $typeHeures;

    /**
     * @var \Application\Entity\Db\CentreCout
     */
    private $centreCout;

    /**
     * @var DomaineFonctionnel
     */
    protected $domaineFonctionnel;

    /**
     * @var \Application\Entity\Db\Validation
     */
    private $validation;

    /**
     * @var \Application\Entity\Db\FormuleResultatService
     */
    private $formuleResultatService;

    /**
     * @var \Application\Entity\Db\FormuleResultatServiceReferentiel
     */
    private $formuleResultatServiceReferentiel;

    /**
     * miseEnPaiementIntervenantStructure
     *
     * @var MiseEnPaiementIntervenantStructure
     */
    protected $miseEnPaiementIntervenantStructure;



    public function __construct()
    {
        $this->miseEnPaiementIntervenantStructure = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Set dateMiseEnPaiement
     *
     * @param \DateTime $dateMiseEnPaiement
     *
     * @return MiseEnPaiement
     */
    public function setDateMiseEnPaiement($dateMiseEnPaiement)
    {
        $this->dateMiseEnPaiement = $dateMiseEnPaiement;

        return $this;
    }



    /**
     * Get dateMiseEnPaiement
     *
     * @return \DateTime
     */
    public function getDateMiseEnPaiement()
    {
        return $this->dateMiseEnPaiement;
    }



    /**
     * Set dateValidation
     *
     * @param \DateTime $dateValidation
     *
     * @return MiseEnPaiement
     */
    public function setDateValidation($dateValidation)
    {
        $this->dateValidation = $dateValidation;

        return $this;
    }



    /**
     * Get dateValidation
     *
     * @return \DateTime
     */
    public function getDateValidation()
    {
        return $this->dateValidation;
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
     * Set periodePaiement
     *
     * @param \Application\Entity\Db\Periode $periodePaiement
     *
     * @return MiseEnPaiement
     */
    public function setPeriodePaiement(\Application\Entity\Db\Periode $periodePaiement = null)
    {
        $this->periodePaiement = $periodePaiement;

        return $this;
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
     * Set heures
     *
     * @param float $heures
     *
     * @return MiseEnPaiement
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
     * Set typeHeures
     *
     * @param \OffreFormation\Entity\Db\TypeHeures $typeHeures
     *
     * @return self
     */
    public function setTypeHeures(\OffreFormation\Entity\Db\TypeHeures $typeHeures = null)
    {
        $this->typeHeures = $typeHeures;

        return $this;
    }



    /**
     * Get typeHeures
     *
     * @return \OffreFormation\Entity\Db\TypeHeures
     */
    public function getTypeHeures()
    {
        return $this->typeHeures;
    }



    /**
     * Set centreCout
     *
     * @param \Application\Entity\Db\CentreCout $centreCout
     *
     * @return MiseEnPaiement
     */
    public function setCentreCout(\Application\Entity\Db\CentreCout $centreCout = null)
    {
        $this->centreCout = $centreCout;

        return $this;
    }



    /**
     * Get centreCout
     *
     * @return \Application\Entity\Db\CentreCout
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }



    /**
     *
     * @return DomaineFonctionnel
     */
    function getDomaineFonctionnel()
    {
        return $this->domaineFonctionnel;
    }



    /**
     *
     * @param DomaineFonctionnel $domaineFonctionnel
     *
     * @return self
     */
    function setDomaineFonctionnel(DomaineFonctionnel $domaineFonctionnel)
    {
        $this->domaineFonctionnel = $domaineFonctionnel;

        return $this;
    }



    /**
     * Set validation
     *
     * @param \Application\Entity\Db\Validation $validation
     *
     * @return MiseEnPaiement
     */
    public function setValidation(\Application\Entity\Db\Validation $validation = null)
    {
        $this->validation = $validation;

        return $this;
    }



    /**
     * Get validation
     *
     * @return \Application\Entity\Db\Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }



    /**
     * Set formuleResultatService
     *
     * @param \Application\Entity\Db\FormuleResultatService $formuleResultatService
     *
     * @return MiseEnPaiement
     */
    public function setFormuleResultatService(\Application\Entity\Db\FormuleResultatService $formuleResultatService = null)
    {
        $this->formuleResultatService = $formuleResultatService;

        return $this;
    }



    /**
     * Get formuleResultatService
     *
     * @return \Application\Entity\Db\FormuleResultatService
     */
    public function getFormuleResultatService()
    {
        return $this->formuleResultatService;
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
     *
     * @param ServiceAPayerInterface $serviceAPayer
     *
     * @return self
     */
    public function setServiceAPayer(ServiceAPayerInterface $serviceAPayer = null)
    {
        if ($serviceAPayer instanceof FormuleResultatService) {
            $this->setFormuleResultatService($serviceAPayer);
            $this->setFormuleResultatServiceReferentiel();
        } elseif ($serviceAPayer instanceof FormuleResultatServiceReferentiel) {
            $this->setFormuleResultatService();
            $this->setFormuleResultatServiceReferentiel($serviceAPayer);
        } else {
            $this->setFormuleResultatService();
            $this->setFormuleResultatServiceReferentiel();
        }

        return $this;
    }



    /**
     * Set formuleResultatServiceReferentiel
     *
     * @param \Application\Entity\Db\FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel
     *
     * @return MiseEnPaiement
     */
    public function setFormuleResultatServiceReferentiel(\Application\Entity\Db\FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel = null)
    {
        $this->formuleResultatServiceReferentiel = $formuleResultatServiceReferentiel;

        return $this;
    }



    /**
     * Get formuleResultatServiceReferentiel
     *
     * @return \Application\Entity\Db\FormuleResultatServiceReferentiel
     */
    public function getFormuleResultatServiceReferentiel()
    {
        return $this->formuleResultatServiceReferentiel;
    }



    /**
     * Get miseEnPaiementIntervenantStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiementIntervenantStructure()
    {
        return $this->miseEnPaiementIntervenantStructure;
    }



    /**
     *
     * @return string
     */
    public function getEtat()
    {
        if (!$this->getDateMiseEnPaiement()) return self::A_METTRE_EN_PAIEMENT;

        return self::MIS_EN_PAIEMENT;
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     * @see ResourceInterface
     */
    public function getResourceId()
    {
        return 'MiseEnPaiement';
    }
}
