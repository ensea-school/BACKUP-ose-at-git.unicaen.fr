<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Mission\Form\MissionTauxFormAwareTrait;
use Mission\Form\MissionTauxValeurFormAwareTrait;
use Mission\Service\MissionTauxServiceAwareTrait;
use Mission\Service\MissionTauxValeurServiceAwareTrait;
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
    use MissionTauxValeurServiceAwareTrait;
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

        $tauxRemuId = $this->params()->fromRoute('tauxRemu');
        $tauxRemu = $this->getServiceMissionTaux()->get($tauxRemuId);
        $form = $this->getFormMissionTaux();
        if (empty($tauxRemu)) {
            $title               = "Création d'un nouveau taux";
            $tauxRemu = $this->getServiceMissionTaux()->newEntity();
        } else {
            $title = "Édition d'un taux";
        }
        $form->bindRequestSave($tauxRemu, $this->getRequest(), function () use ($tauxRemu, $form) {

            $this->getServiceMissionTaux()->save($tauxRemu);
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form','title');
    }



    public function saisirValeurAction(): array
    {

        $tauxRemuValeurId = $this->params()->fromRoute('tauxRemuValeur');
        $tauxRemuValeur = $this->getServiceMissionTauxValeur()->get($tauxRemuValeurId);
        $form = $this->getFormMissionTauxValeur();
        if (empty($tauxRemuValeur)) {
            $title               = "Création d'une nouvelle valeur";
            /* @var \Mission\Entity\Db\MissionTauxRemuValeur $tauxRemuValeur */
            $tauxRemuValeur = $this->getServiceMissionTauxValeur()->newEntity();


        } else {
            $title = "Édition d'une nouvelle valeur";
        }
        if($tauxRemuValeur->getMissionTauxRemu() == null){
            $tauxRemuId = $this->params()->fromRoute('tauxRemu');
            $tauxRemu = $this->getServiceMissionTaux()->get($tauxRemuId);
            $tauxRemuValeur = $tauxRemuValeur->setMissionTauxRemu($tauxRemu);
        }


        $form->bindRequestSave($tauxRemuValeur, $this->getRequest(), function () use ($tauxRemuValeur, $form) {

            $this->getServiceMissionTauxValeur()->save($tauxRemuValeur);
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form','title');
    }



    public function supprimerAction(): MessengerViewModel
    {
        $tauxRemu = $this->getEvent()->getParam('tauxRemu');
        $this->getServiceMissionTaux()->delete($tauxRemu, true);

        return new MessengerViewModel();
    }



    public function supprimerValeurAction(): MessengerViewModel
    {

        return new MessengerViewModel();

    }
}

