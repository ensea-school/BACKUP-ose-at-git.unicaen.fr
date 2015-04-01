<?php

namespace Application\Form\Intervenant;

use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Validator\NotEmpty;

/**
 * Formulaire de validation de contrat/avenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratValidation extends Form implements InputFilterProviderInterface
{
    use \Application\Traits\ContratAwareTrait;

    public function init()
    {
        $this->setHydrator(new ClassMethods(false));
        $this->setAttribute('method', 'POST');

        $contratToString = lcfirst($this->getContrat()->toString(true));

        $this->add([
            'name' => 'valide',
            'type'  => 'Checkbox',
            'options' => [
                'label' => "Cochez pour valider $contratToString",
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0
            ],
            'attributes' => [
            ],
        ]);

        $this->add(new Csrf('security'));

        $this->add([
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => [
                'value' => "Valider $contratToString",
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
        return [
            'valide' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => "Vous devez cocher la case pour valider",
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}