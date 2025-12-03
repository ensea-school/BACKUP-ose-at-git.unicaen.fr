<?php

namespace PieceJointe\Form;

use Application\Form\AbstractForm;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\ClassMethodsHydrator;

/**
 * Description of TypePieceJointeSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class TypePieceJointeSaisieForm extends AbstractForm
{

    public function init()
    {
        $this->setHydrator(new ClassMethodsHydrator(false));

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
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
            'name'       => 'libelle',
            'options'    => [
                'label' => "Libelle",
            ],
            'attributes' => [
                'id' => uniqid('code'),
            ],
            'type'       => 'Text',
        ]);
        $this->add([
            'name'    => 'urlModeleDoc',
            'options' => [
                'label' => "Modèle",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
                       'name'    => 'description',
                       'options' => [
                           'label' => "Description",
                       ],
                       'type'    => 'Textarea',
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
            'code'    => [
                'required' => true,
            ],
            'libelle' => [
                'required' => true,
            ],
        ];
    }

}