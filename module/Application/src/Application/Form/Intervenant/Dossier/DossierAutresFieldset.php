<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;

/**
 * Description of DossierAutresFieldset
 *
 */
class DossierAutresFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;

    public function init()
    {
        $this->addElements();
    }


    private function addElements()
    {
        $this->add([
            'name'       => 'autre1',
            'options'    => [
                'label'              => 'Autre N°1',
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'autre2',
            'options'    => [
                'label'              => 'Autre N°2',
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'autre3',
            'options'    => [
                'label'              => 'Autre N°3',
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'autre4',
            'options'    => [
                'label'              => 'Autre N°4',
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'autre5',
            'options'    => [
                'label'              => 'Autre N°5',
            ],
            'type'       => 'Text',
        ]);

        return $this;
    }

    public function getInputFilterSpecification()
    {
        $spec = [
            'autre1' => [
                'required'   => false,
            ],
            'autre2' => [
                'required'   => false,
            ],
            'autre3' => [
                'required'   => false,
            ],
            'autre4' => [
                'required'   => false,
            ],
            'autre5' => [
                'required'   => false,
            ],
        ];

        return $spec;
    }
}