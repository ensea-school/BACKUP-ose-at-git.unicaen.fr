<?php

namespace Application\Form\Intervenant;

use Application\Form\AbstractForm;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Validator\Identical;
use Application\Entity\Db\Validation;

/**
 * Formulaire de validation des données personnelles d'un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierValidation extends AbstractForm
{
    public function init()
    {
        $this->setObject(new Validation());
        $this->setHydrator(new ClassMethods(false));

        $this->setAttribute('method', 'POST');
        $this->add([
            'name' => 'valide',
            'type'  => 'Checkbox',
            'options' => [
                'label' => "Cochez pour valider les données personnelles",
            ],
            'attributes' => [
            ],
        ]);

        $this->add(new Csrf('security'));
        $this->add([
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
            ],
        ]);

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
        $validationExiste = (bool) $this->getObject()->getId();
        
        $validatorsValide   = [];
        $validatorsValide[] = new Identical([
            'token' => $validationExiste ? '0' : '1', 
            'messageTemplates' => [
                Identical::NOT_SAME => $validationExiste ? "Pour dévalider, vous devez décocher la case" : "Pour valider, vous devez cocher la case",
            ],
        ]);
        
        return [
            'valide' => [
                'required'   => false,
                'validators' => $validatorsValide,
            ],
        ];
    }
}