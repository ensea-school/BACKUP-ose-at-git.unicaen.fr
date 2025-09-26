<?php

namespace Referentiel\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Referentiel\Entity\Db\FonctionReferentiel;
use Referentiel\Form\FonctionReferentielSaisieFormAwareTrait;
use Referentiel\Service\FonctionReferentielServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class FonctionReferentielController extends AbstractController
{
    use FonctionReferentielServiceAwareTrait;
    use FonctionReferentielSaisieFormAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('annee')->init([
            FonctionReferentiel::class,
        ]);
        $fonctionsReferentiels = $this->getServiceFonctionReferentiel()->getList();

        return compact('fonctionsReferentiels');
    }



    public function saisieAction()
    {
        /* @var $fonctionReferentiel FonctionReferentiel */

        $this->em()->getFilters()->enable('annee')->init([
            FonctionReferentiel::class,
        ]);

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
            if ($this->isAllowed($fr, Privileges::REFERENTIEL_ADMIN_EDITION)) {
                try {
                    $this->getServiceFonctionReferentiel()->save($fr);
                    $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }else{
                $this->flashMessenger()->addErrorMessage('Vous ne disposez pas des droits nécessaires pour ajouter ou modifier cette fonction référentielle');
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