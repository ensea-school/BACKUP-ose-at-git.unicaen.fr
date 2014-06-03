<?php
namespace Application\Form\VolumeHoraire;

use Zend\Stdlib\Hydrator\HydratorInterface;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwaretrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

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
    public function getTypesInterventions()
    {
        $sti = $this->getServiceLocator()->get('applicationTypeIntervention');
        /* @var $sti \Application\Service\TypeIntervention */
        return $sti->getTypesIntervention();
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
        $em = $this->getEntityManager();

        $service = isset($data['service']) ? $em->getRepository('Application\Entity\Db\Service')->find( $data['service'] ) : null;
        $periode = isset($data['periode']) ? $em->getRepository('Application\Entity\Db\Periode')->find( $data['periode'] ) : null;

        foreach( $this->getTypesInterventions() as $typeIntervention ){
            if (isset($data[$typeIntervention->getCode()])){
                $heures = (int)$data[$typeIntervention->getCode()];
            }else{
                $heures = 0;
            }

            $volumeHoraire = $object->getWithTypeIntervention($typeIntervention);
            if ($heures && $volumeHoraire){
                $volumeHoraire->setHeures($heures);
            }elseif( $heures && ! $volumeHoraire){
                $volumeHoraire = $this->getServiceLocator()->get('applicationVolumeHoraire')->newEntity();
                $volumeHoraire->setPeriode($periode);
                $volumeHoraire->setTypeIntervention($typeIntervention);
                $volumeHoraire->setHeures($heures);
                $object->add($volumeHoraire);
            }elseif( ! $heures && $volumeHoraire ){
                $object->remove($volumeHoraire);
            }
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
        $data = array(
            'service' => $object->getService() ? $object->getService()->getId() : null,
            'periode' => $object->getPeriode() ? $object->getPeriode()->getId() : null,
        );

        foreach( $object->get() as $volumeHoraire ){
            $data[$volumeHoraire->getTypeIntervention()->getCode()] = $volumeHoraire->getHeures();
        }
        return $data;
    }
}