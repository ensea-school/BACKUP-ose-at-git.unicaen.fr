<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\TypeHeures;
use Paiement\Entity\Db\CentreCout;
use Paiement\Entity\Db\ServiceAPayerInterface;
use Paiement\Entity\Db\ServiceAPayerTrait;
use Referentiel\Entity\Db\ServiceReferentiel;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;

/**
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleResultatServiceReferentiel implements ServiceAPayerInterface, ResourceInterface
{
    use FormuleResultatTypesHeuresTrait;
    use ServiceAPayerTrait;

    private FormuleResultat $formuleResultat;

    private ServiceReferentiel $serviceReferentiel;



    public function __construct()
    {
        $this->miseEnPaiement = new ArrayCollection();
        $this->centreCout = new ArrayCollection();
    }



    public function getDefaultCentreCout(TypeHeures $typeHeures): ?CentreCout
    {
        return null; // pas encore de centre de cout par défaut
    }



    public function getDefaultDomaineFonctionnel(): ?DomaineFonctionnel
    {
        return $this->getServiceReferentiel()->getFonctionReferentiel()->getDomaineFonctionnel();
    }



    public function isDomaineFonctionnelModifiable(): bool
    {
        return true;
    }



    public function getServiceReferentiel(): ServiceReferentiel
    {
        return $this->serviceReferentiel;
    }



    public function getStructure(): ?Structure
    {
        return $this->getServiceReferentiel()->getStructure();
    }



    public function getIntervenant(): ?Intervenant
    {
        return $this->getServiceReferentiel()->getIntervenant();
    }



    public function getFormuleResultat(): FormuleResultat
    {
        return $this->formuleResultat;
    }



    public function getHeuresMission(): float
    {
        return 0;
    }



    public function isPayable(): bool
    {
        $fr = $this->getFormuleResultat();

        return $fr->getTypeVolumeHoraire()->getCode() === TypeVolumeHoraire::CODE_REALISE
            && $fr->getEtatVolumeHoraire()->getCode() === EtatVolumeHoraire::CODE_VALIDE;
    }



    public function getResourceId()
    {
        return 'FormuleResultatServiceReferentiel';
    }
}