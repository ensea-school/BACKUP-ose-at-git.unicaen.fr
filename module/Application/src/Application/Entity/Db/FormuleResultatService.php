<?php

namespace Application\Entity\Db;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * FormuleResultatService
 */
class FormuleResultatService implements ServiceAPayerInterface, ResourceInterface
{
    use FormuleResultatTypesHeuresTrait;
    use ServiceAPayerTrait;

    /**
     * @var \Application\Entity\Db\Service
     */
    private $service;

    /**
     * 
     * @param TypeHeures $typeHeures
     * @return CentreCout|null
     */
    public function getDefaultCentreCout( TypeHeures $typeHeures )
    {
        $element = $this->getService()->getElementPedagogique();
        if (! $element) return null;
        $result = $element->getCentreCoutEp($typeHeures->getTypeHeuresElement());
        if (false == $result) return null;
        $ccep = $result->first();
        if ($ccep instanceof CentreCoutEp){
            return $ccep->getCentreCout();
        }else{
            return null;
        }
    }

    /**
     * Get Service
     *
     * @return \Application\Entity\Db\Service 
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
        if ($service->getElementPedagogique())
            return $service->getElementPedagogique()->getStructure();
        else
            return $service->getIntervenant()->getStructure();
    }

    public function getResourceId()
    {
        return 'FormuleResultatService';
    }
}
