<?php

namespace Signature\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\RoleServiceAwareTrait;
use Signature\Hydrator\SignatureFlowStepHydrator;
use UnicaenApp\Util;
use UnicaenSignature\Entity\Data\LevelInfo;
use UnicaenSignature\Entity\Db\SignatureFlowStep;
use UnicaenSignature\Service\SignatureConfigurationServiceAwareTrait;


/**
 * Description of SignatureFlowStepForm
 *
 */
class SignatureFlowStepForm extends AbstractForm
{
    use RoleServiceAwareTrait;
    use SignatureConfigurationServiceAwareTrait;
    use ParametresServiceAwareTrait;

    public function init()
    {


        $this->setAttribute('id', uniqid('fm'));
        $this->setHydrator(new SignatureFlowStepHydrator());
        $ignored = ['letterfileName', 'signatureFlow', 'recipientsMethod', 'notificationsRecipients', 'editableRecipients', 'options', 'observers_options', 'observersMethod'];
        $labels  = [
            'label'             => 'Nom de l\'étape',
            'level'             => 'Niveau de signature',
            'recipientMethod'   => 'Type de signataire',
            'roles'             => 'Rôle des signataires',
            'order'             => 'Order de l\'étape',
            'allRecipientsSign' => 'Tous les signataires doivent signer',

        ];

        $this->spec(SignatureFlowStep::class, $ignored);
        //On récupére la liste de niveau de signature possible
        $paramLetterFile             = $this->getServiceParametres()->get("signature_electronique_parapheur");
        $levelLetterFiles            = $this->getSignatureConfigurationService()->getLevels();
        $listeSignatureTypes['none'] = 'aucun';
        if (!empty($paramLetterFile)) {

            /**
             * @var LevelInfo $value
             */
            foreach ($levelLetterFiles as $key => $value) {
                if ($value->isUsed()) {
                    $listeSignatureTypes[$value->getKey()] = $value->getLabel();
                }
            }
        }
        $this->spec(['level' => [
            'type'    => 'Select',
            'name'    => 'level',
            'options' => [
                'value_options' => $listeSignatureTypes,
            ],
        ]]);

        $this->add([
                       'name'       => 'recipientMethod',
                       'options'    => [
                           'label'         => 'Type de signataire',
                           'label_options' => ['disable_html_escape' => true],
                       ],
                       'attributes' => [
                           'id' => 'recipient-method',
                       ],
                       'type'       => 'Select',
                   ]);
        $this->get('recipientMethod')->setValueOptions(['by_intervenant' => 'Intervenant',
                                                        'by_role'        => 'Rôles',]);

        $this->add([
                       'name'       => 'roles',
                       'options'    => [
                           'label'            => 'Type de signataire',
                           'label_options'    => ['disable_html_escape' => true],
                           'label_attributes' => ['class' => 'liste-roles'],
                       ],
                       'attributes' => [
                           'class' => 'liste-roles',
                       ],
                       'type'       => 'Select',
                   ]);
        $this->get('roles')->setValueOptions(Util::collectionAsOptions($this->getServiceRole()->getList()));


        $this->build();
        $this->setLabels($labels);


        $this->addSubmit();
    }



    public function getInputFilterSpecification()
    {
        return [
            'roles' => [
                'required' => false,
            ],
        ];
    }

}