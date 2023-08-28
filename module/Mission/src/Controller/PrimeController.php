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

}