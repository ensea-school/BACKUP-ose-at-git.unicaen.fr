<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Common\Exception\MessageException;
use Application\Exception\DbException;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Service\Recherche;

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
     * @param Recherche $recherche
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getFilteredServices($intervenant, $recherche)
    {
                //\Test\Util::sqlLog($this->getServiceService()->getEntityManager());
        $role                      = $this->getContextProvider()->getSelectedIdentityRole();

        $service                   = $this->getServiceService();
        $volumeHoraireService      = $this->getServiceLocator()->get('applicationVolumehoraire');       /* @var $volumeHoraireService \Application\Service\VolumeHoraire */
        $elementPedagogiqueService = $this->getServiceLocator()->get('applicationElementPedagogique');  /* @var $elementPedagogiqueService \Application\Service\ElementPedagogique */
        $intervenantService        = $this->getServiceLocator()->get('applicationIntervenant');         /* @var $intervenantService \Application\Service\Intervenant */

        $this->initFilters();
        $qb = $service->initQuery()[0];

        $service
            ->join(     'applicationIntervenant',       $qb, 'intervenant',         ['id', 'nomUsuel', 'prenom','sourceCode'] )
            ->leftJoin( $elementPedagogiqueService,     $qb, 'elementPedagogique',  ['id', 'sourceCode', 'libelle', 'histoDestruction', 'fi', 'fc', 'fa', 'tauxFi', 'tauxFc', 'tauxFa', 'tauxFoad'] )
            ->leftjoin( $volumeHoraireService,          $qb, 'volumeHoraire',       ['id', 'heures'] );

//        $intervenantService
//            ->leftJoin( 'applicationUtilisateur',       $qb, 'utilisateur',         true );

        $elementPedagogiqueService
            ->leftJoin( 'applicationEtape',             $qb, 'etape',               ['id', 'libelle', 'niveau', 'histoDestruction', 'sourceCode'] )
            ->leftJoin( 'applicationPeriode',           $qb, 'periode',             ['id', 'code', 'libelleLong', 'libelleCourt', 'ordre'] )
            ->leftJoin( 'applicationTypeIntervention',  $qb, 'typeIntervention',    ['id', 'code', 'libelle', 'ordre'] );

        $volumeHoraireService
            ->leftJoin( 'applicationMotifNonPaiement',  $qb, 'motifNonPaiement',    ['id', 'libelleCourt', 'libelleLong'] );

        $service->finderByContext($qb);
        $service->finderByFilterObject($recherche, new \Zend\Stdlib\Hydrator\ClassMethods(false), $qb);

        if ($intervenant){
            $service->finderByIntervenant($intervenant, $qb);
        }
        if (! $intervenant && $role instanceof \Application\Acl\ComposanteRole){
            $service->finderByComposante($role->getStructure(), $qb);
        }
        return $qb;
    }

    public function indexAction()
    {
        $typeVolumeHoraireCode    = $this->params()->fromRoute('type-volume-horaire-code', 'PREVU' );
        $totaux                   = $this->params()->fromQuery('totaux', 0) == '1';
        $role                     = $this->getContextProvider()->getSelectedIdentityRole();
        $intervenant              = $this->context()->intervenantFromRoute();
        $annee                    = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $viewModel                = new \Zend\View\Model\ViewModel();
        $canAddService            = $this->isAllowed($this->getServiceService()->newEntity()->setIntervenant($intervenant), 'create');
        $canAddServiceReferentiel = $intervenant instanceof IntervenantPermanent &&
                $this->isAllowed($this->getServiceServiceReferentiel()->newEntity()->setIntervenant($intervenant), 'create');

        if (! $this->isAllowed($this->getServiceService()->newEntity()->setIntervenant($intervenant), 'read')){
            throw new \BjyAuthorize\Exception\UnAuthorizedException();
        }

        if (! $intervenant){
            $action = $this->getRequest()->getQuery('action', null); // ne pas afficher par défaut, sauf si demandé explicitement
            $params = $this->getEvent()->getRouteMatch()->getParams();
            $params['action'] = 'recherche';
            $rechercheViewModel   = $this->forward()->dispatch('Application\Controller\Service', $params);
            $viewModel->addChild($rechercheViewModel, 'recherche');
            
            $recherche = $this->getServiceService()->loadRecherche();
        }else{
            $this->getContextProvider()->getLocalContext()->setIntervenant($intervenant); // passage au contexte pour le présaisir dans le formulaire de saisie
            $action = 'afficher'; // Affichage par défaut
            $recherche = new Recherche;
            $recherche->setTypeVolumeHoraire( $this->getServiceTypeVolumehoraire()->getByCode($typeVolumeHoraireCode) );
            $recherche->setEtatVolumeHoraire( $this->getServiceEtatVolumeHoraire()->getSaisi() );

            $params = [
                'intervenant'   => $intervenant->getSourceCode(),
                'action'        => 'formule-totaux-hetd',
            ];
            $this->getEvent()->setParam('typeVolumeHoraire', $recherche->getTypeVolumeHoraire() );
            $this->getEvent()->setParam('etatVolumeHoraire', $recherche->getEtatVolumeHoraire() );
            $totalViewModel   = $this->forward()->dispatch('Application\Controller\Intervenant', $params);
            $viewModel->addChild($totalViewModel, 'formuleTotauxHetd');
        }

        /* Préparation et affichage */
        if ('afficher' === $action || $totaux){
            $qb = $this->getFilteredServices($intervenant, $recherche);
            $services = $this->getServiceService()->getList($qb);
            $this->getServiceService()->setTypeVolumehoraire($services, $recherche->getTypeVolumeHoraire());

            // services référentiels : délégation au contrôleur
            if (! $totaux){
                $controller       = 'Application\Controller\ServiceReferentiel';
                $params           = $this->getEvent()->getRouteMatch()->getParams();
                $params['action'] = 'voirListe';
                $params['recherche'] = $recherche; /** @todo recherche également au niveau du service référentiel */
                $params['query']  = $this->params()->fromQuery();
                $params['renderIntervenants'] = ! $intervenant;
                $listeViewModel   = $this->forward()->dispatch($controller, $params);
                $viewModel->addChild($listeViewModel, 'servicesRefListe');
            }
        }else{
            $services = [];
        }
        $renderReferentiel  = !$intervenant instanceof IntervenantExterieur;
        $typeVolumeHoraire = $recherche->getTypeVolumeHoraire();
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
            $this->rechercheAction();
            $recherche = $this->getServiceService()->loadRecherche();
        }else{
            $recherche = new Recherche;
            $recherche->setTypeVolumeHoraire( $this->getServiceTypeVolumehoraire()->getPrevu() );
            $recherche->setEtatVolumeHoraire( $this->getServiceEtatVolumeHoraire()->getSaisi() );
        }

        /* Préparation et affichage */
        $data = $this->getServiceService()->getTableauBordExport($recherche);
        //var_dump($data);

        $csvModel = new \UnicaenApp\View\Model\CsvModel();
        $head = [
            'Code intervenant',
            'Intervenant',
            'Statut intervenant',
            'Type d\'intervenant',
            'Structure d\'affectation',

            'Structure d\'enseignement',
            'Code formation',
            'Formation ou établissement',
            'Code enseignement',
            'Enseignement',
            'Commentaires',
            'Période',
            'Majoration',
            'Source enseignement',

            'Service statutaire',
            'Modification de service du',
            'Total Heures effectives',
            'Total HETD',
            'Solde (HETD)',
            'Heures non payées',
            'Référentiel',
        ];
        foreach( $data['types-intervention'] as $typeIntervention ){
            /* @var $typeIntervention \Application\Entity\Db\TypeIntervention */
            $head[] = $typeIntervention->getCode();
        }

        $csvModel->setHeader( $head );
        foreach( $data['data'] as $d ){
            if ($d['heures-reelles'] + $d['heures-non-payees'] > 0){
                $line = [
                    $d['intervenant-code'],
                    $d['intervenant-nom'],
                    $d['intervenant-statut-libelle'],
                    $d['intervenant-type-libelle'],
                    $d['service-structure-aff-libelle'],

                    $d['service-structure-ens-libelle'],
                    $d['etape-code'],
                    $d['etape-libelle'] ? $d['etape-libelle'] : $d['etablissement-libelle'],
                    $d['element-code'],
                    $d['element-libelle'],
                    $d['commentaires'],
                    $d['element-periode-libelle'],
                    $d['element-ponderation-compl'],
                    $d['element-source-libelle'],

                    $d['heures-service-statutaire'],
                    $d['heures-service-du-modifie'],
                    $d['heures-reelles'],
                    $d['heures-assurees'],
                    $d['heures-solde'],
                    $d['heures-non-payees'],
                    $d['heures-referentiel'],
                ];
                foreach( $data['types-intervention'] as $typeIntervention ){
                    /* @var $typeIntervention \Application\Entity\Db\TypeIntervention */
                    if (isset($d['types-intervention'][$typeIntervention->getId()])){
                        $line[] = $d['types-intervention'][$typeIntervention->getId()];
                    }else{
                        $line[] = 0;
                    }
                }
                $csvModel->addLine($line);
            }
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
        $annee   = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $action = $this->getRequest()->getQuery('action', null);
        $tri = null;
        if ('trier' == $action) $tri = $this->getRequest()->getQuery('tri', null);

        if (! $this->isAllowed($this->getServiceService()->newEntity()->setIntervenant($intervenant), 'read')){
            throw new \BjyAuthorize\Exception\UnAuthorizedException();
        }

        $this->initFilters();
        if ($intervenant){
            $this->getContextProvider()->getLocalContext()->setIntervenant($intervenant);
        }

        if (! $intervenant){
            $this->rechercheAction();
            $recherche = $this->getServiceService()->loadRecherche();
        }else{
            $recherche = new Recherche;
            $recherche->setTypeVolumeHoraire( $this->getServiceTypeVolumehoraire()->getPrevu() );
            $recherche->setEtatVolumeHoraire( $this->getServiceEtatVolumeHoraire()->getSaisi() );
        }

        $viewModel = new \Zend\View\Model\ViewModel();

        $params = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'recherche';
        $listeViewModel   = $this->forward()->dispatch('Application\Controller\Service', $params);
        $viewModel->addChild($listeViewModel, 'recherche');

        $recherche = $this->getServiceService()->loadRecherche();
        if ('afficher' == $action || 'trier' == $action ){
            $resumeServices = $this->getServiceService()->getTableauBordResume($recherche, $tri);
        }else{
            $resumeServices = null;
        }

        $viewModel->setVariables( compact('annee','action','resumeServices','canAddService') );
        return $viewModel;
    }

    public function resumeRefreshAction()
    {
        $this->initFilters();

        $filter = $this->getFormRecherche()->hydrateFromSession();

        return compact('filter');
    }

    public function rechercheAction()
    {
        $errors = [];
        $service = $this->getServiceService();
        $rechercheForm = $this->getFormRecherche();
        $entity = $service->loadRecherche();
        $rechercheForm->bind($entity);

        $request = $this->getRequest();
        /* @var $request Http\Request */
        if ('afficher' === $request->getQuery('action', null)){
            $rechercheForm->setData($request->getQuery());
            if ($rechercheForm->isValid()) {
                try {
                    $service->saveRecherche($entity);
                }catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }
            }else{
                $errors[] = 'Les données de recherche saisies sont invalides.';
            }
        }
        return compact('rechercheForm', $errors);
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

        $intervenant = $this->getContextProvider()->getLocalContext()->getIntervenant();
        $assertionEntity = $this->getServiceService()->newEntity()->setIntervenant($intervenant);
        if (! $this->isAllowed($assertionEntity, 'delete')) {
            throw new MessageException("Cette opération n'est pas autorisée.");
        }

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
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire');
        if (empty($typeVolumeHoraire)){
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        }else{
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get( $typeVolumeHoraire );
        }
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
        $form->setAttribute('action', $this->url()->fromRoute('service/saisie', ['id' => $entity->getId()], ['query' => ['type-volume-horaire' => $typeVolumeHoraire->getId()]], true));

        $intervenant = $this->getContextProvider()->getLocalContext()->getIntervenant();
        $assertionEntity = $this->getServiceService()->newEntity()->setIntervenant($intervenant);
        if (! $this->isAllowed($assertionEntity, 'create') || ! $this->isAllowed($assertionEntity, 'update')) {
            throw new MessageException("Cette opération n'est pas autorisée.");
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->saveToContext();
            if ($form->isValid()) {
                try {
                    $entity = $service->save($entity);
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
    protected function getFormRecherche()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('ServiceRechercheForm');
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
     * @return \Application\Service\EtatVolumeHoraire
     */
    protected function getServiceEtatVolumeHoraire()
    {
        return $this->getServiceLocator()->get('applicationEtatVolumeHoraire');
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
