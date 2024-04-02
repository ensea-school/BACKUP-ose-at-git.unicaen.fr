<?php

namespace Application\Form\Periode;

use Application\Filter\FloatFromString;
use Application\Form\AbstractForm;
use Application\Hydrator\GenericHydrator;
use Application\Service\Traits\SourceServiceAwareTrait;
use Laminas\Form\Element\Csrf;


/**
 * Description of PeriodeForm
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class PeriodeSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;

    protected $spec = [
        'id'                => ['hydrator' => ['type' => 'int']],
        'libelleCourt'      => ['hydrator' => ['type' => 'string']],
        'libelleLong'       => ['hydrator' => ['type' => 'string']],
        'code'              => ['hydrator' => ['type' => 'string']],
        'ordre'             => ['hydrator' => ['type' => 'int']],
        'enseignement'      => ['hydrator' => ['type' => 'int']],
        'paiement'          => ['hydrator' => ['type' => 'int']],
        'ecartMois'         => ['hydrator' => ['type' => 'int']],
    ];



    public function init()
    {

        $this->setAttribute('action', $this->getCurrentUrl());

        $hydrator = new GenericHydrator($this->getServiceSource()->getEntityManager(), $this->spec);
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'    => 'code',
            'options' => [
                'label' => "Code",
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'libelleCourt',
            'options' => [
                'label' => "Libellé Court",
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'libelleLong',
            'options' => [
                'label' => "Libellé Long",
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'enseignement',
            'options' => [
                'label' => "Peut porter des enseignements",
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'paiement',
            'options' => [
                'label' => "Peut porter des paiements",
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'ecartMois',
            'options' => [
                'label' => "Ecart des mois depuis septembre",
            ],
            'type'    => 'Text',
        ]);

        $this->add(new Csrf('security'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary',
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

            'code' => [
                'required' => true,
            ],

            'libelleCourt' => [
                'required' => true,
            ],

            'libelleLong' => [
                'required' => true,
            ],

            'enseignement' => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            if ($value == null) return true;

                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],

            'paiement' => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            if ($value == null) return true;

                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],

            'ecartMois' => [
                'required' => true,
            ],
        ];
    }

}
