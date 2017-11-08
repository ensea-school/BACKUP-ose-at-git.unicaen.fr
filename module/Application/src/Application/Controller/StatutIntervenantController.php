<?php

namespace Application\Controller;

use Application\Entity\Db\StatutIntervenant;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Application\Exception\DbException;
use Application\Form\StatutIntervenant\Traits\StatutIntervenantSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class StatutIntervenantController extends AbstractController
{
    use StatutIntervenantAwareTrait;
    use StatutIntervenantSaisieFormAwareTrait;

    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            StatutIntervenant::class,
        ]);

        $statutsIntervenants = $this->getServiceStatutIntervenant()->getList();

        return compact('statutsIntervenants');
    }


    public function saisieAction()
    {
        /* @var $statutIntervenant StatutIntervenant */

        $statutIntervenant = $this->getEvent()->getParam('statutIntervenant');

        $form = $this->getFormStatutIntervenantSaisie();
        if (empty($statutIntervenant)) {
            $title = 'Création d\'une nouveau statut d\'intervenant';
            $statutIntervenant = $this->getServiceStatutIntervenant()->newEntity();
        } else {
            $title = 'Édition d\'un statut d\'intervenant';
        }

        $form->bindRequestSave($statutIntervenant, $this->getRequest(), function (StatutIntervenant $si) {
            try {
                $this->getServiceStatutIntervenant()->save($si);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $si->getId());
            }
        });

        return compact('form', 'title');
    }

    public function deleteAction()
    {
        $statutIntervenant = $this->getEvent()->getParam('statutIntervenant');

        try {
            $this->getServiceStatutIntervenant()->delete($statutIntervenant);
            $this->flashMessenger()->addSuccessMessage("Statut d\'Intervenant supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }
        return new MessengerViewModel(compact('statutIntervenant'));
    }
}
