<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Intervenant;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\SourceServiceAwareTrait;
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
        }


        //on récupére les posts


        return $this->redirect()->toRoute('intervenant/prime', ['intervenant' => $intervenant->getId()]);
    }



    public function getContratPrimeAction ()
    {
        $intervenant   = $this->getEvent()->getParam('intervenant');
        $contratsPrime = $this->getServiceMission()->getContratPrimeMission(['intervenant' => $intervenant->getId()]);

        return $contratsPrime;
    }

}