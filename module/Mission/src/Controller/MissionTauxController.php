<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Mission\Entity\Db\MissionTauxRemu;
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


    public function indexAction()
    {

        $tauxMissions = $this->getServiceMissionTaux()->getTauxRemus();
        $annee        = $this->getServiceContext()->getAnnee();

        return compact('tauxMissions', 'annee');
    }



    public function saisirAction(): array
    {

        $tauxMissions = $this->getServiceMissionTaux()->getTauxRemus();
        $annee        = $this->getServiceContext()->getAnnee();

        return compact('tauxMissions', 'annee');
    }



    public function saisirValeurAction(): array
    {

        $tauxMissions = $this->getServiceMissionTaux()->getTauxRemus();
        $annee        = $this->getServiceContext()->getAnnee();

        return compact('tauxMissions', 'annee');
    }



    public function supprimerAction(): MessengerViewModel
    {
        echo "test";

        return new MessengerViewModel();
    }



    public function supprimerValeurAction(): MessengerViewModel
    {

        return new MessengerViewModel();

    }
}

