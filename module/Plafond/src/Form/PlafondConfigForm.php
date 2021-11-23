<?php

namespace Plafond\Form;

use Application\Form\AbstractForm;



/**
 * Description of PlafondConfigForm
 *
 * @author UnicaenCode
 */
class PlafondConfigForm extends AbstractForm
{

    public function init()
    {

        $this->setAttribute('action',$this->getCurrentUrl());

        /* Ajoutez vos éléments de formulaire ici */

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
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            /* Filtres et validateurs */
        ];
    }

}