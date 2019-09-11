<?php

namespace Application\Form\Agrement;

use Application\Form\AbstractForm;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\ClassMethods;
use UnicaenApp\Hydrator\Strategy\DateStrategy;

/**
 * Formulaire de saisie d'un agrément.
 *
 */
class Saisie extends AbstractForm
{

    public function init()
    {
        $this->setHydrator(new ClassMethods(false));

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'       => 'dateDecision',
            'type'       => 'UnicaenApp\Form\Element\Date',
            'options'    => [
                'label' => "Date de la décision",
            ],
            'attributes' => [
                'id' => uniqid('dateDecision'),
            ],
        ]);
        $this->getHydrator()->addStrategy('dateDecision', new DateStrategy($this->get('dateDecision')));

        $this->add(new Csrf('security'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
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
        return [
            'dateDecision' => [
                'required' => true,
            ],
        ];
    }
}