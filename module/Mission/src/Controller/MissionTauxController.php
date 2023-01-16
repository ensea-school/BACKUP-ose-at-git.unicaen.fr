<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Mission\Form\MissionTauxFormAwareTrait;
use Mission\Form\MissionTauxValeurFormAwareTrait;
use Mission\Service\MissionTauxServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of MissionTauxController
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTauxController extends AbstractController
{
    use MissionTauxServiceAwareTrait;
    use ContextServiceAwareTrait;
    use MissionTauxFormAwareTrait;
    use MissionTauxValeurFormAwareTrait;

    public function indexAction()
    {

        $tauxMissions = $this->getServiceMissionTaux()->getTauxRemus();
        $annee        = $this->getServiceContext()->getAnnee();

        return compact('tauxMissions', 'annee');
    }



    public function saisirAction()
    {

        $tauxRemu = $this->getEvent()->getParam('missionTauxRemu');
        $form     = $this->getFormMissionTaux();
        if (empty($tauxRemu)) {
            $title    = "Création d'un nouveau taux";
            $tauxRemu = $this->getServiceMissionTaux()->newEntity();
        } else {
            $title = "Édition d'un taux";
        }
        $form->bindRequestSave($tauxRemu, $this->getRequest(), function () use ($tauxRemu, $form) {

            $this->em()->persist($tauxRemu);
            $tauxRemuValeurs = $tauxRemu->getTauxRemuValeurs();
            foreach ($tauxRemuValeurs as $tauxRemuValeur) {
                $tauxRemuValeur->setMissionTauxRemu($tauxRemu);
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

        $tauxRemuValeurId = $this->params()->fromRoute('missionTauxRemuValeur');
        $form           = $this->getFormMissionTauxValeur();

        if (empty($tauxRemuValeurId)) {
            $title = "Création d'une nouvelle valeur";
            $tauxRemuValeur = $this->getServiceMissionTaux()->newEntityValeur();
        } else {
            $tauxRemuValeur = $this->getServiceMissionTaux()->getTauxRemusValeur($tauxRemuValeurId);
            $title = "Édition d'une valeur";
        }

        if ($tauxRemuValeur->getMissionTauxRemu() == null) {
            $tauxRemu = $this->getEvent()->getParam('missionTauxRemu');
            $tauxRemuValeur->setMissionTauxRemu($tauxRemu);
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



    public function supprimerAction(): MessengerViewModel
    {
        $tauxRemu = $this->getEvent()->getParam('missionTauxRemu');
        $this->getServiceMissionTaux()->delete($tauxRemu, true);

        return new MessengerViewModel();
    }



    public function supprimerValeurAction(): MessengerViewModel
    {

        return new MessengerViewModel();
    }
}

