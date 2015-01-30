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

        $volumeHoraireService->leftJoin( 'applicationEtatVolumeHoraire', $qb, 'etatVolumeHoraire', ['id','code','libelle','ordre'] );
        $volumeHoraireService->leftJoin( 'ApplicationFormuleVolumeHoraire', $qb, 'formuleVolumeHoraire', ['id'] );

        $service->finderByContext($qb);
        $service->finderByFilterObject($recherche, new \Zend\Stdlib\Hydrator\ClassMethods(false), $qb, null, ['typeVolumeHoraire','etatVolumeHoraire']);

        if ($intervenant){
            $service->finderByIntervenant($intervenant, $qb);
        }

        $qb->addOrderBy( $elementPedagogiqueService->getAlias().'.libelle' );

        if (! $intervenant && $role instanceof \Application\Acl\ComposanteRole){
            $service->finderByComposante($role->getStructure(), $qb);
        }
        return $qb;
    }

    public function indexAction()
    {
        $typeVolumeHoraireCode    = $this->params()->fromRoute('type-volume-horaire-code', 'PREVU' );
        $totaux                   = $this->params()->fromQuery('totaux', 0) == '1';
        $viewHelperParams         = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
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

            // services référentiels : délégation au contrôleur
//            if (! $totaux){
//                $rech             = clone $recherche;
//                $controller       = 'Application\Controller\ServiceReferentiel';
//                $params           = $this->getEvent()->getRouteMatch()->getParams();
//                $params['action'] = 'voirListe';
//                $params['recherche'] = $rech->setTypeVolumeHoraire(null); /** @todo recherche également au niveau du service référentiel */
//                $params['query']  = $this->params()->fromQuery();
//                $params['renderIntervenants'] = ! $intervenant;
//                $listeViewModel   = $this->forward()->dispatch($controller, $params);
//                $viewModel->addChild($listeViewModel, 'servicesRefListe');
//            }
        }else{
            $services = [];
        }
        $typeVolumeHoraire = $recherche->getTypeVolumeHoraire();
        $params = $viewHelperParams;
        $viewModel->setVariables(compact('annee', 'services', 'typeVolumeHoraire','action', 'role', 'intervenant', 'canAddService', 'canAddServiceReferentiel', 'params'));
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
        $role               = $this->getContextProvider()->getSelectedIdentityRole();

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
        $params = [
            'ignored-columns' => ['intervenant-type-code'],
        ];
        if ($role instanceof \Application\Acl\ComposanteRole){
            $params['composante'] = $role->getStructure();
        }
        $data = $this->getServiceService()->getTableauBord($recherche, $params);

        $csvModel = new \UnicaenApp\View\Model\CsvModel();
        $csvModel->setHeader($data['head']);
        $csvModel->addLines($data['data']);
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
        $role                     = $this->getContextProvider()->getSelectedIdentityRole();
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
            $params = [
                'tri' => $tri,
                'isoler-non-payes' => false,
                'regroupement' => 'intervenant'
            ];
            if ($role instanceof \Application\Acl\ComposanteRole){
                $params['composante'] = $role->getStructure();
            }
            $resumeServices = $this->getServiceService()->getTableauBord($recherche, $params);
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

        $params             = $this->params()->fromPost('params', $this->params()->fromQuery('params') );
        $details            = 1 == (int)$this->params()->fromQuery('details',               (int)$this->params()->fromPost('details',0));
        $onlyContent        = 1 == (int)$this->params()->fromQuery('only-content',          0);
        $service = $this->context()->serviceFromRoute('id'); // remplacer id par service au besoin, à cause des routes définies en config.

        return compact('service', 'params', 'details', 'onlyContent');
    }

    public function volumesHorairesRefreshAction()
    {
        $this->initFilters();

        $id = (int)$this->params()->fromRoute('id');
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire') );
        if (empty($typeVolumeHoraire)){
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        }else{
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get( $typeVolumeHoraire );
        }
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
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire') );
        if (empty($typeVolumeHoraire)){
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        }else{
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get( $typeVolumeHoraire );
        }
        $id        = (int) $this->params()->fromRoute('id', 0);
        $service   = $this->getServiceService()->get($id);
        $title     = "Suppression de service";
        $form      = new \Application\Form\Supprimer('suppr');
        $form->add(new \Zend\Form\Element\Hidden('type-volume-horaire'));
        $viewModel = new \Zend\View\Model\ViewModel();

        $intervenant = $this->getContextProvider()->getLocalContext()->getIntervenant();
        $assertionEntity = $this->getServiceService()->newEntity()->setIntervenant($intervenant);
        if (! $this->isAllowed($assertionEntity, 'delete')) {
            throw new MessageException("Cette opération n'est pas autorisée.");
        }

        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));
        $form->get('type-volume-horaire')->setValue( $typeVolumeHoraire->getId() );

        if ($this->getRequest()->isPost()) {
            $errors = array();
            try {
                if ($typeVolumeHoraire->getCode() === \Application\Entity\Db\TypeVolumeHoraire::CODE_REALISE){
                    // destruction des volumes horaires associés
                    foreach( $service->getVolumeHoraire() as $vh ){
                        if ($vh->getTypeVolumeHoraire() === $typeVolumeHoraire){
                            $this->getServiceVolumeHoraire()->delete($vh);
                        }
                    }
                }else{
                     // destruction du service même
                    $this->getServiceService()->delete($service);
                }
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
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire') );
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
     * @return \Application\Service\VolumeHoraire
     */
    protected function getServiceVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationVolumeHoraire');
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
