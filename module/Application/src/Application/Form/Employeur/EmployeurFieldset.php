<?php

namespace Application\Form\Employeur;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;

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
        $employeur = new SearchAndSelect('employeur');
        $employeur
            ->setAutocompleteSource($this->getUrl(
                'employeur-search'
            ))
            ->setLabel('Employeurs :');
        $this->add($employeur);


        /* $this->add([
             'name'    => 'employeur',
             'options' => [
                 'label' => 'Employeur',
             ],
             'type'    => 'Text',
         ]);*/

        return $this;
    }



    public function getInputFilterSpecification()
    {

        $spec = [
            'employeur' => [
                'required' => false,
            ],
        ];

        return $spec;
    }

}