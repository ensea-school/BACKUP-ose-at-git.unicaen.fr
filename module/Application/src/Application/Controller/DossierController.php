<?php

namespace Application\Controller;

use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Dossier;
use Application\Entity\Db\Listener\DossierListener;

/**
 * Description of DossierController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierController extends AbstractActionController implements \Application\Service\ContextProviderAwareInterface
{
    use \Application\Service\ContextProviderAwareTrait;

    public function voirAction()
    {
        $role        = $this->getContextProvider()->getSelectedIdentityRole();
        $intervenant = $this->context()->mandatory()->intervenantFromRoute('id');
        $dossier     = $intervenant->getDossier();
        $title       = "Détails d'un dossier <small>$intervenant</small>";
        $short       = $this->params()->fromQuery('short', false);
        $view        = new \Zend\View\Model\ViewModel();

        if (!$dossier) {
            throw new \Common\Exception\MessageException("L'intervenant $intervenant n'a aucun dossier.");
        }
        
        $view->setVariables(compact('intervenant', 'dossier', 'title', 'short'));
        
        return $view;
    }
    
    /**
     * Modification du dossier d'un intervenant.
     * 
     * @return type
     * @throws RuntimeException
     */
    public function modifierAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        $form = $this->getFormModifier();
        
        if ($role instanceof \Application\Acl\DossierRole) {
            $intervenant = $role->getIntervenant();
        }
        else {
            $intervenant = $this->context()->mandatory()->intervenantFromRoute('id');
        }
        
        if (!$intervenant instanceof \Application\Entity\Db\IntervenantExterieur) {
            throw new RuntimeException("La saisie de dossier n'est possible pour un dossier extérieur.");
        }
        
        if (!($dossier = $intervenant->getDossier())) {
            $dossier = $this->getDossierService()->newEntity()->fromIntervenant($intervenant);
            $intervenant->setDossier($dossier);
        }
        
        $form->bind($intervenant);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDossierService()->save($dossier);
                $notified = $this->notify($intervenant);
                $this->getIntervenantService()->save($intervenant);
                $this->flashMessenger()->addSuccessMessage("Dossier enregistré avec succès.");
                if ($notified) {
                    $this->flashMessenger()->addInfoMessage("Un mail doit être envoyé pour informer la composante de la modification du dossier...");
                }
                return $this->redirect()->toUrl($this->url()->fromRoute(null, array(), array(), true));
            }
//            var_dump('not valid', $form->getMessages());
        }
        
        return compact('intervenant', 'form');
    }
    
    protected function notify(Intervenant $intervenant)
    {
        if (DossierListener::$created || DossierListener::$modified) {
            // envoyer un mail au gestionnaire
            var_dump('Envoi de mail "dossier créé ou modifié"...');
            
            return true;
        }
        
        return false;
    }
    
    /**
     * @return \Application\Form\Intervenant\Dossier
     */
    protected function getFormModifier()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('IntervenantDossier');
    }
    
    /**
     * @return \Application\Service\Intervenant
     */
    protected function getIntervenantService()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }
    
    /**
     * @return \Application\Service\Dossier
     */
    protected function getDossierService()
    {
        return $this->getServiceLocator()->get('ApplicationDossier');
    }
}