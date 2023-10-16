<?php

namespace Mission\Form;

use Application\Form\AbstractForm;
use Mission\Service\MissionTypeServiceAwareTrait;


/**
 * Description of MissionCentreCoutsTypeForm
 *
 * @author UnicaenCode
 */
class MissionCentreCoutsTypeForm extends AbstractForm
{
    use MissionTypeServiceAwareTrait;

    public function init()
    {

        $this->setAttribute('action', $this->getCurrentUrl());
        /* Ajoutez vos éléments de formulaire ici */

        $this->add([

            'name'    => 'centreCouts',
            'type'    => 'Select',
            'input'   => [
                'required' => true,
            ],
            'options' => [
                'empty_option' => 'Sélectionner un centre de couts',
            ],
            'attributes' => [
                'class'            => 'input-sm selectpicker',
                'data-live-search' => 'true',
                'onchange' => 'this.form.submit();',
            ],
        ]);
        $this->add([
            'name'  => 'structure',
            'type'  => 'hidden',
            'input' => [
                'required' => true,
            ],
        ]);
    }
}

