<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Validator\NumeroINSEEValidator;

/**
 * Description of DossierInseeFieldset
 *
 */
class DossierInseeFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;

    public function init()
    {
        $this->addElements();
    }



    private function addElements()
    {
        $this->add([
            'name'       => 'numeroInsee',
            'options'    => [
                'label'              => 'Numéro <abbr title="Numéro de sécurité sociale">INSEE</abbr> (clé incluse) <span class="text-danger">*</span>',
                'use_hidden_element' => false,
                'checked_value'      => 1,
                'unchecked_value'    => 0,
                'label_options'      => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'info_icon' => "Numéro INSEE (sécurité sociale) avec la clé de contrôle",
            ],
            'type'       => 'Text',
        ]);

        /**
         * Numéro INSEE provisoire
         */
        $this->add([
            'name'       => 'numeroInseeEstProvisoire',
            'options'    => [
                'label'         => 'Numéro <abbr title="Numéro de sécurité sociale">INSEE</abbr> provisoire ',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
            ],
            'type'       => 'Checkbox',
        ]);


        return $this;
    }



    public function getInputFilterSpecification()
    {
        $numeroInseeProvisoire = (bool)$this->get('numeroInseeEstProvisoire')->getValue();

        $spec = [
            'numeroInsee'              => [
                'required'   => false,
                'validators' => [
                    new NumeroINSEEValidator([
                        'provisoire' => $numeroInseeProvisoire,
                    ]),
                ],
            ],
            'numeroInseeEstProvisoire' => [
                'required' => false,
            ],
        ];

        return $spec;
    }
}