<?php

namespace Application\Controller;

use Application\Entity\Db\Voirie;
use Application\Form\Voirie\Traits\VoirieSaisieFormAwareTrait;
use Application\Service\Traits\VoirieServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class VoirieController extends AbstractController
{

    use VoirieServiceAwareTrait;
    use VoirieSaisieFormAwareTrait;

    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Voirie::class,
        ]);

        $voiries = $this->getServiceVoirie()->getList();

        return compact('voiries');
    }



    public function saisieAction()
    {
        $voirie = $this->getEvent()->getParam('voirie');

        $form = $this->getFormVoirieVoirieSaisie();

        if (empty($voirie)) {
            $title  = "Création d'une nouvelle voirie";
            $voirie = $this->getServiceVoirie()->newEntity();
        } else {
            $title = "Edition d'une voirie";
        }

        $form->bindRequestSave($voirie, $this->getRequest(), function (Voirie $v) {
            try {
                $this->getServiceVoirie()->save($v);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function deleteAction()
    {
        $voirie = $this->getEvent()->getParam('voirie');
        try {
            $this->getServiceVoirie()->delete($voirie);
            $this->flashMessenger()->addSuccessMessage("Voirie supprimée avec succés");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('voirie'));
    }
}