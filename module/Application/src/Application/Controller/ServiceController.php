<?php

namespace Application\Controller;

use Debug\Util;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Common\Exception\MessageException;
use Application\Exception\DbException;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TypeVolumeHoraire;
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
    use \Application\Service\Traits\ContextAwareTrait,
        \Application\Service\Traits\ServiceAwareTrait,
        \Application\Service\Traits\VolumeHoraireAwareTrait,
        \Application\Service\Traits\ElementPedagogiqueAwareTrait,
        \Application\Service\Traits\TypeVolumeHoraireAwareTrait,
        \Application\Service\Traits\TypeInterventionAwareTrait,
        \Application\Service\Traits\IntervenantAwareTrait,
        \Application\Service\Traits\ServiceReferentielAwareTrait,
        \Application\Service\Traits\EtatVolumeHoraireAwareTrait,
        \Application\Service\Traits\ValidationAwareTrait,
        \Application\Service\Traits\StructureAwareTrait,
        \Application\Service\Traits\EtapeAwareTrait,
        \Application\Service\Traits\PeriodeAwareTrait
    ;
    
    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\Service',
                'Application\Entity\Db\VolumeHoraire'
            ],
            $this->getServiceContext()->getDateObservation()
        );
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

        $service                   = $this->getServiceService();
        $volumeHoraireService      = $this->getServiceVolumeHoraire();
        $elementPedagogiqueService = $this->getServiceElementPedagogique();
        $structureService          = $this->getServiceStructure();
        $etapeService              = $this->getServiceEtape();
        $periodeService            = $this->getServicePeriode();

        $this->initFilters();
        $qb = $service->initQuery()[0]; /* @var $qb \Doctrine\ORM\QueryBuilder */
        $service
            ->join(     'applicationIntervenant',       $qb, 'intervenant',         ['id', 'nomUsuel', 'prenom','sourceCode'] )
            ->leftJoin( $elementPedagogiqueService,     $qb, 'elementPedagogique',  ['id', 'sourceCode', 'libelle', 'histoDestruction', 'fi', 'fc', 'fa', 'tauxFi', 'tauxFc', 'tauxFa', 'tauxFoad'] )
            ->leftjoin( $volumeHoraireService,          $qb, 'volumeHoraire',       ['id', 'heures'] );

//        $intervenantService
//            ->leftJoin( 'applicationUtilisateur',       $qb, 'utilisateur',         true );

        $elementPedagogiqueService
            ->join(     $structureService,              $qb, 'structure',           ['id', 'libelleCourt'] )
            ->leftJoin( $etapeService,                  $qb, 'etape',               ['id', 'libelle', 'niveau', 'histoDestruction', 'sourceCode'] )
            ->leftJoin( $periodeService,                $qb, 'periode',             ['id', 'code', 'libelleLong', 'libelleCourt', 'ordre'] )
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

        $qb->addOrderBy( $structureService->getAlias().'.libelleCourt' )
           ->addOrderBy( $etapeService->getAlias().'.libelle')
           ->addOrderBy( $periodeService->getAlias().'.libelleCourt')
           ->addOrderBy( $elementPedagogiqueService->getAlias().'.sourceCode' );

        if (! $intervenant && $composante = $this->getServiceContext()->getSelectedIdentityRole()->getStructure()){
            $service->finderByComposante($composante, $qb);
        }
        return $qb;
    }

    public function indexAction()
    {
        $totaux           = $this->params()->fromQuery('totaux', 0) == '1';
        $viewHelperParams = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $role             = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant      = $this->context()->intervenantFromRoute();
        $viewModel        = new \Zend\View\Model\ViewModel();

        $serviceProto = $this->getServiceService()->newEntity()
                ->setIntervenant($intervenant)
                ->setTypeVolumeHoraire($this->getTypeVolumeHoraire());
        
        $canAddService = $this->isAllowed($serviceProto, 'create');

//        if (! $this->isAllowed($this->getServiceService()->newEntity()->setIntervenant($intervenant), 'read')){
//            throw new \BjyAuthorize\Exception\UnAuthorizedException();
//        }

        if (! $intervenant){
            $action = $this->getRequest()->getQuery('action', null); // ne pas afficher par défaut, sauf si demandé explicitement
            $params = $this->getEvent()->getRouteMatch()->getParams();
            $params['action'] = 'recherche';
            $rechercheViewModel   = $this->forward()->dispatch('Application\Controller\Service', $params);
            $viewModel->addChild($rechercheViewModel, 'recherche');

            $recherche = $this->getServiceService()->loadRecherche();
        }else{
            $localContext = $this->getServiceLocator()->get('applicationLocalContext');
            /* @var $localContext \Application\Service\LocalContext */

            $localContext->setIntervenant($intervenant); // passage au contexte pour le présaisir dans le formulaire de saisie
            $action = 'afficher'; // Affichage par défaut
            $recherche = new Recherche;
            $recherche->setTypeVolumeHoraire( $this->getTypeVolumeHoraire() );
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
            if (! $totaux){
                $controller       = 'Application\Controller\ServiceReferentiel';
                $params           = $this->getEvent()->getRouteMatch()->getParams();
                $params['action'] = 'index';
                $params['recherche'] = $recherche;
                $params['query']  = $this->params()->fromQuery();
                $params['renderIntervenants'] = ! $intervenant;
                $listeViewModel   = $this->forward()->dispatch($controller, $params);
                $viewModel->addChild($listeViewModel, 'servicesRefListe');
            }
        }else{
            $services = [];
        }
        $typeVolumeHoraire = $recherche->getTypeVolumeHoraire();
        $params = $viewHelperParams;
        $viewModel->setVariables(compact('services', 'typeVolumeHoraire','action', 'role', 'intervenant', 'canAddService', 'params'));
        if ($totaux){
            $viewModel->setTemplate('application/service/rafraichir-totaux');
        }else{
            $viewModel->setTemplate('application/service/index');

            // gestion du bouton permettant de clôturer la saisie du réalisé pour les permanents
            $this->injectClotureSaisie($viewModel);
        }
                
        return $viewModel;
    }
    
    private function injectClotureSaisie(\Zend\View\Model\ModelInterface $viewModel)
    {
        $params = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'cloturer-saisie';
        
        $widget = $this->forward()->dispatch('Application\Controller\Service', $params);
        
        if ($widget instanceof \Zend\View\Model\ModelInterface) {
            $viewModel->addChild($widget, 'clotureSaisie');
        }
    }
    
    /**
     * Clôture de la saisie du réalisé des permanents.
     * 
     * GET  : affichage du bouton permettant de clôturer la saisie.
     * POST : création d'une validation pour clôturer la saisie, ou suppression pour déclôturer.
     * 
     * @return ViewModel
     */
    public function cloturerSaisieAction()
    {
        $intervenant = $this->context()->intervenantFromRoute();
        if (! $intervenant) {
            return false; // désactive la vue
        }
        
        $structure  = $intervenant->getStructure();
        $tvh        = $this->getTypeVolumeHoraire();
        $validation = $this->getServiceValidation()->findValidationClotureServices($intervenant, $tvh); // clôture existante
        $viewModel  = new ViewModel();

        if (! $intervenant->getStatut()->estPermanent()) {
            return false; // désactive la vue
        }
        if (TypeVolumeHoraire::CODE_REALISE !== $tvh->getCode()) {
            return false; // désactive la vue
        }
        
        if (! $validation) {
            $validation = $this->getServiceValidation()->createValidationClotureServices($intervenant, $structure, $tvh);
        }
        
        if ($this->getRequest()->isPost()) {
            $cloturer = $this->params()->fromPost('cloturer');
            if (null === $cloturer || $validation->getId() && 1 === $cloturer || !$validation->getId() && 0 === $cloturer) {
                exit;
            }
            if ($cloturer) {
                $this->em()->persist($validation);
            }
            else {
                $this->em()->remove($validation);
            }
            $this->em()->flush();
        }
        
        if ($validation->getId()) {
            $dateCloture = $validation->getHistoModification()->format(\Common\Constants::DATETIME_FORMAT);
            $this->messenger()->addMessage("La saisie du service réalisé a été clôturée le $dateCloture.", 'success');
        }
        
        $avertissement = "<strong>Attention!</strong> <br />"
                . "Assurez-vous d'avoir saisi la totalité de vos services réalisés (enseignements et référentiel), "
                . "quelle que soit la composante d'intervention. <br />"
                . "Cliquer sur le bouton ci-dessous vous empêchera de revenir sur votre saisie.";
        $confirm = "Attention! "
                . "Confirmez-vous avoir saisi la totalité de vos services réalisés (enseignements et référentiel), "
                . "quelle que soit la composante d'intervention ? "
                . "Cliquer sur OK vous empêchera de revenir sur votre saisie.";
                
        $viewModel->setVariables([
            'typeVolumeHoraire' => $tvh,
            'validation'        => $validation,
            'avertissement'     => $avertissement,
            'confirm'           => $confirm,
        ]);
        
        return $viewModel;
    }

    public function exportAction()
    {
        $intervenant        = $this->context()->intervenantFromRoute();
        $role               = $this->getServiceContext()->getSelectedIdentityRole();

        if (! $this->isAllowed($this->getServiceService()->newEntity()->setIntervenant($intervenant), 'read')){
            throw new \BjyAuthorize\Exception\UnAuthorizedException();
        }

        $this->initFilters();
        if ($intervenant){
            $localContext = $this->getServiceLocator()->get('applicationLocalContext');
            /* @var $localContext \Application\Service\LocalContext */
            $localContext->setIntervenant($intervenant);
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
        $annee              = $this->getServiceContext()->getAnnee();
        $role               = $this->getServiceContext()->getSelectedIdentityRole();
        $action             = $this->getRequest()->getQuery('action', null);
        $tri                = null;
        if ('trier' == $action) $tri = $this->getRequest()->getQuery('tri', null);

        if ($intervenant){
            $localContext = $this->getServiceLocator()->get('applicationLocalContext');
            /* @var $localContext \Application\Service\LocalContext */
            $localContext->setIntervenant($intervenant);
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

    public function constatationAction()
    {
        $this->initFilters();
        $errors = [];
        $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getRealise();
        $services = $this->params()->fromQuery('services');
        if ($services){
            $services = explode( ',', $services );
            foreach( $services as $sid ){
                $service = $this->getServiceService()->get( $sid );
                if ($this->isAllowed($service, 'update')){
                    try{
                        $this->getServiceService()->setRealisesFromPrevus( $service );
                    }catch(\Exception $e){
                        $errors[] = $e->getMessage();
                    }
                }
            }
        }
        return compact('errors');
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

        $service->setTypeVolumeHoraire($typeVolumeHoraire);
        if (! $this->isAllowed($service, 'delete')) {
            throw new MessageException("Cette opération n'est pas autorisée.");
        }

        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->get('type-volume-horaire')->setValue( $typeVolumeHoraire->getId() );

        if ($this->getRequest()->isPost()) {
            $errors = [];
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
        //$role    = $this->getServiceContext()->getSelectedIdentityRole();
        $form    = $this->getFormSaisie();
        $errors  = [];

        $localContext = $this->getServiceLocator()->get('applicationLocalContext');
        /* @var $localContext \Application\Service\LocalContext */
        $intervenant = $localContext->getIntervenant();
        
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

        $assertionEntity = $this->getServiceService()->newEntity()->setIntervenant($intervenant);
        $assertionEntity->setTypeVolumeHoraire($typeVolumeHoraire);
        if (! $this->isAllowed($assertionEntity, 'create') && ! $this->isAllowed($assertionEntity, 'update')) {
            throw new MessageException("Cette opération n'est pas autorisée.");
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->saveToContext();
            if ($form->isValid()) {
                try {
                    $entity = $service->save($entity->setIntervenant($intervenant));
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
     * @var TypeVolumeHoraire
     */
    private $typeVolumeHoraire;
    
    /**
     * @return TypeVolumeHoraire
     */
    private function getTypeVolumeHoraire()
    {
        if (null === $this->typeVolumeHoraire) {
            $typeVolumeHoraireCode   = $this->params()->fromRoute('type-volume-horaire-code', 'PREVU' );
            $this->typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getByCode($typeVolumeHoraireCode);
        }
        
        return $this->typeVolumeHoraire;
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
     *
     * @return \Application\Assertion\ServiceAssertion
     */
    public function getAssertionService()
    {
        return $this->getServiceLocator()->get('ServiceAssertion');
    }
}
