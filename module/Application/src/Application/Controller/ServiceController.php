<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Exception\DbException;
use Application\Entity\Db\Intervenant;
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
    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')
                ->disableForEntity('Application\Entity\Db\ElementPedagogique')
                ->disableForEntity('Application\Entity\Db\Etape')
                ->disableForEntity('Application\Entity\Db\Etablissement');
    }

    /**
     *
     * @param Intervenant|null $intervenant
     * @param StdClass|null $filter
     * @return \Application\Entity\Db\Service[]
     */
    private function getFilteredServices($intervenant, $filter)
    {
        $typeVolumeHoraire         = $this->getServiceTypeVolumehoraire()->getPrevu();
        $role                      = $this->getContextProvider()->getSelectedIdentityRole();

        $service                   = $this->getServiceService();
        $volumeHoraireService      = $this->getServiceLocator()->get('applicationVolumehoraire');       /* @var $volumeHoraireService \Application\Service\VolumeHoraire */
        $elementPedagogiqueService = $this->getServiceLocator()->get('applicationElementPedagogique');  /* @var $elementPedagogiqueService \Application\Service\ElementPedagogique */

        $this->initFilters();
        $qb = $service->initQuery()[0];

        $service
            ->join(     'applicationIntervenant',       $qb, 'intervenant',         true )
            ->leftJoin( $elementPedagogiqueService,     $qb, 'elementPedagogique',  true )
            ->leftjoin( $volumeHoraireService,          $qb, 'volumeHoraire',       true );

        $elementPedagogiqueService
            ->leftJoin( 'applicationEtape',             $qb, 'etape',               true )
            ->leftJoin( 'applicationPeriode',           $qb, 'periode',             true )
            ->leftJoin( 'applicationTypeIntervention',  $qb, 'typeIntervention',    true );

        $volumeHoraireService
            ->leftJoin( 'applicationMotifNonPaiement',  $qb, 'motifNonPaiement',    true );

        $service->finderByContext($qb);
        $service->finderByFilterObject($filter, null, $qb);
        if ($intervenant){
            $service->finderByIntervenant($intervenant, $qb);
        }
        if (! $intervenant && $role instanceof \Application\Acl\ComposanteRole){
            $service->finderByComposante($role->getStructure(), $qb);
        }
        $services = $service->getList($qb);
        $service->setTypeVolumehoraire($services, $typeVolumeHoraire);
        return $services;
    }

    public function indexAction()
    { 
        $totaux             = $this->params()->fromQuery('totaux', 0) == '1';
        $role               = $this->getContextProvider()->getSelectedIdentityRole();
        $intervenant        = $this->context()->intervenantFromRoute();
        $annee              = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $viewModel          = new \Zend\View\Model\ViewModel();
        $typeVolumeHoraire  = $this->getServiceTypeVolumehoraire()->getPrevu();
        $canAddService      = true;//$this->isAllowed($this->getServiceService()->newEntity()->setIntervenant($intervenant), 'create');
        $canAddServiceReferentiel = true;
//                ! $intervenant instanceof IntervenantExterieur
//                && $this->isAllowed($this->getServiceServiceReferentiel()->newEntity()->setIntervenant($intervenant), 'create');

        if (! $this->isAllowed($this->getServiceService()->newEntity()->setIntervenant($intervenant), 'read')){
            throw new \BjyAuthorize\Exception\UnAuthorizedException();
        }

        if (! $intervenant){
            $action = $this->getRequest()->getQuery('action', null); // ne pas afficher par défaut, sauf si demandé explicitement
            $params = $this->getEvent()->getRouteMatch()->getParams();
            $params['action'] = 'filtres';
            $listeViewModel   = $this->forward()->dispatch('Application\Controller\Service', $params);
            $viewModel->addChild($listeViewModel, 'filtresListe');
            
            $filter = $this->getFormServiceRecherche()->hydrateFromSession();
            $this->getContextProvider()->getLocalContext()->fromArray( // sauvegarde des filtres dans le contexte local
                (new \Zend\Stdlib\Hydrator\ObjectProperty())->extract($filter)
            );
        }else{
            $this->getContextProvider()->getLocalContext()->setIntervenant($intervenant); // passage au contexte pour le présaisir dans le formulaire de saisie
            $action = 'afficher'; // Affichage par défaut
            $filter = null;

            //$params           = $this->getEvent()->getRouteMatch()->getParams();
            $params           = [];
            $params['intervenant'] = $intervenant->getSourceCode();
            $params['action'] = 'total-heures-comp';
            $totalViewModel   = $this->forward()->dispatch('Application\Controller\Intervenant', $params);
            $viewModel->addChild($totalViewModel, 'totalHeuresComp');
        }

        /* Préparation et affichage */
        if ('afficher' === $action || $totaux){
            $services = $this->getFilteredServices($intervenant, $filter);

            // services référentiels : délégation au contrôleur
            if (! $totaux){
                $controller       = 'Application\Controller\ServiceReferentiel';
                $params           = $this->getEvent()->getRouteMatch()->getParams();
                $params['action'] = 'voirListe';
                $params['filter'] = $filter;
                $params['query']  = $this->params()->fromQuery();
                $params['renderIntervenants'] = ! $intervenant;
                $listeViewModel   = $this->forward()->dispatch($controller, $params);
                $viewModel->addChild($listeViewModel, 'servicesRefListe');
            }
        }else{
            $services = [];
        }
        $renderReferentiel  = !$intervenant instanceof IntervenantExterieur;
        $viewModel->setVariables(compact('annee', 'services', 'typeVolumeHoraire','action', 'role', 'intervenant', 'renderReferentiel','canAddService', 'canAddServiceReferentiel'));
        if ($totaux){
            $viewModel->setTemplate('application/service/rafraichir-totaux');
        }else{
            $viewModel->setTemplate('application/service/index');
        }
        return $viewModel;
    }

    public function exportAction()
    {
        $intervenant        = $this->context()->intervenantFromRoute();

        if (! $this->isAllowed($this->getServiceService()->newEntity()->setIntervenant($intervenant), 'read')){
            throw new \BjyAuthorize\Exception\UnAuthorizedException();
        }

        $this->initFilters();
        if ($intervenant){
            $this->getContextProvider()->getLocalContext()->setIntervenant($intervenant);
        }

        if (! $intervenant){
            $this->filtresAction();
            $filter = $this->getFormServiceRecherche()->hydrateFromSession();
        }else{
            $filter = null;
        }

        /* Préparation et affichage */

        $services = $this->getFilteredServices($intervenant, $filter);

        $csvModel = new \UnicaenApp\View\Model\CsvModel();
        $head = [
            'Code Intervenant',
            'Intervenant',
            'Statut',
            'Type',
            'Fc. Référentiel',
            'Structure d\'affectation',
            'Composante d\'enseignement',
            'Code formation',
            'Formation ou établissement',
            'Code enseignement',
            'Enseignement',
        ];
        $typesIntervention = [];
        foreach( $services as $service ){
            $tis = $service->getVolumeHoraireListe()->getTypesIntervention();
            foreach( $tis as $ti ){
                if (! isset($typesIntervention[$ti->getId()])){
                    $typesIntervention[$ti->getId()] = $ti;
                }
            }

        }
        usort( $typesIntervention, function($ti1,$ti2){
            return $ti1->getOrdre() > $ti2->getOrdre();
        } );
        foreach( $typesIntervention as $typeIntervention ){
            $head[] = $typeIntervention->getCode();
        }

        $csvModel->setHeader( $head );
        foreach( $services as $service ){ /* @var $service \Application\Entity\Db\Service */
            $line = [
                'code_intervenant'  => $service->getIntervenant()->getSourceCode(),
                'intervenant'       => (string)$service->getIntervenant(),
                'statut'            => (string)$service->getIntervenant()->getStatut(),
                'type'              => (string)$service->getIntervenant()->getStatut()->getTypeIntervenant(),
                'referentiel'       => $this->getServiceLocator()->get('ProcessFormuleHetd')->getServiceReferentiel($service->getIntervenant()),
                'str_aff'           => (string)$service->getStructureAff(),
                'str_ens'           => (string)$service->getStructureEns(),
                'code_etape'        => $service->getElementPedagogique() ? $service->getElementPedagogique()->getEtape()->getSourceCode() : '',
                'etape'             => $service->getElementPedagogique() ? (string)$service->getElementPedagogique()->getEtape() : (string)$service->getEtablissement(),
                'code_element'      => $service->getElementPedagogique() ? $service->getElementPedagogique()->getSourceCode() : '',
                'element'           => $service->getElementPedagogique() ? (string)$service->getElementPedagogique() : '',
            ];
            foreach( $typesIntervention as $typeIntervention ){
                $line[$typeIntervention->getCode()] = $service->getVolumeHoraireListe(null, $typeIntervention)->getHeures();
            }
            $csvModel->addLine($line);
        }
        $csvModel->setFilename('service.csv');
        return $csvModel;
    }

    /**
     * Totaux de services et de référentiel par intervenant.
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function resumeAction()
    {
        $intervenant        = $this->context()->intervenantFromRoute();
        $canAddService      = $this->isAllowed($this->getServiceService()->newEntity()->setIntervenant($intervenant), 'create');

        $this->initFilters();

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

        $filter = $this->getFormServiceRecherche()->hydrateFromSession();

        // sauvegarde des filtres dans le contexte local
        $this->getContextProvider()->getLocalContext()->fromArray(
                (new \Zend\Stdlib\Hydrator\ObjectProperty())->extract($filter)
        );

        if ('afficher' == $action ){
            $resumeServices = $this->getServiceService()->getResumeService($filter);
        }else{
            $resumeServices = null;
        }
   
        $viewModel->setVariables( compact('annee','action','resumeServices','canAddService') );
        return $viewModel;
    }

    public function resumeRefreshAction()
    {
        $this->initFilters();

        $filter = $this->getFormServiceRecherche()->hydrateFromSession();

        return compact('filter');
    }

    public function filtresAction()
    {
        /* Initialisation, si ce n'est pas un intervenant, du formulaire de recherche */
        $rechercheForm = $this->getFormServiceRecherche();
        $rechercheForm->populateOptions();
        $rechercheForm->setDataFromSession();
        $rechercheForm->setData( $this->getRequest()->getQuery() );
        if ($rechercheForm->isValid()){
            $rechercheForm->sessionUpdate();
        }
        return compact('rechercheForm');
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
        $this->initFilters();

        $service = $this->context()->serviceFromRoute();
        $typeVolumeHoraire  = $this->context()->typeVolumeHoraireFromRoute();
        $service->setTypeVolumeHoraire($typeVolumeHoraire);

        $details            = 1 == (int)$this->params()->fromQuery('details',               (int)$this->params()->fromPost('details',0));
        $onlyContent        = 1 == (int)$this->params()->fromQuery('only-content',          0);
        $readOnly           = 1 == (int)$this->params()->fromQuery('read-only',             0);
        $typesIntervention  = $this->params()->fromQuery('types-intervention',              null);
        if ($typesIntervention){
            $typesIntervention  = explode(',',$typesIntervention);
            $typesIntervention = $this->getServiceTypeIntervention()->getByCode($typesIntervention);
        }else{
            $typesIntervention = [];
        }

        $tiv = $this->params()->fromQuery('types-intervention-visibility', $this->params()->fromPost('types-intervention-visibility',null) );
        $typesInterventionVisibility = [];
        if ($tiv){
            $tiv = explode(',',$tiv);
            foreach( $typesIntervention as $ti ){
                $typesInterventionVisibility[$ti->getCode()] = in_array($ti->getCode(), $tiv);
            }
        }

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

        return compact('service', 'typeVolumeHoraire', 'details', 'onlyContent', 'readOnly', 'intervenant', 'structure', 'typesIntervention', 'typesInterventionVisibility');
    }

    public function volumesHorairesRefreshAction()
    {
        $this->initFilters();

        $id = (int)$this->params()->fromRoute('id');
        $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        $service = $this->getServiceService();
        $form    = $this->getFormSaisie();
        $element = $this->context()->elementPedagogiqueFromPost('element');
        $etablissement = $this->context()->etablissementFromPost();

        if ($id) {
            $entity = $service->get($id); /* @var $entity \Application\Entity\Db\Service */
        } else {
            $entity = $service->newEntity();
        }
        $entity->setTypeVolumeHoraire($typeVolumeHoraire);
        $entity->setEtablissement($etablissement);
        $entity->setElementPedagogique($element);
        $form->bind($entity);

        if (! $id) $form->initFromContext();
        return compact('form');
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
        $this->initFilters();

        $id = (int)$this->params()->fromRoute('id');
        $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        $service = $this->getServiceService();
        //$role    = $this->getContextProvider()->getSelectedIdentityRole();
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
            $form->saveToContext();
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
        return compact('form','errors','title');
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
     * @return \Application\Form\Service\Recherche
     */
    protected function getFormServiceRecherche()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('ServiceRecherche');
    }

    /**
     * @return \Application\Service\Service
     */
    protected function getServiceService()
    {
        return $this->getServiceLocator()->get('ApplicationService');
    }

    /**
     * @return \Application\Service\ElementPedagogique
     */
    protected function getServiceElementPedagogique()
    {
        return $this->getServiceLocator()->get('ApplicationElementPedagogique');
    }

    /**
     * @return \Application\Service\TypeVolumeHoraire
     */
    protected function getServiceTypeVolumehoraire()
    {
        return $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire');
    }

    /**
     * @return \Application\Service\TypeIntervention
     */
    protected function getServiceTypeIntervention()
    {
        return $this->getServiceLocator()->get('ApplicationTypeIntervention');
    }

    /**
     * @return \Application\Service\Intervenant
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('applicationIntervenant');
    }

    /**
     * @return \Application\Service\ServiceReferentiel
     */
    protected function getServiceServiceReferentiel()
    {
        return $this->getServiceLocator()->get('applicationServiceReferentiel');
    }

    /**
     * @return \Application\Service\ContextProvider
     */
    public function getContextProvider()
    {
        return $this->getServiceLocator()->get('ApplicationContextProvider');
    }

    /**
     *
     * @return \Application\Assertion\ServiceAssertion
     */
    public function getAssertionService()
    {
        return $this->getServiceLocator()->get('ServiceAssertion');
    }
}
