<?php

namespace Application\Form\OffreFormation\EtapeCentreCout;

use Application\Entity\Db\CentreCoutEp;
use Application\Entity\Db\ElementPedagogique;
use Application\Service\CentreCout as CentreCoutService;
use Application\Service\Source as SourceService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ElementCentreCoutFieldsetHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  ElementPedagogique $element
     * @return object
     */
    public function hydrate(array $data, $element)
    {
        $newData = array_filter($data);
        $oldData = array_filter($this->extract($element));
        
        foreach ($element->getTypeHeures() as $th) {
            $code = $th->getCode();
            
            $newCcId = isset($newData[$code]) ? (int)$newData[$code] : null;
            $oldCcId = isset($oldData[$code]) ? (int)$oldData[$code] : null;
            
            $creating = !$oldCcId && $newCcId;
            $updating = $oldCcId && $newCcId && $oldCcId !== $newCcId;
            $deleting = $oldCcId && !$newCcId;
            
//            if ($newCcId || $oldCcId) {
//                var_dump($element->getId(), $oldCcId . "->" . $newCcId, $creating, $updating, $deleting);
//            }

            if ($deleting) {
                $ccEp = $element->getCentreCoutEp($th)->first(); /* @var $ccEp CentreCoutEp */
                $element->removeCentreCoutEp($ccEp);
                $this->getEm()->remove($ccEp);
            }
            elseif ($updating) {
                $ccEp = $element->getCentreCoutEp($th)->first();
                $cc   = $this->getServiceCentreCout()->get($newCcId);
                $ccEp->setCentreCout($cc);
            }
            elseif ($creating) {
                $ccEp = new CentreCoutEp();
                $cc   = $this->getServiceCentreCout()->get($newCcId);
                $ccEp
                        ->setCentreCout($cc)
                        ->setTypeHeures($th)
                        ->setElementPedagogique($element)
                        ->setSource($this->getServiceSource()->getOse())
                        ->setSourceCode(uniqid($cc->getId().'_'.$th->getId().'_'.$element->getId()));
                $this->getEm()->persist($ccEp);
            }
        }
        
        return $element;
    }

    /**
     * Extract values from an object
     *
     * @param  ElementPedagogique $element
     * @return array
     */
    public function extract($element)
    {
        $data = [];

        foreach ($element->getTypeHeures() as $th) {
            if (($ccEp = $element->getCentreCoutEp($th)->first())) {
                $cc = $ccEp->getCentreCout();
                $ccId = $cc->getId();
                $data[$th->getCode()] = $ccId;
            }
//            else {
//                $ccId = '';
//            }
//            $data[$th->getCode()] = $ccId;
        }
        
        return $data;
    }
    
    /**
     * @return EntityManager
     */
    private function getEm()
    {
        return $this->getServiceCentreCout()->getEntityManager();
    }

    /**
     * @return CentreCoutService
     */
    private function getServiceCentreCout()
    {
        return $this->getServiceLocator()->get('applicationCentreCout');
    }

    /**
     * @return SourceService
     */
    private function getServiceSource()
    {
        return $this->getServiceLocator()->get('applicationSource');
    }
}