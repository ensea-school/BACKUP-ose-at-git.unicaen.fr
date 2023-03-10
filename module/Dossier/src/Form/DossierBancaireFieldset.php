<?php

namespace Dossier\Form;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Dossier\Validator\RIBValidator;
use UnicaenApp\Validator\RIB;

/**
 * Description of DossierBancaireFieldset
 *
 */
class DossierBancaireFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;

    public function init()
    {
        $this->addElements();
    }



    private function addElements()
    {
        $this->add([
            'name'       => 'ribBic',
            'options'    => [
                'label'         => 'BIC <span class="text-danger">*</span>',
                'label_options' => ['disable_html_escape' => true],
            ],
            'attributes' => [
                'size'      => 11,
                'maxlength' => 11,
                'class'     => 'dossierElement',
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'ribIban',
            'options'    => [
                'label'         => 'IBAN <span class="text-danger">*</span>',
                'label_options' => ['disable_html_escape' => true],
            ],
            'attributes' => [
                'size'      => 34,
                'maxlength' => 34,
                'class'     => 'dossierElement',
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'ribHorsSepa',
            'options'    => [
                'label' => 'RIB hors zone SEPA',
            ],
            'attributes' => [
                'class' => 'dossierElement',
            ],
            'type'       => 'Checkbox',
        ]);


        return $this;
    }



    public function getInputFilterSpecification()
    {
        $spec = [
            'ribBic' => [
                'required'   => false,
                'readonly'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StringToUpper'],
                ],
                'validators' => [
                    new \Laminas\Validator\Regex([
                        'pattern'  => "/[0-9a-zA-Z]{8,11}/",
                        'messages' => [\Laminas\Validator\Regex::NOT_MATCH => "Le BIC doit contenir 8 à 11 caractères"],
                    ]),
                ],
            ],

            'ribIban' => [
                'required'   => false,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StringToUpper'],
                ],
                'validators' => [
                    new RIBValidator(),
                ],
            ],

            'ribHorsSepa' => [
                'required' => false,
            ],
        ];

        return $spec;
    }
}