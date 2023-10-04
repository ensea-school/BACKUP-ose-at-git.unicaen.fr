<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Enseignement\Entity\Db\Service;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\CentreCoutEp;
use OffreFormation\Entity\Db\TypeHeures;
use OffreFormation\Service\DomaineFonctionnelService;
use Paiement\Entity\Db\CentreCout;
use Paiement\Entity\Db\ServiceAPayerInterface;
use Paiement\Entity\Db\ServiceAPayerTrait;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;

/**
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleResultatService implements ServiceAPayerInterface, ResourceInterface
{
    use FormuleResultatTypesHeuresTrait;
    use ServiceAPayerTrait;

    private FormuleResultat $formuleResultat;

    private Service $service;



    public function __construct()
    {
        $this->miseEnPaiement = new ArrayCollection();
        $this->centreCout = new ArrayCollection();
    }



    public function getDefaultCentreCout(TypeHeures $typeHeures): ?CentreCout
    {
        $element = $this->getService()->getElementPedagogique();
        if (!$element) return null;
        $result = $element->getCentreCoutEp($typeHeures->getTypeHeuresElement());
        if (false == $result) return null;
        $ccep = $result->first();
        if ($ccep instanceof CentreCoutEp) {
            return $ccep->getCentreCout();
        } else {
            return null;
        }
    }



    public function getDefaultDomaineFonctionnel(DomaineFonctionnelService $serviceDomaineFonctionnel = null): ?DomaineFonctionnel
    {
        $element = $this->getService()->getElementPedagogique();
        if (!$element) {
            if (!$serviceDomaineFonctionnel) {
                throw new \LogicException('Le service DomaineFonctionnel doit être fourni pour que le domaine fonctionnel par défaut soit identifié');
            }

            return $serviceDomaineFonctionnel->getForServiceExterieur();
        }

        return $element->getEtape()->getDomaineFonctionnel();
    }



    public function isDomaineFonctionnelModifiable(): bool
    {
        return $this->getService()->getElementPedagogique() === null;
    }



    public function getService(): Service
    {
        return $this->service;
    }



    public function getStructure(): ?Structure
    {
        $service = $this->getService();
        if ($service->getElementPedagogique()) {
            return $service->getElementPedagogique()->getStructure();
        } else {
            return $service->getIntervenant()->getStructure();
        }
    }



    public function getIntervenant(): ?Intervenant
    {
        return $this->getService()->getIntervenant();
    }



    public function getFormuleResultat(): FormuleResultat
    {
        return $this->formuleResultat;
    }



    public function isPayable(): bool
    {
        $fr = $this->getFormuleResultat();

        return $fr->getTypeVolumeHoraire()->getCode() === TypeVolumeHoraire::CODE_REALISE
            && $fr->getEtatVolumeHoraire()->getCode() === EtatVolumeHoraire::CODE_VALIDE;
    }



    public function getHeuresMission(): float
    {
        return 0;
    }



    public function getResourceId()
    {
        return 'FormuleResultatService';
    }
}