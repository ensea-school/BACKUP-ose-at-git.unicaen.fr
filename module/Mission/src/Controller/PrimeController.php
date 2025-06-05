<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Fichier;
use Application\Provider\Tbl\TblProvider;
use Application\Service\Traits\FichierServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Contrat\Entity\Db\Contrat;
use Contrat\Service\ContratServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Mission\Entity\Db\Prime;
use Mission\Service\MissionServiceAwareTrait;
use Mission\Service\PrimeServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of PrimeController
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class PrimeController extends AbstractController
{
    use MissionServiceAwareTrait;
    use SourceServiceAwareTrait;
    use ContratServiceAwareTrait;
    use FichierServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use PrimeServiceAwareTrait;

    public function indexAction ()
    {
        /* @var $intervenant Intervenant */

        $intervenant          = $this->getEvent()->getParam('intervenant');
        $missions             = $intervenant->getMissions();
        $missionsWithoutPrime = 0;
        foreach ($missions as $mission) {
            if (!$mission->getPrime()) {
                $missionsWithoutPrime += 1;
            }
        }

        return compact('intervenant', 'missionsWithoutPrime');
    }



    public function declarationPrimeAction ()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $prime       = $this->getEvent()->getParam('prime');

        $result = $this->uploader()->upload();

        if ($result instanceof JsonModel) {
            return $result;
        }
        if (is_array($result)) {
            $this->getServicePrime()->creerDeclaration($result['files'], $prime);
            $this->updateTableauxBord($intervenant);
        }

        return $this->redirect()->toRoute('intervenant/prime-mission', ['intervenant' => $intervenant->getId()]);
    }



    private function updateTableauxBord (Intervenant $intervenant, $validation = false)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            TblProvider::MISSION_PRIME,
        ], $intervenant);
    }



    public function supprimerDeclarationPrimeAction ()
    {
        /**
         * @var Prime $prime
         */
        $prime = $this->getEvent()->getParam('prime');
        //On supprimer la déclaration sur l'honneur
        $fichier = $prime->getDeclaration();
        if ($fichier) {
            $prime->setDeclaration(null);
            $this->em()->remove($fichier);
        }

        $this->em()->flush();
        $this->updateTableauxBord($prime->getIntervenant());
        $this->flashMessenger()->addSuccessMessage("Déclaration sur l'honneur supprimée");

        return true;
    }



    public function validerDeclarationPrimeAction ()
    {

        $prime = $this->getEvent()->getParam('prime');

        //validation de la déclaration de prime
        $this->getServicePrime()->validerDeclarationPrime($prime);
        $this->updateTableauxBord($prime->getIntervenant());
        $this->flashMessenger()->addSuccessMessage("Déclaration sur l'honneur validée");

        return true;
    }



    public function refuserPrimeAction ()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $prime       = $this->getEvent()->getParam('prime');
        /**
         * @var $prime Prime
         */

        if ($prime->getDateRefus()) {
            $prime->setDateRefus(null);
        } else {
            $date = new \DateTime('now');
            $prime->setDateRefus($date);
        }
        $this->em()->persist($prime);
        $this->em()->flush();

        $this->updateTableauxBord($intervenant);


        return $this->redirect()->toRoute('intervenant/prime-mission', ['intervenant' => $intervenant->getId()]);
    }



    public function devaliderDeclarationPrimeAction ()
    {

        /**
         * @var $prime Prime
         */
        $prime = $this->getEvent()->getParam('prime');

        //validation de la déclaration de prime
        $this->getServicePrime()->devaliderDeclarationPrime($prime);
        $this->updateTableauxBord($prime->getIntervenant());
        $this->flashMessenger()->addSuccessMessage("Déclaration sur l'honneur devalidée");

        return true;
    }



    public function telechargerDeclarationPrimeAction ()
    {
        /** @var Fichier $fichier */

        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');
        /** @var Contrat $contrat */
        $prime = $this->getEvent()->getParam('prime');

        $fichier = $prime->getDeclaration();

        $this->uploader()->download($fichier);
    }



    /**
     * Retourne la liste des primes
     *
     * @return JsonModel
     */
    public function listeAction ()
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $model = $this->getServicePrime()->data(['intervenant' => $intervenant]);


        return $model;
    }



    public function supprimerPrimeAction ()
    {
        /**
         * @var Prime       $prime
         * @var Intervenant $intervenant
         */

        $prime       = $this->getEvent()->getParam('prime');
        $intervenant = $this->getEvent()->getParam('intervenant');

        if ($prime->getIntervenant()->getId() == $intervenant->getId()) {
            $this->getServicePrime()->supprimerPrime($prime);
            $this->flashMessenger()->addSuccessMessage("La prime a été supprimée");
            $this->updateTableauxBord($prime->getIntervenant());
        } else {
            $this->flashMessenger()->addErrorMessage("La prime n'appartien pas au bon intervenant");
        }

        return true;
    }



    protected function saisieAction ()
    {
        /**
         * @var Intervenant $intervenant
         */
        $prime       = $this->getEvent()->getParam('prime');
        $intervenant = $this->getEvent()->getParam('intervenant');
        $missions = $this->getServicePrime()->getMissionsByIntervenant(['intervenant' => $intervenant]);


        if ($prime) {
            $title = 'Modification d\'une prime de mission';
        } else {
            $title = 'Création d\'une prime de mission';
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            if (empty($prime)) {
                $prime = $this->getServicePrime()->newEntity();
                $prime->setIntervenant($intervenant);
                $prime = $this->getServicePrime()->save($prime);
                $this->flashMessenger()->addSuccessMessage('Prime créée');
            } else {
                //On supprimer la prime de toutes les missions
                //On est en mise à jour
                $this->flashMessenger()->addSuccessMessage('Prime mise à jour');
                $this->getServiceMission()->deletePrimeMissions($prime);
            }
            //On rattache la prime aux missions concernées
            $datas = $this->getRequest()->getPost('missions');

            foreach ($datas as $id => $mission) {
                $missionEntity = $this->getServiceMission()->get($id);
                $missionEntity->setPrime($prime);
                $this->getServiceMission()->save($missionEntity);
            }
            $this->updateTableauxBord($prime->getIntervenant());
        }

        $vm = new ViewModel();
        $vm->setTemplate('mission/prime/saisie');
        $vm->setVariables(compact('title', 'missions', 'prime', 'intervenant'));

        return $vm;
    }

}