<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Constants;
use Application\Entity\Db\StatutIntervenant;
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
            'name'       => 'emailEtablissement',
            'options'    => [
                'label'         => 'E-mail professionnel <span class="text-danger">*</span>',
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
                'label'         => 'E-mail personnel',
                'label_options' => ['disable_html_escape' => true],
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
                'label'         => 'Téléphone professionnel <span class="text-danger">*</span>',
                'label_options' => ['disable_html_escape' => true],

            ],
            'attributes' => [
                'class'     => 'form-control left-border-none',
                'info_icon' => "Si vous n'avez pas de téléphone professionnel vous devez renseigner le champs téléphone personnel.",
            ],
            'type'       => Tel::class,
        ]);

        /**
         * Téléphone personnel
         */
        $this->add([
            'name'       => 'telephonePersonnel',
            'options'    => [
                'label'         => 'Téléphone personnel',
                'label_options' => ['disable_html_escape' => true],
            ],
            'attributes' => [
                //'placeholder' => "Email établissement",
                'class' => 'form-control left-border-none',

            ],
            'type'       => Tel::class,
        ]);

        //Gestion des labels selon les règles du statut intervenant sur les données contact
        $statutDossierIntervenant = $this->getOption('statutDossierIntervenant');
        /**
         * @var $statutDossierIntervenant StatutIntervenant
         */
        if ($statutDossierIntervenant->getDossierTelPerso()) {
            $this->get('telephonePersonnel')->setLabel('Téléphone personnel <span class="text-danger">*</span>');
            $this->get('telephoneProfessionnel')->removeAttribute('info_icon');
        }
        if ($statutDossierIntervenant->getDossierEmailPerso()) {
            $this->get('emailPersonnel')->setLabel('E-mail personnel <span class="text-danger">*</span>');
            $this->get('emailEtablissement')->removeAttribute('info_icon');
        }
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