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
    
    public function iesAction()
    {
        $service = $this->getServiceLocator()->get('WfIntervenantEtapeService'); /* @var $service \Application\Service\WfIntervenantEtape */
        
        $service->createIntervenantsEtapes();
        
        die('Done!');
    }
    
//    public function intervenantsAction()
//    {
//        $role     = $this->getContextProvider()->getSelectedIdentityRole();
//        $wf       = $this->getWorkflowIntervenant()->setRole($role); /* @var $wf \Application\Service\Workflow\WorkflowIntervenant */
//        $wfQb     = $this->getServiceLocator()->get('WorkflowQueryBuilder')->setWorkflowIntervenant($wf);
//        $stepKeys = [
////            WorkflowIntervenant::DONNEES_PERSO_SAISIE, 
////            WorkflowIntervenant::SERVICE_SAISIE, 
////            WorkflowIntervenant::PIECES_JOINTES, 
////            WorkflowIntervenant::DONNEES_PERSO_VALIDATION,
////            WorkflowIntervenant::SERVICE_VALIDATION,
//            WorkflowIntervenant::CONSEIL_RESTREINT,
////            WorkflowIntervenant::CONSEIL_ACADEMIQUE,
////            WorkflowIntervenant::CONTRAT,
//        ];
//        
//        echo '<pre>' . print_r($wfQb->getNotCrossingQuerySQL(WorkflowIntervenant::CONSEIL_RESTREINT), true) . '</pre>';
//        die;
//        $qb = $this->em()->getRepository('Application\Entity\Db\Intervenant')->createQueryBuilder("i")
//                ->where($this->em()->getExpressionBuilder()->in("i.id", ":ids"))
//                ->orderBy("i.nomUsuel, i.prenom");
//        
//        $data = new \SplObjectStorage();
//        foreach ($stepKeys as $stepKey) {
//            $step         = $wf->getStep($stepKey);
//            
//            $result       = $wfQb->executeNotCrossingQuerySQL($stepKey);
//            $intervenants = $qb->setParameter('ids', array_keys($result))->getQuery()->getResult();
////            $result       = $wfQb->executeNotCrossingCountQuerySQL($stepKey);
////            $intervenants = [];
////            var_dump($result);
//            
//            $data->attach($step, $intervenants);
//            
//        }
//        
//        return new ViewModel([ 
//            'role' => $role,
//            'wf'   => $wf,
//            'data' => $data,
//        ]);
//    }
}
