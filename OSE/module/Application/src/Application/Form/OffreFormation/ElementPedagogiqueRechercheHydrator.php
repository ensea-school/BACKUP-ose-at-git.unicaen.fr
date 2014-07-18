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
            $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            /* @var $em \Doctrine\ORM\EntityManager */
            $object = $em->find('Application\Entity\Db\ElementPedagogique', $id);
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
        $data = array();

        $data['element'] = array(
            'id'    => $object ? $object->getId() : null,
            'label' => $object ? $object->getLibelle() : null,
        );

        return $data;
    }

}