<?php

namespace Application\Form\VolumeHoraire;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Description of Saisie
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Saisie extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
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
         
        $this->add(new Csrf('security'));
        
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
                'title' => "Enregistrer ce volume horaire",
                'class' => 'volume-horaire volume-horaire-enregistrer btn btn-primary btn-xs'
            ),
        ));
        
        $this->add(array(
            'name' => 'annuler',
            'type' => 'Button',
            'options' => array(
                'label' => 'Annuler',
            ),
            'attributes' => array(
                'title' => "Abandonner cette saisie",
                'class' => 'volume-horaire volume-horaire-annuler btn btn-primary btn-xs'
            ),
        ));
    }
    


}