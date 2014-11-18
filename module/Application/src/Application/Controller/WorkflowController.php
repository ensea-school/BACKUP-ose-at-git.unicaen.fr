<?php

namespace Application\Controller;

use Application\Service\Workflow\WorkflowIntervenant;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of WorkflowController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class WorkflowController extends AbstractActionController implements ContextProviderAwareInterface, WorkflowIntervenantAwareInterface
{
    use ContextProviderAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
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
        
        $role        = $this->getContextProvider()->getSelectedIdentityRole();
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
    
    public function intervenantsAction()
    {
        $role     = $this->getContextProvider()->getSelectedIdentityRole();
        $wf       = $this->getWorkflowIntervenant()->setRole($role); /* @var $wf \Application\Service\Workflow\WorkflowIntervenant */
        $wfQb     = $this->getServiceLocator()->get('WorkflowQueryBuilder')->setWorkflowIntervenant($wf);
        $stepKeys = [
//            WorkflowIntervenant::KEY_DONNEES_PERSO_SAISIE, 
//            WorkflowIntervenant::KEY_SERVICE_SAISIE, 
//            WorkflowIntervenant::KEY_PIECES_JOINTES, 
//            WorkflowIntervenant::KEY_DONNEES_PERSO_VALIDATION,
//            WorkflowIntervenant::KEY_SERVICE_VALIDATION,
            WorkflowIntervenant::KEY_CONSEIL_RESTREINT,
//            WorkflowIntervenant::KEY_CONSEIL_ACADEMIQUE,
//            WorkflowIntervenant::KEY_CONTRAT,
        ];
        
        echo '<pre>' . print_r($wfQb->getNotCrossingQuerySQL(WorkflowIntervenant::KEY_CONSEIL_RESTREINT), true) . '</pre>';
        die;
        $qb = $this->em()->getRepository('Application\Entity\Db\Intervenant')->createQueryBuilder("i")
                ->where($this->em()->getExpressionBuilder()->in("i.id", ":ids"))
                ->orderBy("i.nomUsuel, i.prenom");
        
        $data = new \SplObjectStorage();
        foreach ($stepKeys as $stepKey) {
            $step         = $wf->getStep($stepKey);
            
            $result       = $wfQb->executeNotCrossingQuerySQL($stepKey);
            $intervenants = $qb->setParameter('ids', array_keys($result))->getQuery()->getResult();
//            $result       = $wfQb->executeNotCrossingCountQuerySQL($stepKey);
//            $intervenants = [];
//            var_dump($result);
            
            $data->attach($step, $intervenants);
            
        }
        
        return new ViewModel([ 
            'role' => $role,
            'wf'   => $wf,
            'data' => $data,
        ]);
    }
}