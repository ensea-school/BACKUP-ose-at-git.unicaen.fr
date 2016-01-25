<?php

namespace Application\Form\Budget;

use Application\Form\AbstractForm;


/**
 * Description of DotationSaisieForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class DotationSaisieForm extends AbstractForm
{

    public function init()
    {
        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'type'    => 'Text',
            'name'    => 'libelle',
            'options' => [
                'label' => 'Libellé de la dotation',
            ],
        ]);

        $this->add([
            'name'       => 'annee1',
            'type'       => 'Number',
            'options'    => [
                'label' => "Nombre d'heures :",
            ],
            'attributes' => [
                'class' => 'input-sm',
                'step'  => 'any',
                'min'   => 0,
                'onchange' => 'formSaisieDotationUpdateAnneeCivile()'
            ],
        ]);

        $this->add([
            'name'       => 'annee2',
            'type'       => 'Number',
            'options'    => [
                'label' => "Nombre d'heures :",
            ],
            'attributes' => [
                'class' => 'input-sm',
                'step'  => 'any',
                'min'   => 0,
                'onchange' => 'formSaisieDotationUpdateAnneeCivile()'
            ],
        ]);

        $this->add([
            'name'       => 'anneeCivile',
            'type'       => 'Text',
            'options'    => [
                'label' => "Nombre d'heures :",
            ],
            'attributes' => [
                'class' => 'input-sm',
                'step'  => 'any',
                'min'   => 0,
                'readonly' => 'true',
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
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
            'annee1' => [
                'required' => true,
            ],
            'annee2' => [
                'required' => true,
            ],
            'anneeCivile' => [
                'required' => true,
            ],
            'libelle' => [
                'required' => true,
            ],
        ];
    }

}
