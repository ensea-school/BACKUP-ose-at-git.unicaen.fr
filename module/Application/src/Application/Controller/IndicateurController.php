<?php

namespace Application\Controller;

use Application\Entity\Db\Structure;
use Application\Entity\Db\VIndicModifDossier;
use Application\Service\Indicateur as IndicateurService;
use Application\Entity\Db\Indicateur;
use Application\Service\Traits\AffectationAwareTrait;
use Application\Service\Traits\NotificationIndicateurAwareTrait;
use Doctrine\ORM\Query\Expr\Join;
use Exception;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;


class IndicateurController extends AbstractController
{
    use \Application\Service\Traits\IndicateurServiceAwareTrait;
    use \Application\Service\Traits\IntervenantAwareTrait;
    use \Application\Service\Traits\ContextAwareTrait;
    use NotificationIndicateurAwareTrait;
    use AffectationAwareTrait;



    /**
     * Liste des indicateurs.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $indicateurs   = $this->getServiceIndicateur()->getList();
        $notifications = $this->getServiceNotificationIndicateur()->getList(
            $this->getServiceNotificationIndicateur()->finderByRole()
        );

        $abonnements = [];
        foreach ($notifications as $notification) {
            $abonnements[$notification->getIndicateur()->getId()] = $notification;
        }

        return compact('indicateurs', 'abonnements');
    }



    public function resultAction()
    {
        $role       = $this->getServiceContext()->getSelectedIdentityRole();
        $indicateur = $this->getEvent()->getParam('indicateur');
        /* @var $indicateur Indicateur */
        $indicateur->setServiceIndicateur($this->getServiceIndicateur());

        $structure = $role->getStructure() ?: $this->getEvent()->getParam('structure');

        /* @var $structure Structure */

        return compact('indicateur', 'structure');
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
        $indicateur->setServiceIndicateur($this->getServiceIndicateur());
        /* @var $indicateur Indicateur */
        $indicateur->setServiceIndicateur($this->getServiceIndicateur());
        $structure = $role->getStructure() ?: $this->getEvent()->getParam('structure');

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

        $indicateur = $this->getEvent()->getParam('indicateur');
        $frequence  = $this->params()->fromPost('notification');
        $inHome     = $this->params()->fromPost('in-home') == '1';

        $serviceNotif = $this->getServiceNotificationIndicateur();

        try {
            $notificationIndicateur = $serviceNotif->abonner($indicateur, $frequence, $inHome);
            $status                 = 'success';
            $message                = 'Demande prise en compte';
            if (!$notificationIndicateur) {
                $message .= ' (Abonnement supprimé)';
            }
        } catch (Exception $e) {
            $notificationIndicateur = null;
            $status                 = 'error';
            $message                = "Abonnement impossible: {$e->getMessage()}";
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
        $sab = $this->getServiceNotificationIndicateur();
        $saf = $this->getServiceAffectation();
        $sid = $this->getServiceIndicateur();

        $qb = $sab->finderByRole(); // filtre selon le rôle courant
        $sab->join($sid, $qb, 'indicateur', true);
        $sab->finderByInHome(true, $qb);

        $sab->join($saf, $qb, 'affectation');
        $saf->finderByHistorique($qb);

        $sid->orderBy($qb);

        $notifications = $sab->getList($qb);

        $indicateurs = [];
        foreach( $notifications as $notification ){
            $indicateurs[] = $notification->getIndicateur()->setServiceIndicateur($sid);
        }

        return compact('indicateurs');
    }

}