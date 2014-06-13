<?php
namespace Application\Form\OffreFormation;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity\Db\ElementPedagogique;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateursHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  ModulateursListe $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {

        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  ElementPedagogique $object
     * @return array
     */
    public function extract($object)
    {
        $sm   = $this->getServiceLocator()->get('applicationModulateur');
        /* @var $sm \Application\Service\Modulateur */

        $data = array();
        $qb = $sm->finderByElementPedagogique($object);
        $modulateurs = $sm->getList( $qb );
        foreach( $modulateurs as $modulateur ){
            $data[$modulateur->getTypeModulateur()->getCode()] = $modulateur->getCode();
        }
        return $data;
    }

}