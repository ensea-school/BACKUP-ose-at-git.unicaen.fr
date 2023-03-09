<?php

namespace Contrat\Form;

use Application\Form\AbstractForm;
use Contrat\Entity\Db\ContratAwareTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Validator\NotEmpty;

/**
 * Formulaire de validation de contrat/avenant.
 *
 */
class ContratValidationForm extends AbstractForm
{
    use ContratAwareTrait;

    public function init2()
    {
        $this->setAttribute('action', $this->getCurrentUrl());
        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setAttribute('method', 'POST');

        $contratToString = lcfirst($this->getContrat()->toString(true));

        $this->add([
            'name'       => 'valide',
            'type'       => 'Checkbox',
            'options'    => [
                'label'              => "Cochez pour valider $contratToString",
                'use_hidden_element' => false,
                'checked_value'      => 1,
                'unchecked_value'    => 0,
            ],
            'attributes' => [
            ],
        ]);

        $this->add(new Csrf('security'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Valider $contratToString",
            ],
        ]);

        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'valide' => [
                'required'   => true,
                'validators' => [
                    [
                        'name'    => 'NotEmpty',
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