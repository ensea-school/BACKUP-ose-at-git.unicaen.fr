<?php

namespace Application\Controller;

use Application\Entity\Db\FonctionReferentiel;
use Application\Service\Traits\FonctionReferentielServiceAwareTrait;
use Application\Exception\DbException;
use Application\Form\FonctionReferentiel\Traits\FonctionReferentielSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class FonctionReferentielController extends AbstractController
{
    use FonctionReferentielServiceAwareTrait;
    use FonctionReferentielSaisieFormAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            FonctionReferentiel::class,
        ]);

        $fonctionsReferentiels = $this->getServiceFonctionReferentiel()->getList();

        return compact('fonctionsReferentiels');
    }



    public function saisieAction()
    {
        /* @var $fonctionReferentiel FonctionReferentiel */

        $fonctionReferentiel = $this->getEvent()->getParam('fonctionReferentiel');

        $form = $this->getFormFonctionReferentielSaisie();
        if (empty($fonctionReferentiel)) {
            $title = 'Création d\'une nouvelle fonction référentielle';
            $fonctionReferentiel = $this->getServiceFonctionReferentiel()->newEntity();
        } else {
            $title = 'Édition d\'une fonction référentielle';
        }

        $form->bindRequestSave($fonctionReferentiel, $this->getRequest(), function (FonctionReferentiel $fr) {
            try {
                $this->getServiceFonctionReferentiel()->save($fr);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $fr->getId());
            }
        });

        return compact('form', 'title');
    }

    public function deleteAction()
    {
        $fonctionReferentiel = $this->getEvent()->getParam('fonctionReferentiel');

        try {
            $this->getServiceFonctionReferentiel()->delete($fonctionReferentiel);
            $this->flashMessenger()->addSuccessMessage("Fonction Référentielle supprimée avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }
        return new MessengerViewModel(compact('fonctionReferentiel'));
    }
}