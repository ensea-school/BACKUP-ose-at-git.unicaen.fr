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
     * @param  null|int|string  $name    Optional name for the element
     * @param  array            $options Optional options for the element
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
        $this->setHydrator(new ServiceReferentielHydrator())
                ->setObject(new IntervenantPermanent());
        
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

class ServiceReferentielHydrator implements \Zend\Stdlib\Hydrator\HydratorInterface
{
    /**
     * @var \Application\Entity\Db\Annee
     */
    protected $annee;
    
    /**
     * Extract values from an object
     *
     * @param  IntervenantPermanent $intervenant
     * @return array
     */
    public function extract($intervenant)
    {
        if (!$this->annee) {
            throw new \Common\Exception\LogicException("Aucune année spécifiée.");
        }
        
        return array(
            'serviceReferentiel' => $intervenant->getServiceReferentiel($this->annee),
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
        if (!$this->annee) {
            throw new \Common\Exception\LogicException("Aucune année spécifiée.");
        }

        $newServicesReferentiel = $data['serviceReferentiel'];
        $curServicesReferentiel = \Zend\Stdlib\ArrayUtils::iteratorToArray($intervenant->getServiceReferentiel($this->annee));
        
//        foreach ($newServicesReferentiel as $serviceReferentiel) { /* @var $serviceReferentiel \Application\Entity\Db\ServiceReferentiel */
//            var_dump("SR posté : " . $serviceReferentiel . " {$serviceReferentiel->getId()} (Fonction {$serviceReferentiel->getFonction()->getId()})");
//        }
//        foreach ($curServicesReferentiel as $serviceReferentiel) { /* @var $serviceReferentiel \Application\Entity\Db\ServiceReferentiel */
//            var_dump("SR existant : " . $serviceReferentiel . " {$serviceReferentiel->getId()} (Fonction {$serviceReferentiel->getFonction()->getId()})");
//        }
        
        // historicisation des services supprimés
        $toRemove = array_diff($curServicesReferentiel, $newServicesReferentiel);
        foreach ($toRemove as $serviceReferentiel) { /* @var $serviceReferentiel \Application\Entity\Db\ServiceReferentiel */
            $serviceReferentiel->setHistoDestruction(new \DateTime());
        }
//        foreach ($toRemove as $serviceReferentiel) { /* @var $serviceReferentiel \Application\Entity\Db\ServiceReferentiel */
//            var_dump("SR a suppr : " . $serviceReferentiel . " {$serviceReferentiel->getId()} (Fonction {$serviceReferentiel->getFonction()->getId()})");
//        }
        
        // insertion des nouveaux services
        foreach ($newServicesReferentiel as $serviceReferentiel) { /* @var $serviceReferentiel \Application\Entity\Db\ServiceReferentiel */
            if (null === $serviceReferentiel->getId()) {
                $intervenant->addServiceReferentiel($serviceReferentiel); 
                $serviceReferentiel
                        ->setIntervenant($intervenant)
                        ->setAnnee($this->annee);
//                var_dump("SR a ajout : " . $serviceReferentiel . " {$serviceReferentiel->getId()} (Fonction {$serviceReferentiel->getFonction()->getId()})");
            }
        }
        
        return $intervenant;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Annee $annee
     * @return \Application\Form\ServiceReferentiel\AjouterModifier
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee)
    {
        $this->annee = $annee;
        
        return $this;
    }
}