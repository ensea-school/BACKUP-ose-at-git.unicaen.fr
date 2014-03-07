<?php

namespace Application\Form\ServiceReferentiel;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element\Csrf;

/**
 * Description of AjouterModifier
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see ServiceReferentielFieldset
 * @see FonctionServiceReferentielFieldset
 */
class AjouterModifier extends Form
{
    /**
     * @var \Application\Entity\Db\Annee
     */
    protected $annee;
    
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
        
        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'service-referentiel')
                ->setHydrator(new \Zend\Stdlib\Hydrator\ClassMethods(false))
                ->setInputFilter(new InputFilter())
//                ->setPreferFormInputFilter(false)
         ;
        
        $this->add(array(
            'name' => 'intervenant',
            'type' => 'Application\Form\ServiceReferentiel\ServiceReferentielFieldset',
            'options' => array(
                'use_as_base_fieldset' => true,
            ),
        ));
        
        $this->add(array(
            'type' => 'Button',
            'name' => 'ajouter',
            'options' => array(
                'label' => 'Ajouter',
            ),
            'attributes' => array(
                'title' => "Ajouter une fonction",
                'class' => 'fonction-referentiel fonction-referentiel-ajouter btn btn-default btn-xs'
            ),
        ));
         
        /**
         * Csrf
         */
        $this->add(new Csrf('security'));
        
        /**
         * Submit
         */
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
            ),
        ));
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