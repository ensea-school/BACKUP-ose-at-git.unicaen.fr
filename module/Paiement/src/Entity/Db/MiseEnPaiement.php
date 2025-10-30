<?php

namespace Paiement\Entity\Db;

use Application\Entity\Db\Periode;
use Doctrine\Common\Collections\Collection;
use Enseignement\Entity\Db\Service;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Mission\Entity\Db\Mission;
use OffreFormation\Entity\Db\TypeHeures;
use Referentiel\Entity\Db\ServiceReferentiel;
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

    private ?Service $service = null;

    private ?ServiceReferentiel $serviceReferentiel = null;

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



    public function getService(): ?Service
    {
        return $this->service;
    }



    public function setService(?Service $service): MiseEnPaiement
    {
        $this->service = $service;
        return $this;
    }



    public function getServiceReferentiel(): ?ServiceReferentiel
    {
        return $this->serviceReferentiel;
    }



    public function setServiceReferentiel(?ServiceReferentiel $serviceReferentiel): MiseEnPaiement
    {
        $this->serviceReferentiel = $serviceReferentiel;
        return $this;
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



    public function getEtat(): string
    {
        if (!$this->getDateMiseEnPaiement()) return self::A_METTRE_EN_PAIEMENT;

        return self::MIS_EN_PAIEMENT;
    }



    public function getResourceId(): string
    {
        return self::class;
    }
}
