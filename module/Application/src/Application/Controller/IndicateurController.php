<?php

namespace Application\Controller;

use Application\Acl\ComposanteRole;
use Application\Controller\Plugin\Context;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\Indicateur as IndicateurService;
use Application\Service\NotificationIndicateur as NotificationIndicateurService;
use Doctrine\ORM\EntityManager;
use Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Opérations autour des notifications.
 *
 * @method EntityManager em()
 * @method Context              context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IndicateurController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * Liste des indicateurs.
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $indicateurs     = $this->getServiceIndicateur()->getList();
        $indicateursImpl = $this->getServiceIndicateur()->getIndicateursImpl($indicateurs, $this->getStructure());
        $serviceNotif    = $this->getServiceNotificationIndicateur();
        $personnel       = $this->getContextProvider()->getGlobalContext()->getPersonnel();
        
        $qb = $serviceNotif->finderByPersonnel($personnel);
        $abonnements = $abonnementsInfos = [];
        foreach ($qb->getQuery()->getResult() as $notificationIndicateur) {
            $indicateur = $notificationIndicateur->getIndicateur();
            $abonnements[$indicateur->getId()] = $notificationIndicateur;
            $abonnementsInfos[$indicateur->getId()] = $notificationIndicateur->getExtraInfos();
        }
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            'indicateurs'      => $indicateurs,
            'indicateursImpl'  => $indicateursImpl,
            'abonnementUrl'    => $this->url()->fromRoute('indicateur/abonner', ['indicateur' => '_indicateur_']),
            'abonnements'      => $abonnements,
            'abonnementsInfos' => $abonnementsInfos,
        ]);
        
        return $viewModel;
    }
    
    /**
     * Affichage du résultat complet renvoyé par un indicateur.
     * 
     * @return ViewModel
     */
    public function resultAction()
    {
        $indicateur     = $this->context()->mandatory()->indicateurFromRoute();
        $indicateurImpl = $this->getServiceIndicateur()->getIndicateurImpl($indicateur, $this->getStructure());
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            'indicateur'     => $indicateur,
            'indicateurImpl' => $indicateurImpl,
        ]);
        
        return $viewModel;
    }
    
    /**
     * Affichage d'un item du résultat renvoyé par un indicateur.
     * 
     * @return ViewModel
     */
    public function resultItemDonneesPersoDiffImportAction()
    {
        $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            'intervenant' => $intervenant,
        ]);
        
        return $viewModel;
    }
    
    /**
     * Réponse aux requêtes AJAX d'abonnement de l'utilisateur connecté aux notifications concernant un indicateur.
     * 
     * @return JsonModel
     */
    public function abonnerAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->redirect()->toRoute('home');
        }
        
        $indicateur   = $this->context()->mandatory()->indicateurFromRoute();
        $frequence    = $this->params()->fromPost('abonnement');
        $personnel    = $this->getContextProvider()->getGlobalContext()->getPersonnel();
        $serviceNotif = $this->getServiceNotificationIndicateur();
        $status       = 'success';
        
        $notificationIndicateur = null;
        try {
            $notificationIndicateur = $serviceNotif->abonner($personnel, $indicateur, $frequence, $this->getStructure());
            $message = $serviceNotif->getMessage(PHP_EOL);
        }
        catch (Exception $e) {
            $status  = 'failure';
            $message = "Abonnement de $personnel impossible: {$e->getMessage()})";
        }
        
        return new JsonModel([
            'status'  => $status,
            'message' => $message,
            'infos'   => $notificationIndicateur ? $notificationIndicateur->getExtraInfos() : null,
        ]);
    }
    
    /**
     * @return StructureEntity
     */
    private function getStructure()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof ComposanteRole) {
            return $role->getStructure();
        }
        
        return null;
    }
    
    /**
     * @return IndicateurService
     */
    private function getServiceIndicateur()
    {
        return $this->getServiceLocator()->get('IndicateurService');
    }
    
    /**
     * @return NotificationIndicateurService
     */
    private function getServiceNotificationIndicateur()
    {
        return $this->getServiceLocator()->get('NotificationIndicateurService');
    }
}