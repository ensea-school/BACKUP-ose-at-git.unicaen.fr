<?php

namespace Application\Form\Intervenant;

use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Validator\NotEmpty;

/**
 * Formulaire de validation des enseignements d'un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ServiceValidation extends Form implements InputFilterProviderInterface
{
    use \Application\Traits\IntervenantAwareTrait;
    
    public function init()
    {
        $this->setHydrator(new ClassMethods(false));
        
        $this->setAttribute('method', 'POST');
        $this->add(array(
            'name' => 'valide',
            'type'  => 'Checkbox',
            'options' => array(
                'label' => "Cochez pour valider",
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0
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
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => "Vous devez cocher la case pour valider",
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
}