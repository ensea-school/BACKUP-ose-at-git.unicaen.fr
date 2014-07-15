<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Exception\DbException;
use Application\Acl\IntervenantExterieurRole;
use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of ServiceController
 *
 * @method \Doctrine\ORM\EntityManager em()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceController extends AbstractActionController
{
    public function indexAction()
    {
        $totaux = $this->params()->fromQuery('totaux') == '1';
        $service = $this->getServiceService();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        $annee   = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $qb      = $service->finderByContext();
        $viewModel = new \Zend\View\Model\ViewModel();
        $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        $filter    = new \stdClass();
        $title     = "Enseignements et référentiel <small>$annee</small>";
        
        /* Initialisation, si ce n'est pas un intervenant, du formulaire de recherche */
        if (! $role instanceof \Application\Acl\IntervenantRole){
            $intervenant = null;
            $action = $this->getRequest()->getQuery('action', null); // ne pas afficher par défaut, sauf si demandé explicitement
            /*$intervenant = (int)$this->params()->fromRoute('intervenant'); // N'afficher qu'un seul intervenant
            $intervenant = $this->getServiceLocator()->get('ApplicationIntervenant')->get($intervenant);
            if ($intervenant){
                $service->finderByIntervenant( $intervenant, $qb );
                $action = 'afficher'; // Affichage par défaut
            }else{*/
                $params = $this->getEvent()->getRouteMatch()->getParams();
                $params['action'] = 'filtres';
                $listeViewModel   = $this->forward()->dispatch('Application\Controller\Service', $params);
                $viewModel->addChild($listeViewModel, 'filtresListe');
                $rechercheForm = $this->getServiceLocator()->get('FormElementManager')->get('ServiceRecherche');
                /* @var $rechercheForm \Application\Form\Service\Recherche */
                $filter = $rechercheForm->hydrateFromSession();
            //}
            $service->finderByFilterObject($filter, null, $qb);
        }
        else {
            $intervenant = $role->getIntervenant();
            $service->finderByIntervenant($intervenant, $qb);
            $action = 'afficher'; // Affichage par défaut
        }

        // sauvegarde des filtres dans le contexte local
        $this->getContextProvider()->getLocalContext()->fromArray(
                (new \Zend\Stdlib\Hydrator\ObjectProperty())->extract($filter)
        );

        /* Préparation et affichage */
        if ('afficher' === $action || $totaux){
            $services = $service->getList($qb);
            $service->setTypeVolumehoraire($services, $typeVolumeHoraire);

            // services référentiels : délégation au contrôleur
            if (! $totaux){
                $controller       = 'Application\Controller\ServiceReferentiel';
                $params           = $this->getEvent()->getRouteMatch()->getParams();
                $params['action'] = 'voirListe';
                $params['query']  = $this->params()->fromQuery();
                $listeViewModel   = $this->forward()->dispatch($controller, $params);
                $viewModel->addChild($listeViewModel, 'servicesRefListe');
            }
        }else{
            $services = array();
        }

        $renderReferentiel  = !$role instanceof IntervenantExterieurRole && !$intervenant instanceof IntervenantExterieur;

        $viewModel->setVariables(compact('annee', 'services', 'typeVolumeHoraire','action', 'role', 'title', 'intervenant', 'renderReferentiel'));
        if ($totaux){
            $viewModel->setTemplate('application/service/rafraichir-totaux');
        }else{
            $viewModel->setTemplate('application/service/index');
        }
        return $viewModel;
    }

    public function intervenantAction()
    {
        $totaux = $this->params()->fromQuery('totaux',0) == '1';
        $service = $this->getServiceService();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        $annee   = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        $qb      = $service->finderByContext();
        $viewModel = new \Zend\View\Model\ViewModel();
        $intervenant = $this->context()->mandatory()->intervenantFromRoute(); /* @var $intervenant \Application\Entity\Db\Intervenant */
        $service->finderByIntervenant( $intervenant, $qb );

        $service->canAdd($intervenant, true);

        /* Préparation et affichage */
        $services = $service->getList($qb);
        $service->setTypeVolumehoraire($services, $typeVolumeHoraire);

        // services référentiels : délégation au contrôleur
        $this->getContextProvider()->getLocalContext()->setIntervenant($intervenant); // sauvegarde des filtres dans le contexte local
        $controller       = 'Application\Controller\ServiceReferentiel';
        $params           = $this->getEvent()->getRouteMatch()->getParams();
        $listeViewModel   = $this->forward()->dispatch($controller, $params);
        $viewModel->addChild($listeViewModel, 'servicesRefListe');

        $renderIntervenants = false;
        $renderReferentiel  = !$role instanceof IntervenantExterieurRole && !$intervenant instanceof IntervenantExterieur;
        $action = 'afficher';
        $title = "Enseignements <small>$intervenant</small>";

        $viewModel->setVariables(compact('annee', 'services', 'typeVolumeHoraire', 'action', 'role', 'title', 'renderIntervenants', 'renderReferentiel','intervenant'));
        if ($totaux){
            $viewModel->setTemplate('application/service/rafraichir-totaux');
        }else{
            $viewModel->setTemplate('application/service/index');
        }
        return $viewModel;
    }

    /**
     * Totaux de services et de référentiel par intervenant.
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function resumeAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            return $this->redirect()->toRoute('intervenant/services', array('intervenant' => $role->getIntervenant()->getSourceCode()));
        }
        
        $viewModel = new \Zend\View\Model\ViewModel();

        $annee   = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $action = $this->getRequest()->getQuery('action', null);
        $params = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'filtres';
        $listeViewModel   = $this->forward()->dispatch('Application\Controller\Service', $params);
        $viewModel->addChild($listeViewModel, 'filtresListe');

        $rechercheForm = $this->getServiceLocator()->get('FormElementManager')->get('ServiceRecherche');
        /* @var $rechercheForm \Application\Form\Service\Recherche */
        $filter = $rechercheForm->hydrateFromSession();

        // sauvegarde des filtres dans le contexte local
        $this->getContextProvider()->getLocalContext()->fromArray(
                (new \Zend\Stdlib\Hydrator\ObjectProperty())->extract($filter)
        );

        $resumeServices = $this->getServiceLocator()->get('ApplicationService')->getResumeService($filter);

        $canAdd = $this->getServiceService()->canAdd();

        $viewModel->setVariables( compact('annee','action','resumeServices','canAdd') );
        return $viewModel;
    }

    public function resumeRefreshAction()
    {
        $rechercheForm = $this->getServiceLocator()->get('FormElementManager')->get('ServiceRecherche');
        /* @var $rechercheForm \Application\Form\Service\Recherche */
        $filter = $rechercheForm->hydrateFromSession();

        return compact('filter');
    }

    public function filtresAction()
    {
        $role    = $this->getContextProvider()->getSelectedIdentityRole();

        /* Initialisation, si ce n'est pas un intervenant, du formulaire de recherche */
        if (! $role instanceof \Application\Acl\IntervenantRole){
            $rechercheForm = $this->getServiceLocator()->get('FormElementManager')->get('ServiceRecherche');
            /* @var $rechercheForm \Application\Form\Service\Recherche */
            $rechercheForm->populateOptions();
            $rechercheForm->setDataFromSession();
            $rechercheForm->setData( $this->getRequest()->getQuery() );
            if ($rechercheForm->isValid()){
                $rechercheForm->sessionUpdate();
            }
        }
        else {
            $rechercheForm = null; // pas de filtrage
        }
        return compact('rechercheForm', 'role');
    }

    public function voirAction()
    {
        $service = $this->getServiceService();
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de service spécifié.");
        }
        if (!($service = $service->getRepo()->find($id))) {
            throw new RuntimeException("Service '$id' spécifié introuvable.");
        }

        return compact('service');
    }

    public function rafraichirLigneAction()
    {
        $service = $this->context()->serviceFromRoute();
        $typeVolumeHoraire  = $this->context()->typeVolumeHoraireFromRoute();
        $service->setTypeVolumeHoraire($typeVolumeHoraire);

        $details            = 1 == (int)$this->params()->fromQuery('details',               (int)$this->params()->fromPost('details',0));
        $onlyContent        = 1 == (int)$this->params()->fromQuery('only-content',          0);
        $readOnly           = 1 == (int)$this->params()->fromQuery('read-only', 0);

        $intervenant        = $this->params()->fromQuery('intervenant');
        if ('false' === $intervenant) $intervenant = false;
        if ('true' === $intervenant) $intervenant = true;
        if ('' === $intervenant) $intervenant = null;
        $intervenant = $this->getServiceLocator()->get('applicationIntervenant')->get((int)$intervenant);

        $structure        = $this->params()->fromQuery('structure');
        if ('false' === $structure) $structure = false;
        if ('true' === $structure) $structure = true;
        if ('' === $structure) $structure = null;
        $structure = $this->getServiceLocator()->get('applicationStructure')->get((int)$structure);

        return compact('service', 'typeVolumeHoraire', 'details', 'onlyContent', 'readOnly', 'intervenant', 'structure');
    }

    public function suppressionAction()
    {
        $id        = (int) $this->params()->fromRoute('id', 0);
        $service   = $this->getServiceService();
        $entity    = $service->getRepo()->find($id);
        $title     = "Suppression de service";
        $form      = new \Application\Form\Supprimer('suppr');
        $viewModel = new \Zend\View\Model\ViewModel();

        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        if ($this->getRequest()->isPost()) {
            $errors = array();
            try {
                $service->delete($entity);
            }
            catch(\Exception $e){
                $e = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
            $viewModel->setVariable('errors', $errors);
        }

        $viewModel->setVariables(compact('entity', 'context', 'title', 'form'));

        return $viewModel;
    }

    public function saisieAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        $service = $this->getServiceService();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        $form    = $this->getFormSaisie();
        $errors  = array();

        if ($id) {
            $entity = $service->get($id);
            $entity->setTypeVolumeHoraire($typeVolumeHoraire);
            $form->bind($entity);
            $title   = "Modification d'enseignement";
        } else {
            $entity = $service->newEntity();
            $entity->setTypeVolumeHoraire($typeVolumeHoraire);
            $form->bind($entity);
            $form->initFromContext();
            $title   = "Ajout d'enseignement";
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $service->save($entity);
                    $form->get('service')->get('id')->setValue($entity->getId()); // transmet le nouvel ID
                }
                catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }else{
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }
        return compact('form', 'role','errors','title');
    }

    /**
     *
     * @return \Application\Form\Service\Saisie
     */
    protected function getFormSaisie()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('ServiceSaisie');
    }

    /**
     * @return \Application\Service\Service
     */
    public function getServiceService()
    {
        return $this->getServiceLocator()->get('ApplicationService');
    }

    /**
     * @return \Application\Service\TypeVolumeHoraire
     */
    public function getServiceTypeVolumehoraire()
    {
        return $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire');
    }

    /**
     * @return \Application\Service\ContextProvider
     */
    public function getContextProvider()
    {
        return $this->getServiceLocator()->get('ApplicationContextProvider');
    }
}
