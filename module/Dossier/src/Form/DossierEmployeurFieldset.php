<?php

namespace Dossier\Form;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;

/**
 * Description of EmployeurFieldset
 *
 */
class DossierEmployeurFieldset extends AbstractFieldset
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
            ->setLabel('Employeurs <span class="text-danger">*</span>:')
            ->setLabelOption('disable_html_escape', true);

        $this->add($employeur);


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