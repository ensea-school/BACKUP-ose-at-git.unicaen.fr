<?php

namespace Application\Form\OffreFormation\EtapeCentreCout;

use Application\Entity\Db\Etape;
use Application\Service\ElementPedagogique as ElementPedagogiqueService;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EtapeCentreCoutFormHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  Etape $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  Etape $object
     * @return array
     */
    public function extract($object)
    {
        $sel = $this->getServiceLocator()->get('applicationElementPedagogique'); /* @var $sel ElementPedagogiqueService */

        $data = array();

        $elements = $sel->getList($sel->finderByEtape($object));
        foreach ($elements as $element) {
            $data['EL' . $element->getId()] = $element;
        }

        return $data;
    }
}