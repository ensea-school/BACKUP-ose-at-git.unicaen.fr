<?php

namespace Paiement\Entity\Db;

use Application\Entity\Db\DomaineFonctionnel;
use Application\Entity\Db\Periode;
use Application\Entity\Db\Validation;
use Doctrine\Common\Collections\Collection;
use Formule\Entity\Db\FormuleResultatService;
use Formule\Entity\Db\FormuleResultatServiceReferentiel;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Mission\Entity\Db\Mission;
use OffreFormation\Entity\Db\TypeHeures;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class MiseEnPaiement implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    const A_METTRE_EN_PAIEMENT = 'a-mettre-en-paiement';
    const MIS_EN_PAIEMENT      = 'mis-en-paiement';

    private ?\DateTime $dateMiseEnPaiement = null;

    private ?\DateTime $dateValidation = null;

    private ?int $id = null;

    private ?Periode $periodePaiement = null;

    private float $heures = 0;

    private ?TypeHeures $typeHeures = null;

    private ?CentreCout $centreCout = null;

    private ?DomaineFonctionnel $domaineFonctionnel = null;

    private ?Validation $validation = null;

    private ?FormuleResultatService $formuleResultatService = null;

    private ?FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel = null;

    private ?Mission $mission = null;

    private Collection $miseEnPaiementIntervenantStructure;



    public function __construct()
    {
        $this->miseEnPaiementIntervenantStructure = new \Doctrine\Common\Collections\ArrayCollection();
    }



    public function setDateMiseEnPaiement(?\DateTime $dateMiseEnPaiement): self
    {
        $this->dateMiseEnPaiement = $dateMiseEnPaiement;

        return $this;
    }



    public function getDateMiseEnPaiement(): ?\DateTime
    {
        return $this->dateMiseEnPaiement;
    }



    public function setDateValidation(?\DateTime $dateValidation): self
    {
        $this->dateValidation = $dateValidation;

        return $this;
    }



    public function getDateValidation(): ?\DateTime
    {
        return $this->dateValidation;
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function setPeriodePaiement(?Periode $periodePaiement = null): self
    {
        $this->periodePaiement = $periodePaiement;

        return $this;
    }



    public function getPeriodePaiement(): ?Periode
    {
        return $this->periodePaiement;
    }



    public function setHeures(float $heures): self
    {
        $this->heures = $heures;

        return $this;
    }



    public function getHeures(): float
    {
        return $this->heures;
    }



    public function setTypeHeures(?TypeHeures $typeHeures = null): self
    {
        $this->typeHeures = $typeHeures;

        return $this;
    }



    public function getTypeHeures(): ?TypeHeures
    {
        return $this->typeHeures;
    }



    public function setCentreCout(?CentreCout $centreCout = null): self
    {
        $this->centreCout = $centreCout;

        return $this;
    }



    public function getCentreCout(): ?CentreCout
    {
        return $this->centreCout;
    }



    function getDomaineFonctionnel(): ?DomaineFonctionnel
    {
        return $this->domaineFonctionnel;
    }



    function setDomaineFonctionnel(?DomaineFonctionnel $domaineFonctionnel): self
    {
        $this->domaineFonctionnel = $domaineFonctionnel;

        return $this;
    }



    public function setValidation(?Validation $validation = null): self
    {
        $this->validation = $validation;

        return $this;
    }



    public function getValidation(): ?Validation
    {
        return $this->validation;
    }



    public function setFormuleResultatService(?FormuleResultatService $formuleResultatService = null): self
    {
        $this->formuleResultatService = $formuleResultatService;

        return $this;
    }



    public function getFormuleResultatService(): ?FormuleResultatService
    {
        return $this->formuleResultatService;
    }



    public function getServiceAPayer(): ?ServiceAPayerInterface
    {
        if ($this->formuleResultatService) return $this->formuleResultatService;
        if ($this->formuleResultatServiceReferentiel) return $this->formuleResultatServiceReferentiel;
        if ($this->mission) return $this->mission;

        return null;
    }



    public function setServiceAPayer(ServiceAPayerInterface $serviceAPayer = null): self
    {
        if ($serviceAPayer instanceof FormuleResultatService) {
            $this->setFormuleResultatService($serviceAPayer);
            $this->setFormuleResultatServiceReferentiel();
            $this->setMission();
        } elseif ($serviceAPayer instanceof FormuleResultatServiceReferentiel) {
            $this->setFormuleResultatService();
            $this->setFormuleResultatServiceReferentiel($serviceAPayer);
            $this->setMission();
        } elseif ($serviceAPayer instanceof Mission) {
            $this->setFormuleResultatService();
            $this->setFormuleResultatServiceReferentiel();
            $this->setMission($serviceAPayer);
        } else {
            $this->setFormuleResultatService();
            $this->setFormuleResultatServiceReferentiel();
            $this->setMission();
        }

        return $this;
    }



    public function setFormuleResultatServiceReferentiel(?FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel = null): self
    {
        $this->formuleResultatServiceReferentiel = $formuleResultatServiceReferentiel;

        return $this;
    }



    public function getFormuleResultatServiceReferentiel(): ?FormuleResultatServiceReferentiel
    {
        return $this->formuleResultatServiceReferentiel;
    }



    public function getMission(): ?Mission
    {
        return $this->mission;
    }



    public function setMission(?Mission $mission = null): MiseEnPaiement
    {
        $this->mission = $mission;
        return $this;
    }



    /**
     * @return Collection|MiseEnPaiementIntervenantStructure[]
     */
    public function getMiseEnPaiementIntervenantStructure(): Collection
    {
        return $this->miseEnPaiementIntervenantStructure;
    }



    public function getEtat(): string
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
