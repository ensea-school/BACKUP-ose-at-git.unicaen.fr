<?php

namespace Application\Entity\Db;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use OffreFormation\Entity\Db\TypeHeures;
use Paiement\Entity\Db\CentreCout;
use Paiement\Entity\Db\ServiceAPayerInterface;
use Paiement\Entity\Db\ServiceAPayerTrait;

/**
 * FormuleResultatServiceReferentiel
 */
class FormuleResultatServiceReferentiel implements ServiceAPayerInterface, ResourceInterface
{
    use FormuleResultatTypesHeuresTrait;
    use ServiceAPayerTrait;

    /**
     * @var \Referentiel\Entity\Db\ServiceReferentiel
     */
    private $serviceReferentiel;



    /**
     *
     * @param TypeHeures $typeHeures
     *
     * @return CentreCout|null
     */
    public function getDefaultCentreCout(TypeHeures $typeHeures)
    {
        return null; // pas encore de centre de cout par défaut
    }



    /**
     *
     * @return DomaineFonctionnel|null
     */
    public function getDefaultDomaineFonctionnel()
    {
        return $this->getServiceReferentiel()->getFonctionReferentiel()->getDomaineFonctionnel();
    }



    /**
     * @return boolean
     */
    public function isDomaineFonctionnelModifiable()
    {
        return true;
    }



    /**
     * Get ServiceReferentiel
     *
     * @return \Referentiel\Entity\Db\ServiceReferentiel
     */
    public function getServiceReferentiel()
    {
        return $this->serviceReferentiel;
    }



    /**
     * @return Structure
     */
    public function getStructure()
    {
        return $this->getServiceReferentiel()->getStructure();
    }



    /**
     * @return Intervenant
     */
    public function getIntervenant()
    {
        return $this->getServiceReferentiel()->getIntervenant();
    }



    public function getResourceId()
    {
        return 'FormuleResultatServiceReferentiel';
    }
}