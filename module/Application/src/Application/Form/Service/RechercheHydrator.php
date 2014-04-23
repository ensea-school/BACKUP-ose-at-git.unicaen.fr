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

        $id = isset($data['element-pedagogique']) ? (int)$data['element-pedagogique'] : null;
        $object->elementPedagogique = $id ? $em->find('Application\Entity\Db\ElementPedagogique', $id) : null;

        $id = isset($data['etape']) ? (int)$data['etape'] : null;
        $object->etape = $id ? $em->find('Application\Entity\Db\Etape', $id) : null;

        $id = isset($data['structure-ens']) ? (int)$data['structure-ens'] : null;
        $object->structureEns = $id ? $em->find('Application\Entity\Db\Structure', $id) : null;

        $id = isset($data['intervenant']) ? (int)$data['intervenant'] : null;
        $object->intervenant = $id ? $em->find('Application\Entity\Db\Intervenant', $id) : null;
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
        $data = array(
            'intervenant' => isset($object->intervenant) && $object->intervenant ? $object->intervenant->getId() : null,
            'element-pedagogique' => isset($object->elementPedagogique) && $object->elementPedagogique ? $object->elementPedagogique->getId() : null,
            'etape' => isset($object->etape) && $object->etape ? $object->etape->getId() : null,
            'structure-ens' => isset($object->structureEns) && $object->structureEns ? $object->structureEns->getId() : null,
        );
        return $data;
    }

}