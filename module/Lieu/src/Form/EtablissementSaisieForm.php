<?php

namespace Lieu\Form;

use Application\Form\AbstractForm;
use Application\Hydrator\GenericHydrator;
use Application\Service\Traits\SourceServiceAwareTrait;
use Laminas\Form\Element\Csrf;


/**
 * Description of EtablissementForm
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class EtablissementSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;

    protected $spec = [
        'id'           => ['hydrator' => ['type' => 'int']],
        'departement'  => ['hydrator' => ['type' => 'string']],
        'localisation' => ['hydrator' => ['type' => 'string']],
        'libelle'      => ['hydrator' => ['type' => 'string']],
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
            'name'    => 'departement',
            'options' => [
                'label' => "Département de l'établissement",
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'localisation',
            'options' => [
                'label' => "Localisation de l'établissement",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => "Nom de l'établissement",
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
            'libelle' => [
                'required' => true,
            ],

            'departement' => [
                'required'   => false,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => 'Le champ département doit contenir 3 caractères ou moins'],
                        'callback' => function ($value) {
                            if ($value == null) return true;

                            return (strlen($value) <= 3 ? true : false);
                        }]),
                ],

            ],
        ];
    }

}
