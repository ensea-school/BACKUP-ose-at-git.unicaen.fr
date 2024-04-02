<?php

namespace Lieu\Form;


use Application\Form\AbstractForm;
use Application\Hydrator\GenericHydrator;
use Laminas\Form\Element\Csrf;

/**
 * Description of VoirieForm
 */
class VoirieSaisieForm extends AbstractForm
{

    protected $spec = [
        'id'         => ['hydrator' => ['type' => 'int']],
        'libelle'    => ['hydrator' => ['type' => 'string']],
        'code'       => ['hydrator' => ['type' => 'string']],
        'codeRh'     => ['hydrator' => ['type' => 'string']],
        'sourceCode' => ['hydrator' => ['type' => 'string']],
    ];


    public function init()
    {
        $hydrator = new GenericHydrator($this->getEntityManager(), $this->spec);
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'       => 'libelle',
            'options'    => [
                'label' => 'Libellé',
            ],
            'attributes' => [

            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'code',
            'options'    => [
                'label' => "Code",
            ],
            'attributes' => [
                'id' => uniqid('code'),
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'codeRh',
            'options'    => [
                'label' => "Code RH",
            ],
            'attributes' => [
                'id' => uniqid('codeRh'),
            ],
            'type'       => 'Text',
        ]);


        $this->add([
            'name'    => 'sourceCode',
            'type'    => 'Text',
            'options' => [
                'label' => 'Code source',
            ],
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

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
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
                'required' => false,
            ],
            'code'    => [
                'required' => false,
            ],
            'code_rh' => [
                'required' => false,
            ],
        ];
    }

}
