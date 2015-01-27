<?php

namespace Application\Controller;

use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;

/**
 * Description of IntervenantController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Intervenant intervenant()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantController extends AbstractActionController implements ContextProviderAwareInterface, WorkflowIntervenantAwareInterface
{
    use ContextProviderAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
    /**
     * @var Intervenant
     */
    private $intervenant;

    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            // redirection selon le workflow
            $intervenant = $role->getIntervenant();
            $wf  = $this->getWorkflowIntervenant()->setIntervenant($intervenant);
            $url = $wf->getCurrentStepUrl();
            if (!$url) {
                $url = $wf->getStepUrl($wf->getLastStep());
            }
            return $this->redirect()->toUrl($url);
        }
        
        return $this->redirect()->toRoute('intervenant/rechercher');
    }
    
    public function rechercherAction()
    {
        $view = $this->choisirAction();
        
        if ($this->intervenant) {
            return $this->redirect()->toRoute('intervenant/fiche', array('intervenant' => $this->intervenant->getSourceCode()));
        }
        
        $view->setTemplate('application/intervenant/choisir');

        return $view;
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function choisirAction()
    {
        $intervenant = $this->context()->intervenantFromQuery();
        
        $url    = $this->url()->fromRoute('recherche', array('action' => 'intervenantFind'));
        $interv = new \UnicaenApp\Form\Element\SearchAndSelect('interv');
        $interv->setAutocompleteSource($url)
                ->setRequired(true)
                ->setSelectionRequired(true)
                ->setLabel("Recherchez l'intervenant concerné :")
                ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
        if ($intervenant) {
            $f = new \Common\Filter\IntervenantTrouveFormatter();
            $interv->setValue($f->filter($intervenant));
        }
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(array(
            'action' => $this->getRequest()->getRequestUri(),
            'class'  => 'intervenant-rech'));
        $form->add($interv);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $sourceCode = $form->get('interv')->getValueId();
                $this->getRequest()->getQuery()->set('sourceCode', $sourceCode);
                $this->intervenant = $this->importerAction()->getVariable('intervenant');
                $this->addIntervenantChoisiRecent($this->intervenant);
                if (($redirect = $this->params()->fromQuery('redirect'))) {
                    $redirect = str_replace('__sourceCode__', $sourceCode, $redirect);
                    return $this->redirect()->toUrl($redirect);
                }
            }
        }
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/intervenant/choisir')
                ->setVariables(array(
                    'form'    => $form, 
                    'title'   => "Rechercher un intervenant",
                    'recents' => $this->getIntervenantsChoisisRecents()));
        
        return $viewModel;
    }
    
    public function importerAction()
    {
        if (!($sourceCode = $this->params()->fromQuery('sourceCode', $this->params()->fromPost('sourceCode')))) {
            throw new LogicException("Aucun code source d'intervenant spécifié.");
        }
        
        $intervenant = $this->getServiceLocator()->get('ApplicationIntervenant')->importer($sourceCode);
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('intervenant' => $intervenant));
        return $view;
    }

    public function voirAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        $this->em()->getFilters()->enable('historique');

        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        else {
            $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }
        
        // fetch avec jointures
        $entityClass = $intervenant instanceof IntervenantExterieur ? 
                'Application\Entity\Db\IntervenantExterieur' : 
                'Application\Entity\Db\IntervenantPermanent';
        $qb = $this->em()->getRepository($entityClass)->createQueryBuilder("i")
                ->addSelect("ti, si, c, src, a, aff, affr, d")
                ->join("i.type", "ti")
                ->join("i.statut", "si")
                ->join("i.civilite", "c")
                ->join("i.source", "src")
                ->leftJoin("i.utilisateur", "u")
                ->leftJoin("i.adresse", "a")
                ->leftJoin("i.structure", "aff")
                ->leftJoin("i.affectation", "affr")
                ->leftJoin("i.discipline", "d")
                ->where("i = :intervenant")->setParameter('intervenant', $intervenant);
        if ($intervenant instanceof IntervenantExterieur) {
            $qb
                    ->addSelect("sf, rs, tp")
                    ->leftJoin("i.situationFamiliale", "sf")
                    ->leftJoin("i.regimeSecu", "rs")
                    ->leftJoin("i.typePoste", "tp");
        }
        else {
            $qb
                    ->addSelect("co")
                    ->leftJoin("i.corps", "co");
        }
        $intervenant = $qb->getQuery()->getSingleResult();
        
        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->intervenantGetDifferentiel($intervenant);
        $short = $this->params()->fromQuery('short', false);

        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(compact('intervenant', 'changements', 'short', 'page', 'role'));
        
        return $view;
    }

    public function apercevoirAction()
    {
        $this->em()->getFilters()->enable('historique');

        $intervenant = $this->context()->mandatory()->intervenantFromRoute();

        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->intervenantGetDifferentiel($intervenant);
        $title = "Aperçu d'un intervenant";
        $short = $this->params()->fromQuery('short', false);

        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(compact('intervenant', 'changements', 'title', 'short'));
        return $view;
    }

    public function voirHeuresCompAction()
    {
        $this->em()->getFilters()->enable('historique')
                ->disableForEntity('Application\Entity\Db\ElementPedagogique')
                ->disableForEntity('Application\Entity\Db\Etablissement');

        $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        /* @var $intervenant \Application\Entity\Db\Intervenant */
        $form = $this->getFormHeuresComp();
        $annee = $this->context()->getGlobalContext()->getAnnee();

        $typeVolumeHoraire = $this->context()->typeVolumeHoraireFromQuery('type-volume-horaire', $form->get('type-volume-horaire')->getValue());
        /* @var $typeVolumeHoraire \Application\Entity\Db\TypeVolumeHoraire */
        if (! isset($typeVolumeHoraire)){
            throw new LogicException('Type de volume horaire erroné');
        }

        $etatVolumeHoraire = $this->context()->etatVolumeHoraireFromQuery('etat-volume-horaire', $form->get('etat-volume-horaire')->getValue());
        /* @var $etatVolumeHoraire \Application\Entity\Db\EtatVolumeHoraire */
        if (! isset($etatVolumeHoraire)){
            throw new LogicException('Etat de volume horaire erroné');
        }

        $form->setData([
            'type-volume-horaire' => $typeVolumeHoraire->getId(),
            'etat-volume-horaire' => $etatVolumeHoraire->getId(),
        ]);

        $data = [
            'structure-affectation'         => $intervenant->getStructure(),
            'heures-service-statutaire'     => $intervenant->getStatut()->getServiceStatutaire(),
            'heures-modification-service'   => $intervenant->getFormuleIntervenant()->getUniqueFormuleServiceModifie($annee)->getHeures(),
            'services'                      => [],
            'referentiel'                   => [],
            'types-intervention'            => [],
            'has-ponderation-service-compl' => false,
        ];

        $referentiels = $intervenant->getFormuleIntervenant()->getFormuleServiceReferentiel($annee);
        foreach( $referentiels as $referentiel ){
            /* @var $referentiel \Application\Entity\Db\FormuleServiceReferentiel */

            if (! isset($data['referentiel'][$referentiel->getStructure()->getId()])){
                $data['referentiel'][$referentiel->getStructure()->getId()] = [
                    'structure' => $referentiel->getStructure(),
                    'heures'    => 0,
                    'hetd'      => 0,
                    'hetd-compl'=> 0,
                ];
            }
            $data['referentiel'][$referentiel->getStructure()->getId()]['heures'] += $referentiel->getHeures( $typeVolumeHoraire, $etatVolumeHoraire );
            $frr = $referentiel->getServiceReferentiel()->getUniqueFormuleResultatServiceReferentiel($typeVolumeHoraire, $etatVolumeHoraire);
            $data['referentiel'][$referentiel->getStructure()->getId()]['hetd'] += $frr ?$frr->getHeuresService() : 0;
            $data['referentiel'][$referentiel->getStructure()->getId()]['hetd-compl'] += $frr ? $frr->getHeuresComplReferentiel() : 0;
        }

        $services = $intervenant->getFormuleIntervenant()->getFormuleService($annee);
        foreach( $services as $service ){
            /* @var $service \Application\Entity\Db\FormuleService */
            $typesIntervention = [];
            $totalHeures = 0;

            $fvhs = $service->getFormuleVolumeHoraire($typeVolumeHoraire, $etatVolumeHoraire);
            foreach( $fvhs as $fvh ){ /* @var $fvh \Application\Entity\Db\FormuleVolumeHoraire */
                $totalHeures += $fvh->getHeures();
                if (! isset($typesIntervention[$fvh->getTypeIntervention()->getId()])) $typesIntervention[$fvh->getTypeIntervention()->getId()] = [
                    'type-intervention' => $fvh->getTypeIntervention(),
                    'heures'            => 0,
                    'hetd'              => 0,
                ];
                $typesIntervention[$fvh->getTypeIntervention()->getId()]['heures'] += $fvh->getHeures();
                $hetd = $fvh->getVolumeHoraire()->getFormuleResultatVolumeHoraire()->first()->getServiceAssure();
                $typesIntervention[$fvh->getTypeIntervention()->getId()]['hetd'] += $hetd;
            }

            if ($totalHeures > 0){
                $frs = $service->getService()->getUniqueFormuleResultatService( $typeVolumeHoraire, $etatVolumeHoraire );
                if (1.0 !== $service->getPonderationServiceCompl()){
                    $data['has-ponderation-service-compl'] = true;
                }
                $data['services'][$service->getId()] = [
                    'service'                       => $service->getService(),
                    'taux-fi'                       => $service->getTauxFi(),
                    'taux-fa'                       => $service->getTauxFa(),
                    'taux-fc'                       => $service->getTauxFc(),
                    'ponderation-service-compl'     => $service->getPonderationServiceCompl(),
                    'heures'                        => [],
                    'hetd'                          => [],
                    'total-hetd'                    => $frs->getHeuresService(),
                    'total-hetd-compl-fi'           => $frs->getHeuresComplFi(),
                    'total-hetd-compl-fa'           => $frs->getHeuresComplFa(),
                    'total-hetd-compl-fc'           => $frs->getHeuresComplFc(),
                    'total-hetd-compl-fc-majorees'  => $frs->getHeuresComplFcMajorees(),
                ];
                foreach( $typesIntervention as $ti ){
                    if ( $ti['heures'] > 0 ){
                        $data['types-intervention'][$ti['type-intervention']->getId()] = $ti['type-intervention'];
                        $data['services'][$service->getId()]['heures'][$ti['type-intervention']->getId()] = $ti['heures'];
                        $data['services'][$service->getId()]['hetd'][$ti['type-intervention']->getId()] = $ti['hetd'];
                    }
                }
            }
        }

        usort($data['types-intervention'], function($ti1,$ti2){ return $ti1->getOrdre() > $ti2->getOrdre(); });

        return compact('annee', 'form','intervenant','typeVolumeHoraire','etatVolumeHoraire', 'data');
    }

    public function formuleTotauxHetdAction()
    {
        $intervenant = $this->context()->mandatory()->intervenantFromRoute(); /* @var $intervenant Intervenant */
        $annee = $this->context()->getGlobalContext()->getAnnee();
        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        $etatVolumeHoraire = $this->getEvent()->getParam('etatVolumeHoraire');
        $formuleResultat = $intervenant->getUniqueFormuleResultat($annee, $typeVolumeHoraire, $etatVolumeHoraire);
        return compact('formuleResultat');
    }

    public function feuilleDeRouteAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();

        $this->em()->getFilters()->enable('historique');

        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        else {
            $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }

        if ($intervenant instanceof \Application\Entity\Db\IntervenantPermanent) {
            throw new \Common\Exception\MessageException("Pas encore implémenté pour IntervenantPermanent");
        }
        
        $title = sprintf("Feuille de route <small>%s</small>", $intervenant);
        
        $wf = $this->getWorkflowIntervenant()->setIntervenant($intervenant); /* @var $wf \Application\Service\Workflow\WorkflowIntervenant */
        $wf->init();
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(compact('intervenant', 'title', 'wf', 'role'));
        
        if ($wf->getCurrentStep()) {
//            var_dump($wf->getStepUrl($wf->getCurrentStep()));
        }
        
        return $view;
    }
    
    public function modifierAction()
    {
        if (!($id = $this->params()->fromRoute('id'))) {
            throw new LogicException("Aucun identifiant d'intervenant spécifié.");
        }
        if (!($intervenant = $this->intervenant()->getRepo()->find($id))) {
            throw new RuntimeException("Intervenant '$id' spécifié introuvable.");
        }

        $form = $this->getFormModifier();
        $form->bind($intervenant);

        if (($data = $this->params()->fromPost())) {
            $form->setData($data);
            if ($form->isValid()) {
                $em = $this->intervenant()->getEntityManager();
                $em->flush($form->getObject());
            }
        }
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('form' => $form, 'intervenant' => $intervenant));
        return $view;
    }
    
    protected function getFormModifier()
    {
        $builder = new AnnotationBuilder();
        $form    = $builder->createForm('Application\Entity\Db\Intervenant');
        $form->getHydrator()->setUnderscoreSeparatedKeys(false);
        
        return $form;
    }
    
    /**
     * @return \Application\Service\Intervenant
     */
    protected function getIntervenantService()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }
    
    private $intervenantsChoisisRecentsSessionContainer;
    
    /**
     * @return \Zend\Session\Container
     */
    protected function getIntervenantsChoisisRecentsSessionContainer()
    {
        if (null === $this->intervenantsChoisisRecentsSessionContainer) {
            $container = new \Zend\Session\Container(get_class() . '_IntervenantsChoisisRecents');
            $container->setExpirationSeconds(2*60*60); // 1 heure
            $this->intervenantsChoisisRecentsSessionContainer = $container;
        }
        return $this->intervenantsChoisisRecentsSessionContainer;
    }
    
    /**
     * 
     * @param bool $clear
     * @return array
     */
    protected function getIntervenantsChoisisRecents($clear = false)
    {
        $container = $this->getIntervenantsChoisisRecentsSessionContainer();
        if ($clear) {
            unset($container->intervenants);
        }
        if (!isset($container->intervenants)) {
            $container->intervenants = array();
        }
        return $container->intervenants;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return \Application\Controller\IntervenantController
     */
    protected function addIntervenantChoisiRecent(Intervenant $intervenant)
    {
        $container    = $this->getIntervenantsChoisisRecentsSessionContainer();
        $intervenants = (array) $container->intervenants;
        
        if (!array_key_exists($intervenant->getId(), $intervenants)) {
            $intervenants["" . $intervenant] = array(
                'id'         => $intervenant->getId(),
                'sourceCode' => $intervenant->getSourceCode(),
                'nom'        => "" . $intervenant,
            );
            ksort($intervenants);
        }
        $container->intervenants = $intervenants;
        
        return $this;
    }

    /**
     *
     * @return \Application\Form\Intervenant\HeuresCompForm
     */
    protected function getFormHeuresComp()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('IntervenantHeuresCompForm');
    }

    /**
     * @return \Application\Service\Intervenant
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('applicationIntervenant');
    }
}
