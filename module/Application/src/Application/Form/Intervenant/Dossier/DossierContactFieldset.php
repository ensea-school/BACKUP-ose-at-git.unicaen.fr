<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Constants;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Validator\DepartementNaissanceValidator;
use Application\Validator\PaysNaissanceValidator;
use Zend\Form\Element\Email;
use Zend\Form\Element\Tel;
use Zend\Validator\Date as DateValidator;

/**
 * Description of DossierContactFieldset
 *
 */
class DossierContactFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;

    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $this->addElements();
    }



    /**
     * @return self
     */
    private function addElements()
    {
        //$privEdit      = $this->isAllowed(Privileges::getResourceId(Privileges::class));

        /**
         * Mail établissement
         */
        $this->add([
            'name'       => 'emailEtablissement',
            'options'    => [
                'label'         => 'Mail établissement <span class="text-danger">*</span>',
                'label_options' => ['disable_html_escape' => true],

            ],
            'attributes' => [
                //'placeholder' => "Email établissement",
                'class'     => 'form-control',
                'info_icon' => "Si vous n'avez pas d'email établissement vous devez renseigner le champs email personnel.",

            ],
            'type'       => Email::class,
        ]);

        /**
         * Mail personnel
         */
        $this->add([
            'name'       => 'emailPersonnel',
            'options'    => [
                'label' => 'Mail personnel',
            ],
            'attributes' => [
                //'placeholder' => "Email établissement",
                'class'     => 'form-control left-border-none',
                'info_icon' => "Si vous renseignez une adresse mail perso, celle-ci sera utilisée pour vous contacter.",

            ],
            'type'       => Email::class,
        ]);

        /**
         * Téléphone professionnel
         */
        $this->add([
            'name'       => 'telephoneProfessionnel',
            'options'    => [
                'label' => 'Téléphone professionnel',
            ],
            'attributes' => [
                //'placeholder' => "Email établissement",
                'class' => 'form-control left-border-none',

            ],
            'type'       => Tel::class,
        ]);

        /**
         * Téléphone personnel
         */
        $this->add([
            'name'       => 'telephonePersonnel',
            'options'    => [
                'label' => 'Téléphone personnel',
            ],
            'attributes' => [
                //'placeholder' => "Email établissement",
                'class' => 'form-control left-border-none',

            ],
            'type'       => Tel::class,
        ]);

        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $spec = [
            'emailEtablissement'     => [
                'required'   => false,
                'filters'    => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'EmailAddress'],
                ],
            ],
            'emailPersonnel'         => [
                'required'   => false,
                'filters'    => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'EmailAddress'],
                ],
            ],
            'telephoneProfessionnel' => [
                'required' => false,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],

            ],
            'telephonePersonnel'     => [
                'required' => false,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],
            ],

        ];

        return $spec;
    }
}