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
        /**
         * Mail établissement
         */
        $this->add([
            'name'    => 'emailEtablissement',
            'options' => [
                'label' => 'Mail établissement',
            ],
            'type'    => Email::class,
        ]);

        /**
         * Mail personnel
         */
        $this->add([
            'name'    => 'emailPersonnel',
            'options' => [
                'label' => 'Mail personnel',
            ],
            'type'    => Email::class
        ]);

        /**
         * Téléphone professionnel
         */
        $this->add([
            'name'    => 'telephoneProfessionnel',
            'options' => [
                'label' => 'Téléphone professionnel',
            ],
            'type'    => Tel::class,
        ]);

        /**
         * Téléphone personnel
         */
        $this->add([
            'name'    => 'telephonePersonnel',
            'options' => [
                'label' => 'Téléphone personnel',
            ],
            'type'    => Tel::class,
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
            'emailEtablissement'             => [
                'required' => false,
            ],
            'emailPersonnel'      => [
                'required' => false,
            ],
            'telephoneProfessionnel'               => [
                'required' => false,
            ],
            'telephonePersonnel'             => [
                'required' => false,
            ],

        ];

        return $spec;
    }
}