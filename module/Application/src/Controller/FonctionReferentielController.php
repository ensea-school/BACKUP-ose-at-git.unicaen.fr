<?php

namespace Application\Controller;

use Referentiel\Entity\Db\FonctionReferentiel;
use Application\Service\Traits\FonctionReferentielServiceAwareTrait;
use Application\Form\FonctionReferentiel\Traits\FonctionReferentielSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class FonctionReferentielController extends AbstractController
{
    use FonctionReferentielServiceAwareTrait;
    use FonctionReferentielSaisieFormAwareTrait;


    public function indexAction()
    {
        $fonctionsReferentiels = $this->getServiceFonctionReferentiel()->getList();

        return compact('fonctionsReferentiels');
    }



    public function saisieAction()
    {
        /* @var $fonctionReferentiel FonctionReferentiel */

        $fonctionReferentiel = $this->getEvent()->getParam('fonctionReferentiel');
        $tab                 = $this->params()->fromQuery('tab', 'fiche');

        $form = $this->getFormFonctionReferentielFonctionReferentielSaisie();
        if (empty($fonctionReferentiel)) {
            $title               = 'Création d\'une nouvelle fonction référentielle';
            $fonctionReferentiel = $this->getServiceFonctionReferentiel()->newEntity();
        } else {
            $title = 'Édition d\'une fonction référentielle';
        }

        $form->bindRequestSave($fonctionReferentiel, $this->getRequest(), function (FonctionReferentiel $fr) {
            try {
                $this->getServiceFonctionReferentiel()->save($fr);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title', 'tab');
    }



    public function deleteAction()
    {
        $fonctionReferentiel = $this->getEvent()->getParam('fonctionReferentiel');

        try {
            $this->getServiceFonctionReferentiel()->delete($fonctionReferentiel);
            $this->flashMessenger()->addSuccessMessage("Fonction Référentielle supprimée avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('fonctionReferentiel'));
    }
}