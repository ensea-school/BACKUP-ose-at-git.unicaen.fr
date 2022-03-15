<?php

namespace Intervenant\Form;

use Application\Entity\Db\Intervenant;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Intervenant\Entity\Db\Note;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Intervenant\Entity\Db\Statut;
use Intervenant\Service\NoteServiceAwareTrait;
use Intervenant\Service\TypeNoteServiceAwareTrait;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;

/**
 * Description of Statut
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class MailerIntervenantForm extends AbstractForm
{

    use DossierServiceAwareTrait;
    use ContextServiceAwareTrait;


    protected Intervenant $intervenant;

    public function initForm()
    {
        $labels = [
            'from'    => 'Expéditeur',
            'subject' => 'Objet du mail',
            'to'      => 'Email intervenant',
            'content' => 'Contenu du mail',

        ];


        $this->setAttribute('action', $this->getCurrentUrl());

        $this->setAttribute('id', 'mailer-intervenant');


        /*  $form = new Form();
          $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
          $form->add((new Text('from'))->setValue($from));
          $form->add(new Text('cci'));
          $form->add((new Text('nombre'))->setValue(count($emails)));
          $form->add((new Text('subject'))->setValue($subject));
          $form->add((new Textarea('body'))->setValue($body));
          $form->add((new Checkbox('copy'))->setValue(1));
          $form->add((new Hidden('intervenants'))->setValue($intervenantsStringIds));
          $form->getInputFilter()->get('subject')->setRequired(true);
          $form->getInputFilter()->get('body')->setRequired(true);*/


        $this->spec([
            'from'    => [
                'type' => 'Text',
                'name' => 'from',

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
        $context = $this->getServiceContext();
        $emailUtilisateur = $context->getUtilisateur()->getEmail();
        $this->get('from')->setValue($emailUtilisateur);

        //On set les labels des champs de formulaire
        $this->setLabels($labels);

        //On définit les champs obligatoire
        $this->getInputFilter()->get('subject')->setRequired('true');
        $this->getInputFilter()->get('content')->setRequired('true');

        $this->addSubmit('Envoyer');

        return $this;
    }

    public function setIntervenant(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }


}
