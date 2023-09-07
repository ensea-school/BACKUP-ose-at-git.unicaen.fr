<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\Intervenant;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\FichierServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use Contrat\Assertion\ContratAssertion;
use Contrat\Entity\Db\Contrat;
use Contrat\Service\ContratServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Mission\Entity\Db\VolumeHoraireMission;
use Mission\Form\PrimeFormAwareTrait;
use Mission\Service\MissionServiceAwareTrait;
use Mission\Service\PrimeServiceAwareTrait;


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
    use PrimeFormAwareTrait;

    public function indexAction ()
    {
        /* @var $intervenant Intervenant */

        $intervenant = $this->getEvent()->getParam('intervenant');


        return compact('intervenant');
    }



    public function declarationPrimeAction ()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $contrat     = $this->getEvent()->getParam('contrat');

        $result = $this->uploader()->upload();

        if ($result instanceof JsonModel) {
            return $result;
        }
        if (is_array($result)) {
            $this->getServiceContrat()->creerDeclaration($result['files'], $contrat);
            $this->updateTableauxBord($intervenant);
        }

        return $this->redirect()->toRoute('intervenant/prime', ['intervenant' => $intervenant->getId()]);
    }



    private function updateTableauxBord (Intervenant $intervenant, $validation = false)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'prime',
        ], $intervenant);
    }



    public function supprimerDeclarationPrimeAction ()
    {
        $contrat = $this->getEvent()->getParam('contrat');
        //On supprimer la déclaration sur l'honneur
        $fichier = $contrat->getDeclaration();
        if ($fichier) {
            $contrat->setDeclaration(null);
            $this->em()->remove($fichier);
        }

        $this->em()->flush();
        $this->updateTableauxBord($contrat->getIntervenant());
        $this->flashMessenger()->addSuccessMessage("Déclaration sur l'honneur supprimée");

        return true;
    }



    public function validerDeclarationPrimeAction ()
    {

        $contrat = $this->getEvent()->getParam('contrat');

        //validation de la déclaration de prime
        $this->getServiceMission()->validerDeclarationPrime($contrat);
        $this->updateTableauxBord($contrat->getIntervenant());
        $this->flashMessenger()->addSuccessMessage("Déclaration sur l'honneur validée");

        return true;
    }



    public function refuserPrimeAction ()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $contrat     = $this->getEvent()->getParam('contrat');
        /**
         * @var $contrat Contrat
         */

        if ($contrat->getDateRefusPrime()) {
            $contrat->setDateRefusPrime(null);
        } else {
            $date = new \DateTime('now');
            $contrat->setDateRefusPrime($date);
        }
        $this->em()->persist($contrat);
        $this->em()->flush();

        $this->updateTableauxBord($intervenant);


        return $this->redirect()->toRoute('intervenant/prime', ['intervenant' => $intervenant->getId()]);
    }



    public function devaliderDeclarationPrimeAction ()
    {

        /**
         * @var $contrat Contrat
         */
        $contrat = $this->getEvent()->getParam('contrat');

        //validation de la déclaration de prime
        $this->getServiceMission()->devaliderDeclarationPrime($contrat);
        $this->updateTableauxBord($contrat->getIntervenant());
        $this->flashMessenger()->addSuccessMessage("Déclaration sur l'honneur devalidée");

        return true;
    }



    public function telechargerDeclarationPrimeAction ()
    {
        /** @var Fichier $fichier */

        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');
        /** @var Contrat $contrat */
        $contrat = $this->getEvent()->getParam('contrat');

        $fichier = $contrat->getDeclaration();

        $this->uploader()->download($fichier);
    }



    public function getContratPrimeAction ()
    {
        $intervenant   = $this->getEvent()->getParam('intervenant');
        $contratsPrime = $this->getServiceMission()->getContratPrimeMission(['intervenant' => $intervenant->getId()]);

        return $contratsPrime;
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



    protected function saisieAction ()
    {
        $prime       = $this->getEvent()->getParam('prime');
        $intervenant = $this->getEvent()->getParam('intervenant');
        $missions    = $intervenant->getMissions();


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
            }
            //On rattache la prime au contrat
            $datas = $this->getRequest()->getPost('missions');
            foreach ($datas as $id => $mission) {
                $missionEntity = $this->getServiceMission()->get($id);
                $missionEntity->setPrime($prime);
                $this->getServiceMission()->save($missionEntity);
            }
            $this->flashMessenger()->addSuccessMessage('Prime créée');
        }

        $vm = new ViewModel();
        $vm->setTemplate('mission/prime/saisie');
        $vm->setVariables(compact('title', 'missions', 'prime', 'intervenant'));

        return $vm;
    }

}