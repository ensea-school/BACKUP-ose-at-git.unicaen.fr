<?php
namespace Application\Form\VolumeHoraireReferentiel;

use Zend\Stdlib\Hydrator\HydratorInterface;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwaretrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity\Db\Service;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieMultipleHydrator implements HydratorInterface, ServiceLocatorAwareInterface, EntityManagerAwareInterface
{

    use ServiceLocatorAwareTrait;
    use EntityManagerAwaretrait;

    /**
     *
     * @return \Application\Service\TypeIntervention[]
     */
    public function getTypesInterventions( Service $service )
    {
        if ($service->getElementPedagogique()){
            return $service->getElementPedagogique()->getTypeIntervention();
        }else{
            return $this->getServiceTypeIntervention()->getTypesIntervention();
    }
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\VolumeHoraireListe $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $typeVolumeHoraire = $this->getEntityManager()->find('Application\Entity\Db\TypeVolumeHoraire', (int)$data['type-volume-horaire']);
        $periode = $this->getEntityManager()->find('Application\Entity\Db\Periode', (int)$data['periode']);

        $object->setTypeVolumeHoraire($typeVolumeHoraire);
        $object->setPeriode($periode);
        foreach( $this->getTypesInterventions($object->getService()) as $typeIntervention ){
            $object->setTypeIntervention($typeIntervention);
            if (isset($data[$typeIntervention->getCode()])){
                $heures = (float)$data[$typeIntervention->getCode()];
            }else{
                $heures = 0;
            }
            $object->setHeures($heures, false);
        }
        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\VolumeHoraireListe $object
     * @return array
     */
    public function extract($object)
    {
        $vhl = $object->getChild();
        $data = [
            'type-volume-horaire' => $object->getTypeVolumeHoraire() ? $object->getTypeVolumeHoraire()->getId() : null,
            'service' => $object->getService() ? $object->getService()->getId() : null,
            'periode' => $object->getPeriode() ? $object->getPeriode()->getId() : null,
        ];
        foreach( $this->getTypesInterventions( $object->getService() ) as $typeIntervention ){
            $vhl->setTypeIntervention($typeIntervention);
            $data[$typeIntervention->getCode()] = $vhl->getHeures();
        }
        return $data;
    }

    /**
     * @return \Application\Service\TypeIntervention
     */
    protected function getServiceTypeIntervention()
    {
        return $this->getServiceLocator()->get('applicationTypeIntervention');
}
}