<?php

namespace Application\Form\Agrement;

use Application\Constants;
use Application\Form\AbstractForm;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Hydrator\HydratorInterface;
use UnicaenApp\Hydrator\Strategy\DateStrategy;

/**
 * Formulaire de saisie d'un agrément.
 *
 */
class Saisie extends AbstractForm
{

    public function init()
    {
        $this->setHydrator(new AgreementRetourFormHydrator());

        $this->setAttribute('action', $this->getCurrentUrl());


        $this->add([
            'name'       => 'dateDecision',
            'type'       => 'DateTime',
            'options'    => [
                'label' => "Date de la décision",
                'format' => Constants::DATE_FORMAT,
            ],
            'attributes' => [
                'id' => uniqid('dateDecision'),
            ],
        ]);
//        $this->getHydrator()->addStrategy('dateDecision', new DateStrategy($this->get('dateDecision')));

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
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
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

class AgreementRetourFormHydrator implements HydratorInterface
{

    /**
     * @param array $data
     * @param       $object
     */
    public function hydrate(array $data, $object)
    {
        $object->setDateDecision($data['dateDecision'] ? \DateTime::createFromFormat(Constants::DATE_FORMAT, $data['dateDecision']) : null);
    }



    /**
     *
     * @param $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'dateDecision' => $object->getDateDecision() ? $object->getDateDecision()->format(Constants::DATE_FORMAT) : null,
        ];

        return $data;
    }
}