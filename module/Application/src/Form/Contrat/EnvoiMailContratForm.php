<?php

namespace Application\Form\Contrat;

use Intervenant\Entity\Db\Intervenant;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Form\Element;
use Laminas\Form\Element\Csrf;


class EnvoiMailContratForm extends AbstractForm
{

    use ParametresServiceAwareTrait;
    use ContextServiceAwareTrait;

    protected $intervenant;



    public function init()
    {
        /*    $hydrator = new TypeInterventionStatutHydrator();
            $this->setHydrator($hydrator);*/

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
                'options' => [
                    'label' => 'Email de l\'expéditeur du contrat',
                ],
                'name'    => 'expediteur-mail',
                'type'    => Element\Email::class,
            ]
        );


        $this->add([
                'options'    => [
                    'label' => 'Email du destinataire du contrat',
                ],
                'name'       => 'destinataire-mail',
                'type'       => Element\Email::class,
                'attributes' => [
                    'info_icon' => "Non modifiable. Pour changer le mail du destinataire, merci de le faire au niveau des données personnelles.",
                    'disabled'  => 'disabled',
                ],
            ]
        );

        $this->add([
                'name' => 'destinataire-mail-hide',
                'type' => Element\Hidden::class,

            ]
        );

        $this->add([
                'options' => [
                    'label' => 'Ajouter des destinataires en copie caché (séparés par des points virugles)',
                ],
                'name'    => 'destinataire-cc-mail',
            ]
        );

        $this->add(new Csrf('security'));
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Envoyer l'email",
                'class' => 'btn btn-primary',
            ],
        ]);

        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'expediteur-mail'      => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\EmailAddress(['domain' => false]),
                ],
            ],
            'destinataire-mail'    => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\EmailAddress(['domain' => false]),
                ],
            ],
            'destinataire-cc-mail' => [
                'required' => false,

            ],

        ];
    }

}




