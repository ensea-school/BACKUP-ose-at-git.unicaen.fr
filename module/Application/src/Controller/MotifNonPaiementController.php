<?php

namespace Application\Controller;

use Application\Entity\Db\MotifNonPaiement;
use Application\Service\Traits\MotifNonPaiementServiceAwareTrait;
use Application\Exception\DbException;
use Application\Form\MotifNonPaiement\Traits\MotifNonPaiementSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class MotifNonPaiementController extends AbstractController
{
    use MotifNonPaiementServiceAwareTrait;
    use MotifNonPaiementSaisieFormAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            MotifNonPaiement::class,
        ]);

        $motifNonPaiements = $this->getServiceMotifNonPaiement()->getList();

        return compact('motifNonPaiements');
    }


    public function saisirAction()
    {
        /* @var $motifNonPaiement MotifNonPaiement */
        $motifNonPaiement = $this->getEvent()->getParam('motifNonPaiement');

        $form = $this->getFormMotifNonPaiementMotifNonPaiementSaisie();
        if (empty($motifNonPaiement)) {
            $title = 'Création d\'un nouveau motif de non paiement';
            $motifNonPaiement = $this->getServiceMotifNonPaiement()->newEntity();
        } else {
            $title = 'Édition d\'un motif de non paiement';
        }

        $form->bindRequestSave($motifNonPaiement, $this->getRequest(), function (MotifNonPaiement $motifNonPaiement) {
            try {
                $this->getServiceMotifNonPaiement()->save($motifNonPaiement);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }


    public function supprimerAction()
    {
        $motifNonPaiement = $this->getEvent()->getParam('motifNonPaiement');

        try {
            $this->getServiceMotifNonPaiement()->delete($motifNonPaiement);
            $this->flashMessenger()->addSuccessMessage("Motif de non paiement supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
    }
}
