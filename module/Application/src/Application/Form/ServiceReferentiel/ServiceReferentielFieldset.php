<?php

namespace Application\Form\ServiceReferentiel;

use Zend\Form\Fieldset;
use Application\Entity\Db\IntervenantPermanent;

/**
 * Description of ServiceReferentiel
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see FonctionServiceReferentielFieldset
 */
class ServiceReferentielFieldset extends Fieldset
{
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setHydrator(new ServiceReferentielTestHydrator());
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'serviceReferentiel',
            'options' => array(
                'label' => 'Ajoutez autant de fonctions que nécessaire...',
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'allow_remove' => true,
                'target_element' => array(
                    'type' => 'Application\Form\ServiceReferentiel\FonctionServiceReferentielFieldset',
                ),
            ),
        ));   
    }
}

class ServiceReferentielTestHydrator implements \Zend\Stdlib\Hydrator\HydratorInterface
{
    /**
     * Extract values from an object
     *
     * @param  IntervenantPermanent $intervenant
     * @return array
     */
    public function extract($intervenant)
    {
        return array(
            'serviceReferentiel' => $intervenant->getServiceReferentiel(),
        );
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  IntervenantPermanent $intervenant
     * @return object
     */
    public function hydrate(array $data, $intervenant)
    {
        if (!($annee = $intervenant->getAnneeCriterion())) {
            throw new \Common\Exception\LogicException("Une année doit être spécifiée comme critère.");
        }
        
        $newServicesReferentiel = $data['serviceReferentiel'];
        $curServicesReferentiel = \Zend\Stdlib\ArrayUtils::iteratorToArray($intervenant->getServiceReferentiel($annee));
        
        // historicisation des services supprimés
        $toRemove = array_diff($curServicesReferentiel, $newServicesReferentiel);
        foreach ($toRemove as $serviceReferentiel) { /* @var $serviceReferentiel \Application\Entity\Db\ServiceReferentiel */
            $serviceReferentiel->setHistoDestruction(new \DateTime());
        }
        
        // insertion des nouveaux services
        foreach ($newServicesReferentiel as $serviceReferentiel) { /* @var $serviceReferentiel \Application\Entity\Db\ServiceReferentiel */
            if (null === $serviceReferentiel->getId()) {
                $intervenant->addServiceReferentiel($serviceReferentiel);
                $serviceReferentiel
                        ->setIntervenant($intervenant)
                        ->setAnnee($annee);
            }
        }
        
        return $intervenant;
    }
}