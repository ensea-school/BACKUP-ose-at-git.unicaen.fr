<?php

namespace Application\Controller;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\WfEtapeDep;
use Application\Exception\DbException;
use Application\Form\Workflow\Traits\DependanceFormAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\WfEtapeServiceAwareTrait;
use Application\Service\Traits\WfEtapeDepServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\Exception\LogicException;
use UnicaenApp\View\Model\MessengerViewModel;


/**
 * Description of WorkflowController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 *
 */
class WorkflowController extends AbstractController
{
    use ContextServiceAwareTrait;
    use WfEtapeDepServiceAwareTrait;
    use DependanceFormAwareTrait;
    use WorkflowServiceAwareTrait;
    use WfEtapeServiceAwareTrait;



    public function indexAction()
    {
        return [];
    }



    public function dependancesAction()
    {
        $dql = '
        SELECT
          we
        FROM
          Application\Entity\Db\WfEtape we
        ORDER BY
          we.ordre
        ';
        $etapes = $this->em()->createQuery($dql)->getResult();


        $dql = '
        SELECT
          wed, es, ep
        FROM
          Application\Entity\Db\WfEtapeDep wed
          JOIN wed.etapeSuiv es
          JOIN wed.etapePrec ep
        ORDER BY
          es.ordre, ep.ordre
        ';

        $query = $this->em()->createQuery($dql);

        $d = $query->getResult();
        /* @var $d WfEtapeDep[] */
        $deps = [];
        foreach( $d as $dep ){
            $deps[$dep->getEtapeSuiv()->getId()][$dep->getEtapePrec()->getId()] = $dep;
        }

        return compact('etapes', 'deps');
    }



    public function saisieDepAction()
    {
        $wfEtapeDep = $this->getEvent()->getParam('wfEtapeDep');
        /* @var $wfEtapeDep WfEtapeDep */

        if (!$wfEtapeDep) {
            $etapeSuivanteId = $this->params()->fromQuery('etapeSuivante');

            $wfEtapeDep = $this->getServiceWfEtapeDep()->newEntity();
            if ($etapeSuivanteId){
                $etapeSuivante = $this->getServiceWfEtape()->get($etapeSuivanteId);
                $wfEtapeDep->setEtapeSuiv($etapeSuivante);
            }

        }

        $title = "Saisie d'une dépendance";

        $form = $this->getFormWorkflowDependance();
        $form->bindRequestSave($wfEtapeDep, $this->getRequest(), function ($wfEtapeDep) {
            try{
                $this->getServiceWfEtapeDep()->save($wfEtapeDep);
            }catch(\Exception $e){
                throw DbException::translate($e);
            }
        });

        return compact('title', 'form');

    }



    public function suppressionDepAction()
    {
        if (!($wfEtapeDep = $this->getEvent()->getParam('wfEtapeDep'))) {
            throw new \RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }

        $form = $this->makeFormSupprimer(function () use ($wfEtapeDep) {
            $this->getServiceWfEtapeDep()->delete($wfEtapeDep);
        });

        return compact('wfEtapeDep', 'form');
    }



    public function calculerToutAction()
    {
        $action = $this->params()->fromQuery('action') === '1';
        $title = 'Calcul du workflow...';
        $error = null;

        if ($action){
            try{
                $this->getServiceWorkflow()->calculerTout();
            }catch(\Exception $e){
                $error = $e->getMessage();
            }

        }

        return compact('action', 'title', 'error');
    }



    public function feuilleDeRouteRefreshAction()
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        if ($intervenant){
            $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
        }

        $this->flashMessenger()->addSuccessMessage('Feuille de route actualisée.');

        return new MessengerViewModel();
    }



    public function feuilleDeRouteBtnNextAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $wfEtapeCode = $this->params()->fromRoute('wfEtapeCode');
        if (!$wfEtapeCode){
            throw new LogicException('L\'étape du workflow doit être précisée');
        }

        return compact('intervenant', 'wfEtapeCode');
    }
}