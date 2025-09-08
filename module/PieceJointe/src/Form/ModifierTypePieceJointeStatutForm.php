<?php

namespace PieceJointe\Form;

use Application\Form\AbstractForm;
use PieceJointe\Hydrator\TypePieceJointeStatutHydrator;
use Application\Service\Traits\ContextServiceAwareTrait;
use PieceJointe\Service\Traits\TypePieceJointeStatutServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Application\Service\Traits\AnneeServiceAwareTrait;


class ModifierTypePieceJointeStatutForm extends AbstractForm
{
    use AnneeServiceAwareTrait;
    use ContextServiceAwareTrait;
    use TypePieceJointeStatutServiceAwareTrait;


    public function init()
    {
        $hydrator = new TypePieceJointeStatutHydrator();
        $this->setHydrator($hydrator);
        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'type'    => 'Checkbox',
            'name'    => 'obligatoire',
            'options' => [
                'label' => "La pièce justificative doit être fournie obligatoirement",
            ],
        ]);

        $this->add([
            'name'       => 'seuil-hetd',
            'options'    => [
                'label' => "Nombre d'heures min.",
            ],
            'type'       => 'Number',
            'attributes' => [
                'min' => '0',
            ],
        ]);

        $this->add([
            'name'    => 'type-heure-hetd',
            'options' => [
                'label' => 'Calculer les seuils en utilisant les heures équivalent TD',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'changement-rib',
            'options' => [
                'label' => 'Uniquement en cas de changement de RIB',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'nationalite-etrangere',
            'options' => [
                'label' => 'Uniquement en cas de nationalité étrangère',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'fc',
            'options' => [
                'label' => 'Limité aux actions de formation continue',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
                       'name'    => 'fa',
                       'options' => [
                           'label' => 'Limité aux actions de formation apprentissage',
                       ],
                       'type'    => 'Checkbox',
                   ]);
        $this->add([
            'type'       => 'Number',
            'name'       => 'duree-vie',
            'options'    => [
                'label'  => "Durée de vie de la pièce jointe",
                'suffix' => "année(s)",
            ],
            'attributes' => [
                'min'       => '1',
                'value'     => '1',
                'class'     => 'form-control',
                'info_icon' => "Si vous avez coché 'Uniquement en cas de changement de RIB', la durée de vie sera automatiquement à 1",
            ],
        ]);

        $this->add([
            'type'    => 'Checkbox',
            'name'    => 'obligatoire-hnp',
            'options' => [
                'label' => "Pièce jointe obligatoire même si les heures sont non payables",
            ],
        ]);

        $this->add(new Csrf('security'));

        $this->addSubmit();

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
            'obligatoire'     => [
                'required' => true,
            ],
            'seuil-hetd'      => [
                'required'   => false,
                'validators' => [
                    [
                        'name'    => 'Laminas\Validator\GreaterThan',
                        'options' => [
                            'min'       => 0,
                            'inclusive' => true,
                            'messages'  => [
                                \Laminas\Validator\GreaterThan::NOT_GREATER => "Le nombre d'heures doit être supérieur à 0",
                            ],
                        ],
                    ],
                ],
            ],
            'changement-rib'  => [
                'required' => true,
            ],
            'type-heure-hetd' => [
                'required' => true,
            ],
            'fc'              => [
                'required' => true,
            ],
            'fa' => [
                'required' => true,
            ],
            'obligatoire-hnp' => [
                'required' => true,
            ],
            'duree-vie'       => [
                'required' => true,
            ],
        ];
    }

}
