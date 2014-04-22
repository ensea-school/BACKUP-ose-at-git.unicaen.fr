<?php
namespace Application\Form\Service;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RechercheHydrator implements HydratorInterface, ServiceLocatorAwareInterface
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
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        /* @var $em \Doctrine\ORM\EntityManager */

        $id = (int)$data['elementPedagogique']['element']['id'];
        if ($id){
            $object->elementPedagogique = $em->find('Application\Entity\Db\ElementPedagogique', $id);
        }else{
            $object->elementPedagogique = null;
        }

        $id = (int)$data['intervenant']['id'];
        if ($id){
            $object->intervenant = $em->getRepository('Application\Entity\Db\Intervenant')->findOneBySourceCode($id);
        }else{
            $object->intervenant = null;
        }
        return $object;
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
        return $data;
    }

}