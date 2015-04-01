<?php
namespace Application\Form\Service;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwaretrait;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieHydrator implements HydratorInterface, EntityManagerAwareInterface, ServiceLocatorAwareInterface
{

    use EntityManagerAwaretrait;
    use ServiceLocatorAwareTrait;

    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @return \Application\Entity\Db\Periode[]
     */
    public function getPeriodes()
    {
        $servicePeriode = $this->getServiceLocator()->get('applicationPeriode');
        /* @var $servicePeriode \Application\Service\Periode */
        $periodes = $servicePeriode->getEnseignement();
        return $periodes;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\Service $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
         $object = $data['service'];
        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\Service $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];
        $data['service'] = $object;
        foreach( $this->getPeriodes() as $periode ){
            $data[$periode->getCode()] = $object->getVolumeHoraireListe($periode);
        }
        return $data;
    }
}