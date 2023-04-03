<?php

namespace Paiement\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Paiement\Entity\Db\TauxRemu;
use Paiement\Form\TauxFormAwareTrait;
use Paiement\Form\TauxValeurFormAwareTrait;
use Paiement\Service\TauxRemuServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenVue\View\Model\AxiosModel;

/**
 * Description of TauxRemuController
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class TauxRemuController extends AbstractController
{
    use TauxRemuServiceAwareTrait;
    use ContextServiceAwareTrait;
    use TauxFormAwareTrait;
    use TauxValeurFormAwareTrait;

    public function indexAction()
    {
        $annee = $this->getServiceContext()->getAnnee();

        return compact('annee');
    }



    public function getListeTauxAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            TauxRemu::class,
        ]);


        $tauxListe = $this->getServiceTauxRemu()->getTauxRemusAnnee();

        $liste = [];
        /** @var TauxRemu $taux */
        foreach ($tauxListe as $taux) {
            //Calcul de la liste des taux
            $liste[$taux->getId()] = $this->getServiceTauxRemu()->tauxWs($taux);
            $liste[$taux->getId()]['canEdit'] = $this->isAllowed($taux,Privileges::TAUX_EDITION);
            $liste[$taux->getId()]['canDeleteValeur'] = $this->isAllowed($taux,Privileges::TAUX_SUPPRESSION);
            $liste[$taux->getId()]['canDelete'] = $liste[$taux->getId()]['canDeleteValeur'] && !$taux->hasChildren();
        }


        return new AxiosModel($liste);
    }



    public function saisirAction()
    {

        $tauxRemu = $this->getEvent()->getParam('tauxRemu');
        $form     = $this->getFormTaux();
        if (empty($tauxRemu)) {
            $title    = "Création d'un nouveau taux";
            $tauxRemu = $this->getServiceTauxRemu()->newEntity();
        } else {
            $title = "Édition d'un taux";
        }
        $form->bindRequestSave($tauxRemu, $this->getRequest(), function () use ($tauxRemu, $form) {

            $this->em()->persist($tauxRemu);
            $tauxRemuValeurs = $tauxRemu->getTauxRemuValeurs();
            foreach ($tauxRemuValeurs as $tauxRemuValeur) {
                $tauxRemuValeur->setTauxRemu($tauxRemu);
                $this->em()->persist($tauxRemuValeur);
            }
            $this->em()->flush($tauxRemu);
            foreach ($tauxRemuValeurs as $tauxRemuValeur) {
                $this->em()->flush($tauxRemuValeur);
            }
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form', 'title');
    }



    public function saisirValeurAction(): array
    {

        $tauxRemuValeurId = $this->params()->fromRoute('tauxRemuValeur');
        $form             = $this->getFormTauxValeur();

        if (empty($tauxRemuValeurId)) {
            $title          = "Création d'une nouvelle valeur";
            $tauxRemuValeur = $this->getServiceTauxRemu()->newEntityValeur();
        } else {
            $tauxRemuValeur = $this->getServiceTauxRemu()->getTauxRemuValeur($tauxRemuValeurId);
            $title          = "Édition d'une valeur";
        }

        if ($tauxRemuValeur->getTauxRemu() == null) {
            $tauxRemu = $this->getEvent()->getParam('tauxRemu');
            $tauxRemuValeur->setTauxRemu($tauxRemu);
        }


        $form->bindRequestSave($tauxRemuValeur, $this->getRequest(), function () use ($tauxRemuValeur, $form) {
            $this->em()->persist($tauxRemuValeur);
            $this->em()->flush($tauxRemuValeur);
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form', 'title');
    }



    public function supprimerAction(): \Laminas\View\Model\JsonModel
    {
        $tauxRemu = $this->getEvent()->getParam('tauxRemu');
        $this->getServiceTauxRemu()->delete($tauxRemu, true);

        $this->flashMessenger()->addSuccessMessage("Taux supprimée avec succès.");

        return new AxiosModel([]);
    }

    /**
     * Retourne les données pour un taux
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function getAction()
    {
        $tauxRemu = $this->getEvent()->getParam('tauxRemu');

        return new AxiosModel($tauxRemu);
    }

    public function supprimerValeurAction(): MessengerViewModel
    {
        $tauxRemuValeurId = $this->params()->fromRoute('tauxRemuValeur');
        $tauxRemuValeur   = $this->getServiceTauxRemu()->getTauxRemuValeur($tauxRemuValeurId);
        $this->em()->remove($tauxRemuValeur);
        $this->em()->flush();


        return new MessengerViewModel();
    }
}
