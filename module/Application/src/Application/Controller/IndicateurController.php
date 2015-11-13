<?php

namespace Application\Controller;

use Application\Controller\Plugin\Context;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\VIndicModifDossier;
use Application\Service\Indicateur as IndicateurService;
use Application\Service\NotificationIndicateur as NotificationIndicateurService;
use Doctrine\ORM\EntityManager;
use Application\Entity\Db\Indicateur;
use Doctrine\ORM\Query\Expr\Join;
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
class IndicateurController extends AbstractActionController
{
    use \Application\Service\Traits\IndicateurServiceAwareTrait;
    use \Application\Service\Traits\IntervenantAwareTrait;
    use \Application\Service\Traits\ContextAwareTrait;


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
        $personnel       = $this->getServiceContext()->getSelectedIdentityRole()->getPersonnel();
        
        $qb = $serviceNotif->finderByPersonnel($personnel);
        if ($this->getStructure()) {
            $qb = $serviceNotif->finderByStructure($this->getStructure(), $qb);
        }
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
        $role       = $this->getServiceContext()->getSelectedIdentityRole();
        $indicateur = $this->getEvent()->getParam('indicateur');
        $structure  = $role->getStructure() ?: $this->getEvent()->getParam('structure');

        $indicateurImpl = $this->getServiceIndicateur()->getIndicateurImpl($indicateur, $structure);
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            'indicateur'     => $indicateur,
            'indicateurImpl' => $indicateurImpl,
        ]);

        return $viewModel;
    }

    /**
     * Affichage du résultat complet renvoyé par un indicateur.
     *
     * @return ViewModel
     */
    public function detailsAction()
    {
        $role       = $this->getServiceContext()->getSelectedIdentityRole();
        $indicateur = $this->getEvent()->getParam('indicateur');
        /* @var $indicateur Indicateur */
        $indicateur->setServiceIndicateur($this->getServiceIndicateur());
        $structure  = $role->getStructure() ?: $this->getEvent()->getParam('structure');

        return compact('indicateur');
    }

    /**
     * Affichage d'un item du résultat renvoyé par l'indicateur "DonneesPersoDiffImport".
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
     * Affichage d'un item du résultat renvoyé par l'indicateur "DonneesPersoModif".
     *
     * @return ViewModel
     */
    public function resultItemDonneesPersoModifAction()
    {
        $indicateur  = $this->getServiceIndicateur()->getByCode(Indicateur::CODE_DONNEES_PERSO_MODIF);
        $intervenant = $this->context()->mandatory()->intervenantFromRoute();

        // refetch intervenant avec jointures
        $qb = $this->getServiceIntervenant()->getRepo()->createQueryBuilder("int")
            ->addSelect("vi")
            ->leftJoin("int.indicModifDossier", "vi", Join::WITH, "1 = pasHistorise(vi)")
            ->addOrderBy("vi.attrName, vi.histoCreation")
            ->andWhere("int = :intervenant")
            ->setParameter('intervenant', $intervenant);

        $intervenant = $qb->getQuery()->getOneOrNullResult();

        $viewModel = new ViewModel();
        $viewModel->setVariables([
            'title'       => "Historique des modifications d'informations importantes dans les données personnelles",
            'indicateur'  => $indicateur,
            'intervenant' => $intervenant,
        ]);

        return $viewModel;
    }

    /**
     * Suppression de l'historique des modifications sur les données personnelles.
     *
     * @return ViewModel
     */
    public function purgerIndicateurDonneesPersoModifAction()
    {
        $intervenant = $this->context()->mandatory()->intervenantFromRoute();

        $result = $this->confirm()->execute();

        if (is_array($result)) { // confirmation postée
            $utilisateur = $this->identity()['db'];
            $this->getServiceIndicateur()->purgerIndicateurDonneesPersoModif($intervenant, $utilisateur);

            $this->flashMessenger()->addSuccessMessage(sprintf(
                "L'historique des modifications d'informations importantes dans les données personnelles de %s a été effacé avec succès.",
                $intervenant));
        }

        $viewModel = $this->confirm()->getViewModel();

        $viewModel->setVariables([
            'title'       => "Effacement de l'historique",
            'message'     => "Confirmez-vous l'effacement de l'historique des modifications d'informations importantes dans les données personnelles de $intervenant ?",
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
        $personnel    = $this->getServiceContext()->getSelectedIdentityRole()->getPersonnel();
        $serviceNotif = $this->getServiceNotificationIndicateur();
        $status       = 'success';
        
        $notificationIndicateur = null;
        try {
            $notificationIndicateur = $serviceNotif->abonner($personnel, $indicateur, $frequence, $this->getStructure());
            $message = $serviceNotif->getMessage(PHP_EOL);
        }
        catch (Exception $e) {
            $status  = 'error';
            $message = "Abonnement de $personnel impossible: {$e->getMessage()}";
        }
        
        return new JsonModel([
            'status'  => $status,
            'message' => $message,
            'infos'   => $notificationIndicateur ? $notificationIndicateur->getExtraInfos() : null,
        ]);
    }
    
    /**
     * Indicateurs auxquels est abonné l'utilisateur (un Personnel) spécifié dans la requête.
     * 
     * @return ViewModel
     */
    public function abonnementsAction()
    {
        $personnel    = $this->context()->mandatory()->personnelFromRoute();
        $serviceNotif = $this->getServiceNotificationIndicateur();
        
        $qb = $serviceNotif->finderByPersonnel($personnel);
        $qb
                ->join("ni.indicateur", "i")
                ->orderBy("i.type, i.ordre");
        $abonnements = $abonnementsInfos = $indicateurs = [];
        foreach ($qb->getQuery()->getResult() as $notificationIndicateur) {
            $indicateur = $notificationIndicateur->getIndicateur();
            $indicateurs[$indicateur->getId()] = $indicateur;
            $abonnements[$indicateur->getId()] = $notificationIndicateur;
            $abonnementsInfos[$indicateur->getId()] = $notificationIndicateur->getExtraInfos();
        }
        
        $indicateursImpl = $this->getServiceIndicateur()->getIndicateursImpl($indicateurs, $this->getStructure());
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            'indicateurs'      => $indicateurs,
            'indicateursImpl'  => $indicateursImpl,
            'abonnements'      => $abonnements,
            'abonnementsInfos' => $abonnementsInfos,
        ]);
        
        return $viewModel;
    }
    
    /**
     * @return StructureEntity
     */
    private function getStructure()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        return $role->getStructure();
    }
    
    /**
     * @return NotificationIndicateurService
     */
    private function getServiceNotificationIndicateur()
    {
        return $this->getServiceLocator()->get('NotificationIndicateurService');
    }
}