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
class ElementPedagogiqueSaisieHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\ElementPedagogique $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setSourceCode($data['source-code']);
        $object->setLibelle($data['libelle']);
//        $object->setEtape($this->getServiceLocator()->get('ApplicationEtape')->get($data['etape']));
        $object->setPeriode($this->getServiceLocator()->get('ApplicationPeriode')->get($data['periode']));
        $object->setTauxFoad($data['taux-foad']);
        $object->setFc((boolean)$data['fc']);
        $object->setFi((boolean)$data['fi']);
        $object->setFa((boolean)$data['fa']);
//        $object->setStructure($this->getServiceLocator()->get('ApplicationStructure')->get($data['structure']));

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
            'etape'          => ($e = $object->getEtape()) ? $e->getId() : null,
            'source-code'    => $object->getSourceCode(),
            'libelle'        => $object->getLibelle(),
            'id'             => $object->getId(),
            'periode'        => ($tf              = $object->getPeriode()) ? $tf->getId() : null,
            'taux-foad'      => $object->getTauxFoad(),
            'structure'      => ($s               = $object->getStructure()) ? $s->getId() : null,
            'fc'             => $object->getFc(),
            'fi'             => $object->getFi(),
            'fa'             => $object->getFa(),
        );
        return $data;
    }
}