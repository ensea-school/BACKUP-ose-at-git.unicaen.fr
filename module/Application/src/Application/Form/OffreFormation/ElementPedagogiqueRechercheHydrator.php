<?php
namespace Application\Form\OffreFormation;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueRechercheHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $id = (int)$data['element']['id'];
        if ($id){
            $object = $this->getServiceElementPedagogique()->get($id);
            return $object;
        }
        return null;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\ElementPedagogique $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];

        $data['element'] = [
            'id'    => $object ? $object->getId() : null,
            'label' => $object ? $object->getLibelle() : null,
        ];

        $etape = $object ? $object->getEtape() : null;
        if ($etape){
            $data['etape'] = $etape->getId();
        }
        $structure = $object ? $object->getStructure() : null;
        if ($structure){
            $data['structure'] = $structure->getId();
        }

        return $data;
    }

    /**
     * @return \Application\Service\ElementPedagogique
     */
    protected function getServiceElementPedagogique()
    {
        return $this->getServiceLocator()->get('applicationElementPedagogique');
    }
}