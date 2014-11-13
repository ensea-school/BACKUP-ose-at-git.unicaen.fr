<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\ContextProviderAwareInterface;
use Common\Exception\RuntimeException;
use Application\Entity\Db\IntervenantPermanent;
use Common\Exception\MessageException;
use Application\Acl\ComposanteRole;

/**
 * Description of IntervenantController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Intervenant intervenant()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ModificationServiceDuController extends AbstractActionController implements ContextProviderAwareInterface
{
    use \Application\Service\ContextProviderAwareTrait;
    
    /**
     * 
     */
    public function saisirAction()
    {
        $context     = $this->getContextProvider()->getGlobalContext();
        $isAjax      = $this->getRequest()->isXmlHttpRequest();
        $intervenant = $this->context()->mandatory()->intervenantFromRoute(); /* @var $intervenant IntervenantPermanent */
        $role        = $this->getContextProvider()->getSelectedIdentityRole();
        
        $rule = $this->getServiceLocator()->get('PeutSaisirModificationServiceDuRule')
                ->setIntervenant($intervenant)
                ->setStructure($role instanceof ComposanteRole ? $role->getStructure() : null);
        if (!$rule->execute()) {
                        var_dump($rule->getMessage());
            throw new MessageException("La modification de service dû n'est pas possible. ", null, new \Exception($rule->getMessage()));
        }
        
        $this->em()->getFilters()->enable("historique");
        
        // fetch intervenant avec jointure sur les modifs de service dû
        $qb = $this->getServiceIntervenant()->getFinderIntervenantPermanentWithModificationServiceDu();
        $qb->setIntervenant($intervenant);
        $intervenant = $qb->getQuery()->getOneOrNullResult(); /* @var $intervenant IntervenantPermanent */
        
        $annee = $context->getAnnee();
        
        // NB: patch pour permettre de vider toutes les modifs de service dû
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            if (empty($data['fs']['modificationServiceDu'])) {
                foreach ($intervenant->getModificationServiceDu($annee) as $sr) {
                    $sr->setHistoDestruction(new \DateTime());
                    $this->em()->persist($sr);
                    $this->em()->flush();
                }
                $this->em()->refresh($intervenant);
            }
        }
        
        $form = $this->getServiceLocator()->get('form_element_manager')->get('IntervenantModificationServiceDuForm');
        /* @var $form \Application\Form\Intervenant\ModificationServiceDuForm */
        $form->setAttribute('action', $this->getRequest()->getRequestUri());
        $form->getBaseFieldset()->getHydrator()->setAnnee($annee);
        $form->bind($intervenant);
        
        $variables = array(
            'form' => $form, 
            'intervenant' => $intervenant,
            'title' => "Modifications de service dû <small>$intervenant</small>",
        );
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            if (empty($data['fs']['modificationServiceDu'])) {
                $data['fs']['modificationServiceDu'] = array();
            }
//            var_dump($data);
            $form->setData($data);
            if ($form->isValid()) {
                try {
                    $this->em()->flush();
                    if ($isAjax) {
                        exit;
                    }
                    $this->flashMessenger()->addSuccessMessage(sprintf("Modifications de service dû de $intervenant enregistrées avec succès."));
                    $this->redirect()->toRoute(null, array(), array(), true);
                }
                catch (\Doctrine\DBAL\DBALException $exc) {
                    $exception = new RuntimeException("Impossible d'enregistrer les modifications de service dû.", null, $exc->getPrevious());
                    $variables['exception'] = $exception;
//                    var_dump($exc->getMessage(), $exc->getTraceAsString());
                }
            }
        }
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables($variables);

        $variables['context'] = $context;
                
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables($variables);
        
        return $viewModel;
    }

    /**
     * 
     * @param bool $import Importer l'intervenant si besoin ?
     * @return \Application\Entity\Db\Intervenant
     * @throws RuntimeException Intervenant spécifié par son code source introuvable
     */
//    protected function getIntervenant($import = true)
//    {
//        $sourceCode  = $this->params()->fromQuery('sourceCode', $this->params('id'));
//        $intervenant = null;
//        
//        if ($sourceCode) {
//            // test d'existence de l'intervenant et import éventuel
//            $intervenant = $this->em()->getRepository('Application\Entity\Db\Intervenant')->findOneBySourceCode($sourceCode);
//            if (!$intervenant) {
//                if (!$import) {
//                    throw new RuntimeException("Intervenant spécifié introuvable (sourceCode = $sourceCode).");
//                }
//                // import de l'intervenant
//                $intervenant = $this->getServiceLocator()->get('ApplicationIntervenant')->importer($sourceCode);
//            }
//            
//            return $intervenant;
//        }
//        
//        $context = $this->getContextProvider()->getLocalContext();
//        $role    = $this->getContextProvider()->getSelectedIdentityRole();
//        
//        if ($role instanceof \Application\Acl\IntervenantRole) {
//            $intervenant = $role->getIntervenant();
//        }
//        
//        if ($role instanceof \Application\Acl\DbRole) {
//            // récupère l'éventuel intervenant du contexte local
//            $intervenant = $context->getIntervenant();
//        }
//        
//        return $intervenant;
//    }
//    
//    /**
//     * Redirection vers le choix d'un intervenant (action qui redirigera vers l'action 
//     * courante une fois l'intervenant choisi).
//     * 
//     * @param \Application\Entity\Db\Intervenant $intervenant Intervenant pré-choisi
//     * @return \Zend\Http\Response
//     * @see IntervenantController
//     */
//    protected function redirectToChoisirIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
//    {
//        $modal    = $this->params()->fromQuery('modal');
//        $redirect = $this->url()->fromRoute(
//                null, 
//                array(), 
//                array('query' => array('sourceCode' => '__sourceCode__', 'modal' => $modal)), 
//                true);
//        
//        if ($intervenant) {
//           $intervenant = $intervenant->getSourceCode();
//        }
//        
//        return $this->redirect()->toRoute(
//                'intervenant/default', 
//                array('action' => 'choisir'), 
//                array('query' => array('intervenant' => $intervenant, 'redirect' => $redirect, 'modal' => $modal)));
//    }
    
    /**
     * @return \Application\Service\Intervenant
     */
    public function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }
}