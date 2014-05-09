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
class EtapeSaisieHydrator implements HydratorInterface, ServiceLocatorAwareInterface
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
        $object->setSourceCode( $data['source-code'] );
        $object->setLibelle( $data['libelle'] );
        $object->setTypeFormation( $this->getServiceLocator()->get('ApplicationTypeFormation')->get( $data['type-formation'] ) );
        if (array_key_exists('niveau',$data)){
            $object->setNiveau( $data['niveau'] );
        }
        $object->setSpecifiqueEchanges( $data['specifique-echanges'] );
        $object->setStructure( $this->getServiceLocator()->get('ApplicationStructure')->get( $data['structure'] ) );
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
        $data = array(
            'source-code'           => $object->getSourceCode(),
            'libelle'               => $object->getLibelle(),
            'id'                    => $object->getId(),
            'type-formation'        => ($tf = $object->getTypeFormation()) ? $tf->getId() : null,
            'niveau'                => $object->getNiveau(),
            'specifique-echanges'   => $object->getSpecifiqueEchanges(),
            'structure'             => ($s = $object->getStructure()) ? $s->getId() : null,
        );
        return $data;
    }

}