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
class EtapeModulateursHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\Etape $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        //$sel = $this->getServiceLocator()->getServiceLocator()->get('applicationElementPedagogique');
        /* @var $sel \Application\Service\ElementPedagogique */

        /*$elements = $etape->getElementPedagogique();
        foreach( $elements as $element ){
            $modulateursListe = $sel->getModulateursListe($element);
        }*/
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
        $sel = $this->getServiceLocator()->get('applicationElementPedagogique');
        /* @var $sel \Application\Service\ElementPedagogique */

        $data = [];

        $elements = $sel->getList( $sel->finderByEtape($object) );
        foreach( $elements as $element ){
            $data['EL'.$element->getId()] = $element;
        }

        return $data;
    }

}