<?php

namespace Intervenant\Form;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Dossier\Service\Traits\DossierServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;

/**
 * Description of Statut
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class MailerIntervenantForm extends AbstractForm
{

    use DossierServiceAwareTrait;
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;


    protected Intervenant $intervenant;



    public function initForm ()
    {
        $labels = [
            'from'    => 'Expéditeur',
            'subject' => 'Objet du mail',
            'to'      => 'Email intervenant',
            'content' => 'Contenu du mail',
            'copy'    => 'Email en copie caché',

        ];


        $this->setAttribute('action', $this->getCurrentUrl());

        $this->setAttribute('id', 'mailer-intervenant');


        $this->spec([
            'from'    => [
                'type' => 'Text',
                'name' => 'from',

            ],
            'copy'    => [
                'type' => 'Text',
                'name' => 'copy',

            ],
            'to'      => [
                'type' => 'Select',
                'name' => 'to',

            ],
            'subject' => [
                'type' => 'Text',
                'name' => 'subject',
            ],
            'content' => [
                'type'       => 'Textarea',
                'name'       => 'content',
                'attributes' => ['id' => 'content-mailer-intervenant'],
            ],
        ]);


        $this->build();


        //On set l'email de destination par rapport à l'intervenant
        $emails = $this->getServiceDossier()->getEmailsIntervenant($this->intervenant);

        $emailValues = [];
        if (!empty($emails['perso'])) {
            $emailValues[$emails['perso']] = 'E-mail perso - ' . $emails['perso'];
        }
        if (!empty($emails['pro'])) {
            $emailValues[$emails['pro']] = 'E-mail pro - ' . $emails['pro'];
        }
        $this->setValueOptions('to', $emailValues);

        //On set l'email expéditeur par rapport au contexte utilisateur
        $context     = $this->getServiceContext();
        $parametre   = $this->getServiceParametres();
        $fromDefault = !empty($parametre->get('indicateur_email_expediteur')) ? trim($parametre->get('indicateur_email_expediteur')) : '';
        if (!empty($fromDefault)) {
            $from = $fromDefault;
        } else {
            $from = $context->getUtilisateur()->getEmail();
        }

        $this->get('from')->setValue($from);

        //On set les labels des champs de formulaire
        $this->setLabels($labels);

        //On définit les champs obligatoire
        $this->getInputFilter()->get('subject')->setRequired('true');
        $this->getInputFilter()->get('content')->setRequired('true');

        $this->addSecurity();
        $this->addSubmit('Envoyer');

        return $this;
    }



    public function setIntervenant (Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    public function getIntervenant (): Intervenant
    {
        return $this->intervenant;
    }

}
