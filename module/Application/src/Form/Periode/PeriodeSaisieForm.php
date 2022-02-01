<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

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

    protected $hydratorElements = [
        'id'                => ['type' => 'int'],
        'libelleCourt'      => ['type' => 'string'],
        'libelleLong'       => ['type' => 'string'],
        'code'              => ['type' => 'string'],
        'ordre'             => ['type' => 'int'],
        'enseignement'      => ['type' => 'int'],
        'paiement'          => ['type' => 'int'],
        'ecartMois'         => ['type' => 'int'],
        'ecartMoisPaiement' => ['type' => 'int'],
    ];



    public function init()
    {

        $this->setAttribute('action', $this->getCurrentUrl());

        $hydrator = new GenericHydrator($this->getServiceSource()->getEntityManager(), $this->hydratorElements);
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
                'label' => "peut porter des paiement",
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

        $this->add([
            'name'    => 'ecartMoisPaiement',
            'options' => [
                'label' => "Ecart des mois de paiement",
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

            'ecartMoisPaiement' => [
                'required' => true,
            ],
        ];
    }

}
