<?php

namespace Application\Form\VolumeHoraire;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Form\Element\Hidden;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of Saisie
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Saisie extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    
    /**
     * 
     */
    public function init()
    {
        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'volume-horaire')
//                ->setHydrator(new ClassMethods(false))
//                ->setInputFilter(new InputFilter())
//                ->setPreferFormInputFilter(false)
         ;

        $this->add(array(
            'name'       => 'heures',
            'options'    => array(
                'label' => "Nombre d'heures :",
            ),
            'attributes' => array(
                'value' => "0",
                'title' => "Nombre d'heures",
                'class' => 'volume-horaire volume-horaire-heures input-sm'
            ),
            'type'       => 'Text',
        ));

        $this->add(array(
            'name' => 'motifNonPaiement',
            'options'    => array(
                'label' => "Motif de non paiement :",
            ),
            'attributes' => array(
                'value' => "",
                'title' => "Motif de non paiement",
                'class' => 'volume-horaire volume-horaire-motif-non-paiement input-sm'
            ),
            'type' => 'Select'
        ));

        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\DbRole) {
            $motifsNonPaiement = $this->getServiceLocator()->getServiceLocator()->get('ApplicationMotifNonPaiement')
                    ->getMotifsNonPaiement();
            foreach( $motifsNonPaiement as $id => $motifNonPaiement ){
                $motifsNonPaiement[$id] = (string)$motifNonPaiement;
            }
            $motifsNonPaiement[0] = 'Aucun motif : paiement prévu';
            $this->get('motifNonPaiement')->setValueOptions( $motifsNonPaiement );
        }
        else {
            $this->remove('motifNonPaiement');
        }
        
        $this->add( new Hidden('id') );
        $this->add( new Hidden('service') );
        $this->add( new Hidden('periode') );
        $this->add( new Hidden('typeIntervention') );

        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
                'title' => "Enregistrer",
                'class' => 'volume-horaire volume-horaire-enregistrer btn btn-primary'
            ),
        ));
        
        $this->add(array(
            'name' => 'annuler',
            'type' => 'Button',
            'options' => array(
                'label' => 'Fermer',
            ),
            'attributes' => array(
                'title' => "Abandonner cette saisie",
                'class' => 'volume-horaire volume-horaire-annuler btn btn-default fermer'
            ),
        ));
    }

    /* Associe une entity VolumeHoraire au formulaire */
    public function bind( $object, $flags=17)
    {
        /* @var $object \Application\Entity\Db\VolumeHoraire */
        $data = array(
            'id'               => $object->getId(),
            'heures'           => $object->getHeures(),
            'motifNonPaiement' => $object->getMotifNonPaiement() ? $object->getMotifNonPaiement()->getId() : 0,
            'service'          => $object->getService() ? $object->getService()->getId() : null,
            'periode'          => $object->getPeriode() ? $object->getPeriode()->getId() : 0,
            'typeIntervention' => $object->getTypeIntervention() ? $object->getTypeIntervention()->getId() : null,
        );
        $this->setData($data);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'motifNonPaiement' => array(
                'required' => false
            ),
            'periode' => array(
                'required' => false
            ),
        );
    }

}