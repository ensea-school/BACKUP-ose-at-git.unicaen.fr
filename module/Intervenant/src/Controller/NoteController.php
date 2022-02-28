<?php

namespace Intervenant\Controller;


use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Intervenant\Entity\Db\Note;
use Intervenant\Form\NoteSaisieFormAwareTrait;
use Intervenant\Service\NoteServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class NoteController extends AbstractController
{
    use NoteServiceAwareTrait;
    use NoteSaisieFormAwareTrait;

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


        return compact('notes', 'intervenant');
    }



    public function saisirAction()
    {

        $intervenant = $this->getEvent()->getParam('intervenant');
        $note        = $this->getEvent()->getParam('note');
        $form        = $this->getFormNoteSaisie();

        if (empty($note)) {
            $title = 'Création d\'une nouvelle note intervenant';
            $note  = $this->getServiceNote()->newEntity();
            $note->setIntervenant($intervenant);
        } else {
            $title = 'Édition d\'une note intervenant';
        }

        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_NOTE_EDITION));


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
            $form->readOnly();
        }


        return compact('form', 'intervenant');
    }



    public function supprimerAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $note        = $this->getEvent()->getParam('note');

        $canDelete = $this->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_NOTE_SUPPRESSION));


        if (!$canDelete) {
            $this->flashMessenger()->addErrorMessage('Statut non modifiable : droit non accordé, car vous n\'avez pas le privilège pour cela');
        } else {
            try {
                //$this->getServiceNote()->delete($note);
                $this->flashMessenger()->addSuccessMessage("La note a bien été supprimé");
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }

        return new MessengerViewModel(compact('note'));
    }
}

