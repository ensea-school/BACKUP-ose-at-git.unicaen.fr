<?php

namespace Intervenant\Controller;


use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Intervenant\Assertion\NoteAssertion;
use Intervenant\Entity\Db\Note;
use Intervenant\Form\MailerIntervenantFormAwareTrait;
use Intervenant\Form\NoteSaisieFormAwareTrait;
use Intervenant\Service\NoteServiceAwareTrait;

use Laminas\Mail\Message as MailMessage;
use Laminas\Mime\Message;
use Laminas\Mime\Mime;
use Laminas\Mime\Part;
use UnicaenApp\View\Model\MessengerViewModel;

class NoteController extends AbstractController
{
    use NoteServiceAwareTrait;
    use NoteSaisieFormAwareTrait;
    use MailerIntervenantFormAwareTrait;

    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Note::class,
        ]);

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant \Application\Entity\Db\Intervenant */

        if (!$intervenant) {
            throw new \Exception('Intervenant introuvable');
        }

        $notes = $this->getServiceNote()->getByIntervenant($intervenant);

        $historique = $this->getServiceNote()->getHistoriqueIntervenant($intervenant);


        return compact('notes', 'intervenant', 'historique');
    }


    public function saisirAction()
    {

        $intervenant = $this->getEvent()->getParam('intervenant');
        $note = $this->getEvent()->getParam('note');
        $form = $this->getFormNoteSaisie();

        if (empty($note)) {
            $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_NOTE_AJOUT));
            $title = 'Création d\'une nouvelle note intervenant';
            $note = $this->getServiceNote()->newEntity();
            $note->setIntervenant($intervenant);
        } else {
            $canEdit = $this->isAllowed($note, NoteAssertion::PRIV_EDITER_NOTE);
            $title = 'Édition d\'une note intervenant';
        }


        if ($canEdit) {
            $form->bindRequestSave($note, $this->getRequest(), function (Note $ni) {
                try {
                    $this->getServiceNote()->save($ni);

                    $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            });
        } else {
            $form->bind($note);

        }


        return compact('form', 'intervenant', 'title');
    }

    public function voirAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $note = $this->getEvent()->getParam('note');
        $title = 'Visualisation d\'une note intervenant';


        return compact('intervenant', 'note', 'title');

    }


    public function supprimerAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $note = $this->getEvent()->getParam('note');

        $canDelete = $this->isAllowed($note, NoteAssertion::PRIV_SUPPRIMER_NOTE);


        if (!$canDelete) {
            $this->flashMessenger()->addErrorMessage('Statut non modifiable : droit non accordé, car vous n\'avez pas le privilège pour cela');
        } else {
            try {
                $this->getServiceNote()->delete($note);
                $this->flashMessenger()->addSuccessMessage("La note a bien été supprimé");
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }

        return new MessengerViewModel(compact('note'));
    }

    public function envoyerEmailAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $title = 'Rédiger un email à l\'intervenant';

        $form = $this->getFormMailerIntervenant()->setIntervenant($intervenant)->initForm();

        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost();
            $from = $data['from'];
            $to = $data['to'];
            $subject = $data['subject'];
            $content = $data['content'];

            $body = new Message();

            $text = new Part($content);
            $text->type = Mime::TYPE_HTML;
            $text->charset = 'utf-8';
            $body->addPart($text);
            $message = new MailMessage();

            $message->setEncoding('UTF-8')
                ->setFrom($from)
                ->setSubject($subject)
                ->addTo($to)
                ->setBody($body);

            //Envoi du mail
            $this->mail()->send($message);
            //Création d'une trace de l'envoi dans les notes de l'intervenant
            $this->getServiceNote()->createNoteFromEmail($intervenant, $subject, $content);


        }

        return compact('intervenant', 'form', 'title');
    }
}

