<?php

namespace Dossier\Form;

use Dossier\Entity\Db\IntervenantDossier;
use Intervenant\Entity\Db\Statut;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Tel;

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
                'label'         => 'E-mail établissement',
                'label_options' => ['disable_html_escape' => true],

            ],
            'attributes' => [
                'class'     => 'form-control dossierElement',
                'info_icon' => "Non modifiable. Si vous n'avez pas d'email établissement vous devez renseigner le champs email personnel.",
                'disabled'  => 'disabled',
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
                'class'     => 'form-control left-border-none dossierElement',
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
                'class'     => 'form-control left-border-none dossierElement',
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
                'class' => 'form-control left-border-none dossierElement',

            ],
            'type'       => Tel::class,
        ]);

        //Gestion des labels selon les règles du statut intervenant sur les données contact
        $dossierIntervenant       = $this->getOption('dossierIntervenant');
        $statutDossierIntervenant = $dossierIntervenant->getStatut();

        /**
         * @var $statutDossierIntervenant Statut
         * @var $dossierIntervenant       IntervenantDossier
         */

        if ($statutDossierIntervenant->getDossierTelPerso()) {
            $this->get('telephonePersonnel')->setLabel('Téléphone personnel <span class="text-danger">*</span>');
            $this->get('telephoneProfessionnel')->removeAttribute('info_icon');
        }
        if ($statutDossierIntervenant->getDossierEmailPerso() || empty($dossierIntervenant->getEmailPro())) {
            $this->get('emailPersonnel')->setLabel('E-mail personnel <span class="text-danger">*</span>');
            $this->get('emailEtablissement')->removeAttribute('info_icon');
        }
    }



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