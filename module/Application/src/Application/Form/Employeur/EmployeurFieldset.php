<?php

namespace Application\Form\Employeur;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;

/**
 * Description of EmployeurFieldset
 *
 */
class EmployeurFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;

    public function init()
    {
        $this->addElements();
    }

    /**
     * @return self
     */
    private function addElements()
    {

        $this->add([
            'name'       => 'employeur',
            'options'    => [
                'label'         => 'Employeur',
            ],
            'type'       => 'Text',
        ]);

        return $this;
    }

    public function getInputFilterSpecification()
    {

        $spec = [
            'employeur'             => [
                'required' => false,
            ],
        ];

        return $spec;
    }

}