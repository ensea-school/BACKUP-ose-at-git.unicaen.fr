<?php

namespace Application\Entity\Db;

use Laminas\Permissions\Acl\Resource\ResourceInterface;

/**
 * FormuleResultatServiceReferentiel
 */
class FormuleResultatServiceReferentiel implements ServiceAPayerInterface, ResourceInterface
{
    use FormuleResultatTypesHeuresTrait;
    use ServiceAPayerTrait;

    /**
     * @var \Application\Entity\Db\ServiceReferentiel
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
        return $this->getServiceReferentiel()->getFonction()->getDomaineFonctionnel();
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
     * @return \Application\Entity\Db\ServiceReferentiel
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