<?php

namespace Application\Entity\Db;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use OffreFormation\Entity\Db\CentreCoutEp;
use OffreFormation\Entity\Db\TypeHeures;
use OffreFormation\Service\DomaineFonctionnelService;

/**
 * FormuleResultatService
 */
class FormuleResultatService implements ServiceAPayerInterface, ResourceInterface
{
    use FormuleResultatTypesHeuresTrait;
    use ServiceAPayerTrait;

    /**
     * @var \Enseignement\Entity\Db\Service
     */
    private $service;



    /**
     *
     * @param TypeHeures $typeHeures
     *
     * @return CentreCout|null
     */
    public function getDefaultCentreCout(TypeHeures $typeHeures)
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



    /**
     *
     * @return DomaineFonctionnel|null
     */
    public function getDefaultDomaineFonctionnel(DomaineFonctionnelService $serviceDomaineFonctionnel = null)
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



    /**
     * @return boolean
     */
    public function isDomaineFonctionnelModifiable()
    {
        return $this->getService()->getElementPedagogique() === null;
    }



    /**
     * Get Service
     *
     * @return \Enseignement\Entity\Db\Service
     */
    public function getService()
    {
        return $this->service;
    }



    /**
     * @return Structure
     */
    public function getStructure()
    {
        $service = $this->getService();
        if ($service->getElementPedagogique()) {
            return $service->getElementPedagogique()->getStructure();
        } else {
            return $service->getIntervenant()->getStructure();
        }
    }



    /**
     * @return Intervenant
     */
    public function getIntervenant()
    {
        return $this->getService()->getIntervenant();
    }



    public function getResourceId()
    {
        return 'FormuleResultatService';
    }
}