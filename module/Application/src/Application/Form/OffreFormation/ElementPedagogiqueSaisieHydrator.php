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
        $object->setTauxFoad((float)$data['taux-foad']);
        $object->setTauxFc((float)$data['taux-fc']);
        $object->setTauxFi((float)$data['taux-fi']);
        $object->setTauxFa((float)$data['taux-fa']);
        $object->setFc((float)$data['taux-fc'] > 0);
        $object->setFi((float)$data['taux-fi'] > 0);
        $object->setFa((float)$data['taux-fa'] > 0);
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
            'taux-fc'        => $object->getTauxFc(),
            'taux-fi'        => $object->getTauxFi(),
            'taux-fa'        => $object->getTauxFa(),
        );
        return $data;
    }
}