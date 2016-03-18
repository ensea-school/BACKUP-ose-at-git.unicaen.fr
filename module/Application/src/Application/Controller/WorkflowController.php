<?php

namespace Application\Controller;

use Application\Entity\Db\WfEtapeDep;
use Application\Exception\DbException;
use Application\Form\Workflow\Traits\DependanceFormAwareTrait;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\WfEtapeAwareTrait;
use Application\Service\Traits\WfEtapeDepServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Zend\View\Model\ViewModel;

/**
 * Description of WorkflowController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class WorkflowController extends AbstractController implements WorkflowIntervenantAwareInterface
{
    use WorkflowIntervenantAwareTrait;
    use ContextAwareTrait;
    use WfEtapeDepServiceAwareTrait;
    use DependanceFormAwareTrait;
    use WorkflowServiceAwareTrait;
    use WfEtapeAwareTrait;



    public function indexAction()
    {
        return [];
    }

    /**
     * Dessine le bouton pointant vers l'étape située après l'étape dont la route est spécifiée.
     * 
     * @return array
     */
    public function navNextAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            exit;
        }

        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $this->context()->intervenantFromRoute();
        $route       = $this->context()->routeFromQuery();
        $prepend     = $this->context()->prependFromQuery();
        
        if (!$intervenant) {
            exit;
        }
        
        return new ViewModel([ 
            'role'        => $role,
            'intervenant' => $intervenant,
            'route'       => $route,
            'prepend'     => $prepend,
        ]);
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
}