<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\LogicException;
use Application\Entity\Db\Intervenant;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;

/**
 * Description of IntervenantController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantController extends AbstractActionController implements WorkflowIntervenantAwareInterface
{
    use WorkflowIntervenantAwareTrait,
        \Application\Service\Traits\ContextAwareTrait,
        \Application\Service\Traits\IntervenantAwareTrait,
        \Application\Service\Traits\TypeHeuresAwareTrait
    ;

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
        $role = $this->getServiceContext()->getSelectedIdentityRole();

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
            $this->addIntervenantChoisiRecent($this->intervenant);
            return $this->redirect()->toRoute('intervenant/fiche', ['intervenant' => $this->intervenant->getSourceCode()]);
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

        $url    = $this->url()->fromRoute('recherche', ['action' => 'intervenantFind']);
        $interv = new \UnicaenApp\Form\Element\SearchAndSelect('interv');
        $interv->setAutocompleteSource($url)
                ->setRequired(true)
                ->setSelectionRequired(true)
                ->setLabel("Recherchez l'intervenant concerné :")
                ->setAttributes(['title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"]);
        if ($intervenant) {
            $f = new \Common\Filter\IntervenantTrouveFormatter();
            $interv->setValue($f->filter($intervenant));
        }
        $form = new \Zend\Form\Form('search');
        $form->setAttributes([
            'action' => $this->getRequest()->getRequestUri(),
            'class'  => 'intervenant-rech']);
        $form->add($interv);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $sourceCode = $form->get('interv')->getValueId();
                $this->getRequest()->getQuery()->set('sourceCode', $sourceCode);
                $this->intervenant = $this->importerAction()->getVariable('intervenant');
                if (($redirect = $this->params()->fromQuery('redirect'))) {
                    $redirect = str_replace('__sourceCode__', $sourceCode, $redirect);
                    return $this->redirect()->toUrl($redirect);
                }
            }
        }

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/intervenant/choisir')
                ->setVariables([
                    'form'    => $form,
                    'title'   => "Rechercher un intervenant",
                    'recents' => $this->getIntervenantsChoisisRecents()]);

        return $viewModel;
    }

    public function importerAction()
    {
        if (!($sourceCode = $this->params()->fromQuery('sourceCode', $this->params()->fromPost('sourceCode')))) {
            throw new LogicException("Aucun code source d'intervenant spécifié.");
        }

        $intervenant = $this->getServiceIntervenant()->importer($sourceCode);

        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(['intervenant' => $intervenant]);
        return $view;
    }

    public function voirAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $this->em()->getFilters()->enable('historique');

        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        else {
            $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }

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
        $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        /* @var $intervenant \Application\Entity\Db\Intervenant */
        $form = $this->getFormHeuresComp();

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

        $typesHeures = $this->getServiceTypeHeures()->getList();

        $form->setData([
            'type-volume-horaire' => $typeVolumeHoraire->getId(),
            'etat-volume-horaire' => $etatVolumeHoraire->getId(),
        ]);

        $data = [
            'structure-affectation'         => $intervenant->getStructure(),
            'heures-service-statutaire'     => $intervenant->getStatut()->getServiceStatutaire(),
            'heures-modification-service'   => $intervenant->getFormuleIntervenant()->getUniqueFormuleServiceModifie()->getHeures(),
            'services'                      => [],
            'referentiel'                   => [],
            'types-intervention'            => [],
            'has-ponderation-service-compl' => false,
            'th-taux'                       => [],
            'th-service'                    => [],
            'th-compl'                      => [],
        ];

        $referentiels = $intervenant->getFormuleIntervenant()->getFormuleServiceReferentiel();
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
            $data['referentiel'][$referentiel->getStructure()->getId()]['hetd'] += $frr ?$frr->getHeuresServiceReferentiel() : 0;
            $data['referentiel'][$referentiel->getStructure()->getId()]['hetd-compl'] += $frr ? $frr->getHeuresComplReferentiel() : 0;
        }

        $services = $intervenant->getFormuleIntervenant()->getFormuleService();
        foreach( $services as $service ){
            $dsId = $service->getId();
            $ds = [];

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
                $hetd = $fvh->getVolumeHoraire()->getFormuleResultatVolumeHoraire()->first()->getTotal();
                $typesIntervention[$fvh->getTypeIntervention()->getId()]['hetd'] += $hetd;
            }

            if ($totalHeures > 0){
                $frs = $service->getService()->getUniqueFormuleResultatService( $typeVolumeHoraire, $etatVolumeHoraire );
                if (1.0 !== $service->getPonderationServiceCompl()){
                    $data['has-ponderation-service-compl'] = true;
                }
                $ds = [
                    'element-etablissement'         => $service->getService()->getElementPedagogique() ? $service->getService()->getElementPedagogique() : $service->getService()->getEtablissement(),
                    'taux'                          => [],
                    'structure'                     => $service->getService()->getElementPedagogique() ? $service->getService()->getElementPedagogique()->getStructure() : $service->getService()->getIntervenant()->getStructure(),
                    'ponderation-service-compl'     => $service->getPonderationServiceCompl(),
                    'heures'                        => [],
                    'hetd'                          => [
                        'total' => 0,
                    ]
                ];

                foreach( $typesHeures as $typeHeures ){
                    /* @var $typeHeures \Application\Entity\Db\TypeHeures */
                    // taux
                    try{
                        $h = $service->getTaux($typeHeures);
                    } catch (\Exception $ex) {
                        $h = 0.0;
                    }
                    if ($h > 0){
                        $ds['taux'][$typeHeures->getId()] = $h;
                        $data['th-taux'][$typeHeures->getId()] = $typeHeures;
                    }

                    // HETD service
                    try{
                        $h = $frs->getHeuresService($typeHeures);
                    } catch (\Exception $ex) {
                        $h = 0.0;
                    }
                    if ($h > 0){
                        $ds['hetd']['service'][$typeHeures->getId()] = $h;
                        $data['th-service'][$typeHeures->getId()] = $typeHeures;
                    }

                    // HETD compl
                    try{
                        $h = $frs->getHeuresCompl($typeHeures);
                    } catch (\Exception $ex) {
                        $h = 0.0;
                    }
                    if ($h > 0){
                        $ds['hetd']['compl'][$typeHeures->getId()] = $h;
                        $data['th-compl'][$typeHeures->getId()] = $typeHeures;
                    }
                }

                foreach( $typesIntervention as $ti ){
                    if ( $ti['heures'] > 0 ){
                        $data['types-intervention'][$ti['type-intervention']->getId()] = $ti['type-intervention'];
                        $ds['heures'][$ti['type-intervention']->getId()] = $ti['heures'];
                        $ds['hetd'][$ti['type-intervention']->getId()] = $ti['hetd'];
                    }
                }
                $data['services'][$dsId] = $ds;
            }
        }

        usort($data['types-intervention'], function($ti1,$ti2){ return $ti1->getOrdre() > $ti2->getOrdre(); });
        usort($data['th-taux'], function($ti1,$ti2){ return $ti1->getOrdre() > $ti2->getOrdre(); });
        usort($data['th-service'], function($ti1,$ti2){ return $ti1->getOrdre() > $ti2->getOrdre(); });
        usort($data['th-compl'], function($ti1,$ti2){ return $ti1->getOrdre() > $ti2->getOrdre(); });
        return compact('form', 'intervenant', 'typeVolumeHoraire', 'etatVolumeHoraire', 'data');
    }

    public function formuleTotauxHetdAction()
    {
        $intervenant = $this->context()->mandatory()->intervenantFromRoute(); /* @var $intervenant Intervenant */
        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        $etatVolumeHoraire = $this->getEvent()->getParam('etatVolumeHoraire');
        $formuleResultat = $intervenant->getUniqueFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire);
        return compact('formuleResultat');
    }

    public function feuilleDeRouteAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

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

    private $intervenantsChoisisRecentsSessionContainer;

    /**
     * @return \Zend\Session\Container
     */
    protected function getIntervenantsChoisisRecentsSessionContainer()
    {
        if (null === $this->intervenantsChoisisRecentsSessionContainer) {
            $container = new \Zend\Session\Container(get_class() . '_IntervenantsChoisisRecents');
            $container->setExpirationSeconds(3*60*60); // 3 heures
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
            $container->intervenants = [];
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
            $intervenants["" . $intervenant] = [
                'id'         => $intervenant->getId(),
                'sourceCode' => $intervenant->getSourceCode(),
                'nom'        => "" . $intervenant,
            ];
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
}
