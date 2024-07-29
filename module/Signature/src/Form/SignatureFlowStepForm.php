<?php

namespace Signature\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\RoleServiceAwareTrait;
use Signature\Hydrator\SignatureFlowStepHydrator;
use UnicaenApp\Util;
use UnicaenSignature\Entity\Db\SignatureFlowStep;
use UnicaenSignature\Service\SignatureConfigurationServiceAwareTrait;


/**
 * Description of SignatureFlowStepForm
 *
 */
class SignatureFlowStepForm extends AbstractForm
{
    use RoleServiceAwareTrait;

    public function init()
    {


        $this->setAttribute('id', uniqid('fm'));
        $this->setHydrator(new SignatureFlowStepHydrator());
        $ignored = ['signatureFlow', 'recipientsMethod', 'notificationsRecipients', 'editableRecipients', 'options', 'observers_options', 'observersMethod'];
        $this->spec(SignatureFlowStep::class, $ignored);
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

        /* $this->setValueOptions('typeMission', Util::collectionAsOptions($typesMissions));
         $this->get('typeMission')->setAttribute('data-tm', json_encode($tmData));

         $trDql = "SELECT mtr FROM " . TauxRemu::class . " mtr WHERE mtr.histoDestruction IS NULL";
         $this->setValueOptions('tauxRemu', $trDql);
         $this->setValueOptions('tauxRemuMajore', $trDql);
         $this->get('tauxRemuMajore')->setEmptyOption('- Aucune majoration -');

         $this->setLabels([
             'structure'       => 'Composante en charge du suivi de mission',
             'typeMission'     => 'Type de mission',
             'tauxRemu'        => 'Taux de rémunération',
             'tauxRemuMajore'  => 'Taux majoré (heures nocturnes et dimanches/jf)',
             'dateDebut'       => 'Date de début',
             'dateFin'         => 'Date de fin',
             'description'     => 'Descriptif de la mission',
             'libelleMission'  => 'Libelle mission',
             'etudiantsSuivis' => 'Noms des étudiants suivis',
             'heuresFormation' => 'Heures de formation prévues',
             'heures'          => 'Heures',
         ]);*/

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