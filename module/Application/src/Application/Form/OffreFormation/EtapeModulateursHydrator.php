<?php
namespace Application\Form\OffreFormation;

use Application\Service\Traits\ElementPedagogiqueAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeModulateursHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;
    use ElementPedagogiqueAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\Etape $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\Etape $object
     * @return array
     */
    public function extract($object)
    {
        $sel = $this->getServiceElementPedagogique();

        $data = [];

        $elements = $sel->getList( $sel->finderByEtape($object) );
        foreach( $elements as $element ){
            $data['EL'.$element->getId()] = $element;
        }

        return $data;
    }

}