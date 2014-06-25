<?php

namespace Application\Form\Intervenant;

use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Formulaire de validation des données personnelles d'un intervenant vacataire non-BIATSS.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierValidation extends Form implements InputFilterProviderInterface
{
    private $intervenant;
    
    public function setIntervenant($intervenant)
    {
        $this->intervenant = $intervenant;
        return $this;
    }
    
    public function init()
    {
        $this->setAttribute('method', 'POST');
        $this->add(array(
            'name' => 'valide',
            'type'  => 'Checkbox',
            'options' => array(
                'label' => "Cochez pour valider les données personnelles",
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(new Csrf('security'));
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => "Enregistrer",
            ),
        ));

        $h = new ClassMethods(false);
        $this->setHydrator($h);
        
        return $this;
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
            'valide' => array(
                'required' => false,
            ),
        );
    }
}