<?php

namespace Application\Controller;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Service;
use Application\Entity\Db\Validation;
use Application\Form\Service\Traits\RechercheFormAwareTrait;
use Application\Form\Service\Traits\SaisieAwareTrait;
use Application\Processus\Traits\ServiceProcessusAwareTrait;
use Application\Processus\Traits\ValidationEnseignementProcessusAwareTrait;
use Application\Processus\Traits\ValidationProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\RegleStructureValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\Exporter\Pdf;
use UnicaenApp\View\Model\CsvModel;
use UnicaenApp\View\Model\MessengerViewModel;
use Zend\Http\Request;
use Application\Exception\DbException;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Service\Recherche;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\ServiceReferentielServiceAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;

/**
 * Description of ServiceController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceController extends AbstractController
{
    use ServiceProcessusAwareTrait;
    use ContextServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use VolumeHoraireServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ServiceReferentielServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use StructureServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use SaisieAwareTrait;
    use RechercheFormAwareTrait;
    use ValidationEnseignementProcessusAwareTrait;
    use RegleStructureValidationServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use WorkflowServiceAwareTrait;



    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs
     * éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\Service::class,
            \Application\Entity\Db\VolumeHoraire::class,
            \Application\Entity\Db\Validation::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            ElementPedagogique::class,
        ]);
    }



    public function indexAction()
    {
        $this->initFilters();

        $viewHelperParams = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $viewModel        = new \Zend\View\Model\ViewModel();

        $canAddService = Privileges::ENSEIGNEMENT_EDITION;

        $action             = $this->getRequest()->getQuery('action', null); // ne pas afficher par défaut, sauf si demandé explicitement
        $params             = $this->getEvent()->getRouteMatch()->getParams();
        $params['action']   = 'recherche';
        $rechercheViewModel = $this->forward()->dispatch('Application\Controller\Service', $params);
        $viewModel->addChild($rechercheViewModel, 'recherche');

        $recherche = $this->getServiceService()->loadRecherche();

        /* Préparation et affichage */
        if ('afficher' === $action) {
            $services = $this->getProcessusService()->getServices(null, $recherche);
            /* Services référentiels */
        } else {
            $services = [];
        }
        $typeVolumeHoraire = $recherche->getTypeVolumeHoraire();
        $params            = $viewHelperParams;
        $viewModel->setVariables(compact('services', 'typeVolumeHoraire', 'action', 'canAddService', 'params'));
        $viewModel->setTemplate('application/service/index');

        return $viewModel;
    }



    public function exportAction()
    {
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervernant');
        /* @var $intervenant Intervenant */


        $this->initFilters();
        if ($intervenant) {
            $this->getServiceLocalContext()->setIntervenant($intervenant);
        }

        if (!$intervenant) {
            $this->rechercheAction();
            $recherche = $this->getServiceService()->loadRecherche();
        } else {
            $recherche = new Recherche;
            $recherche->setTypeVolumeHoraire($this->getServiceTypeVolumehoraire()->getPrevu());
            $recherche->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
        }

        /* Préparation et affichage */
        $params = [
            'ignored-columns' => ['intervenant-type-code'],
        ];
        if ($structure = $this->getServiceContext()->getSelectedIdentityRole()->getStructure()) {
            $params['composante'] = $structure;
        }
        $data = $this->getServiceService()->getTableauBord($recherche, $params);

        $csvModel = new CsvModel();
        $csvModel->setHeader($data['head']);
        $csvModel->addLines($data['data']);
        $csvModel->setFilename('service.csv');

        return $csvModel;
    }



    public function exportPdfAction()
    {
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervernant');
        /* @var $intervenant Intervenant */


        $this->initFilters();
        if (!$intervenant) {
            $this->rechercheAction();
            $recherche = $this->getServiceService()->loadRecherche();
        } else {
            $recherche = new Recherche;
            $recherche->setTypeVolumeHoraire($this->getServiceTypeVolumehoraire()->getPrevu());
            $recherche->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
        }

        $variables = [
            'typeIntervenant' => $recherche->getTypeIntervenant(),
            'structure'       => $recherche->getStructureAff(),
            'signature1'      => $this->getServiceParametres()->get('export_pdf_services_signature_1'),
            'signature2'      => $this->getServiceParametres()->get('export_pdf_services_signature_2'),
            'signataire1'     => $this->getServiceParametres()->get('export_pdf_services_signataire_1'),
            'signataire2'     => $this->getServiceParametres()->get('export_pdf_services_signataire_2'),
            'data'            => $this->getServiceService()->getExportPdfData($recherche),
        ];

        $tprevrel = lcfirst((string)$recherche->getTypeVolumeHoraire()).'s';
        $tannee = $this->getServiceContext()->getAnnee();
        $title = "État des services $tprevrel au titre de l'année universitaire $tannee";

        $exp = $this->pdf()
            ->setOrientationPaysage(true)
            ->setFormat('A3')
            ->setHeaderTitle($title)
            ->setMarginBottom(25)
            ->setMarginTop(25);
        $exp->setFooterTitle($recherche->getTypeVolumeHoraire().' '.$recherche->getEtatVolumeHoraire());
        $exp->addBodyScript('application/service/export-pdf.phtml', false, $variables);
        $exp->getMpdf()->packTableData = true;
        //$exp->getMpdf()->simpleTables = true;

        $fileName = 'Listing des services - ' . date('dmY') . '.pdf';

        $exp->export($fileName, Pdf::DESTINATION_BROWSER_FORCE_DL);
    }



    /**
     * Totaux de services et de référentiel par intervenant.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function resumeAction()
    {
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervernant');
        /* @var $intervenant Intervenant */

        $canAddService = $this->isAllowed(Privileges::getResourceId(Privileges::ENSEIGNEMENT_EDITION));
        $annee         = $this->getServiceContext()->getAnnee();
        $action        = $this->getRequest()->getQuery('action', null);
        $tri           = null;
        if ('trier' == $action) $tri = $this->getRequest()->getQuery('tri', null);

        if (!$intervenant) {
            $this->rechercheAction();
            $recherche = $this->getServiceService()->loadRecherche();
        } else {
            $this->getServiceLocalContext()->setIntervenant($intervenant);

            $recherche = new Recherche;
            $recherche->setTypeVolumeHoraire($this->getServiceTypeVolumehoraire()->getPrevu());
            $recherche->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
            $recherche->setIntervenant($intervenant);
        }

        $viewModel = new \Zend\View\Model\ViewModel();

        $params           = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'recherche';
        $listeViewModel   = $this->forward()->dispatch('Application\Controller\Service', $params);
        $viewModel->addChild($listeViewModel, 'recherche');

        if ('afficher' == $action || 'trier' == $action) {
            $params = [
                'tri'              => $tri,
                'isoler-non-payes' => false,
                'regroupement'     => 'intervenant',
            ];
            if ($structure = $this->getServiceContext()->getSelectedIdentityRole()->getStructure()) {
                $params['composante'] = $structure;
            }
            $resumeServices = $this->getServiceService()->getTableauBord($recherche, $params);
        } else {
            $resumeServices = null;
        }

        $viewModel->setVariables(compact('annee', 'action', 'resumeServices', 'canAddService'));

        return $viewModel;
    }



    public function resumeRefreshAction()
    {
        $filter = $this->getFormServiceRecherche()->hydrateFromSession();

        return compact('filter');
    }



    public function rechercheAction()
    {
        $errors        = [];
        $service       = $this->getServiceService();
        $rechercheForm = $this->getFormServiceRecherche();
        $entity        = $service->loadRecherche();
        $rechercheForm->bind($entity);

        $request = $this->getRequest();
        /* @var $request Request */
        if ('afficher' === $request->getQuery('action', null)) {
            $rechercheForm->setData($request->getQuery());
            if ($rechercheForm->isValid()) {
                try {
                    $service->saveRecherche($entity);
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }
            } else {
                $errors[] = 'Les données de recherche saisies sont invalides.';
            }
        }

        return compact('rechercheForm', $errors);
    }



    public function rafraichirLigneAction()
    {
        $this->initFilters();

        $params      = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $details     = 1 == (int)$this->params()->fromQuery('details', (int)$this->params()->fromPost('details', 0));
        $onlyContent = 1 == (int)$this->params()->fromQuery('only-content', 0);
        $service     = $this->getEvent()->getParam('service');

        return compact('service', 'params', 'details', 'onlyContent');
    }



    public function horodatageAction()
    {
        $intervenant       = $this->getEvent()->getParam('intervenant');
        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        $referentiel       = $this->params('referentiel') ? true : false;

        return compact('intervenant', 'typeVolumeHoraire', 'referentiel');
    }



    public function volumesHorairesRefreshAction()
    {
        $this->initFilters();

        $id                = (int)$this->params()->fromRoute('id');
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire'));
        if (empty($typeVolumeHoraire)) {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        } else {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get($typeVolumeHoraire);
        }
        $service       = $this->getServiceService();
        $form          = $this->getFormServiceSaisie();
        $element       = $this->context()->elementPedagogiqueFromPost('element');
        $etablissement = $this->context()->etablissementFromPost();

        if ($id) {
            $entity = $service->get($id);
            /* @var $entity \Application\Entity\Db\Service */
        } else {
            $entity = $service->newEntity();
        }
        $entity->setTypeVolumeHoraire($typeVolumeHoraire);
        $entity->setEtablissement($etablissement);
        $entity->setElementPedagogique($element);
        $form->bind($entity);

        if (!$id) $form->initFromContext();

        return compact('form');
    }



    public function initialisationAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $this->getServiceService()->setPrevusFromPrevus($intervenant);
        $errors = [];

        return compact('errors');
    }



    public function constatationAction()
    {
        $this->initFilters();
        $errors   = [];
        $services = $this->params()->fromQuery('services');
        if ($services) {
            $services = explode(',', $services);
            foreach ($services as $sid) {
                $service = $this->getServiceService()->get($sid);
                $intervenants[$service->getIntervenant()->getId()] = $service->getIntervenant();
                $service->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getRealise());
                if ($this->isAllowed($service, Privileges::ENSEIGNEMENT_EDITION)) {
                    try {
                        $this->getServiceService()->setRealisesFromPrevus($service);
                    } catch (\Exception $e) {
                        $errors[] = $e->getMessage();
                    }
                }
            }
        }

        foreach( $intervenants as $id => $intervenant ){
            $this->updateTableauxBord($intervenant);
        }

        return compact('errors');
    }



    public function suppressionAction()
    {
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire'));
        if (empty($typeVolumeHoraire)) {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        } else {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get($typeVolumeHoraire);
        }
        $service = $this->getEvent()->getParam('service');
        /* @var $service Service */

        if (!$service) {
            throw new \LogicException('Le service n\'existe pas');
        }
        $service->setTypeVolumeHoraire($typeVolumeHoraire);
        if (!$this->isAllowed($service, Privileges::ENSEIGNEMENT_EDITION)) {
            throw new \LogicException("Cette opération n'est pas autorisée.");
        }

        if ($this->getRequest()->isPost()) {
            try {
                $this->getServiceService()->delete($service);
                $this->updateTableauxBord($service->getIntervenant());
                $this->flashMessenger()->addSuccessMessage('Suppression effectuée');
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }

        return new MessengerViewModel;
    }



    public function saisieAction()
    {
        $this->initFilters();
        $id                = (int)$this->params()->fromRoute('id');
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire'));
        if (empty($typeVolumeHoraire)) {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        } else {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get($typeVolumeHoraire);
        }
        $service = $this->getServiceService();
        $form    = $this->getFormServiceSaisie();

        $intervenant = $this->getServiceLocalContext()->getIntervenant();

        if ($id) {
            $entity = $service->get($id);
            $entity->setTypeVolumeHoraire($typeVolumeHoraire);
            $form->bind($entity);
            $title = "Modification d'enseignement";
        } else {
            $entity = $service->newEntity();
            $entity->setTypeVolumeHoraire($typeVolumeHoraire);
            $entity->setIntervenant($intervenant);
            $form->bind($entity);
            $form->initFromContext();
            $title = "Ajout d'enseignement";
        }

        if ($intervenant) {
            $form->get('service')->setCanSaisieExterieur($this->isAllowed($intervenant, Privileges::ENSEIGNEMENT_EXTERIEUR));
        } else {
            $form->get('service')->setCanSaisieExterieur($this->isAllowed(Privileges::getResourceId(Privileges::ENSEIGNEMENT_EXTERIEUR)));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            if (!$this->isAllowed($entity, Privileges::ENSEIGNEMENT_EDITION)) {
                $this->flashMessenger()->addErrorMessage("Vous n'êtes pas autorisé à créer ou modifier ce service.");
            } else {

                $form->setData($request->getPost());
                $form->saveToContext();
                if ($form->isValid()) {
                    try {
                        $entity = $service->save($entity->setIntervenant($intervenant));
                        $this->updateTableauxBord($intervenant);
                        $form->get('service')->get('id')->setValue($entity->getId()); // transmet le nouvel ID
                    } catch (\Exception $e) {
                        $e = DbException::translate($e);
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.');
                }
            }
        }

        return compact('form', 'title');
    }



    public function validationAction()
    {
        $this->initFilters();

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $filterStructure = null;//$role->getStructure(); // pour filtrer les affichages à la structure concernée uniquement
        // pas de filtre pour qu'une composante puisse voir ses enseignements validée par d'autres en prévisionnel

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        if (!$intervenant){
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($this->params()->fromRoute('type-volume-horaire-code', 'PREVU'));

        $rsv = $this->getServiceRegleStructureValidation()->getBy($typeVolumeHoraire, $intervenant);
        if ($rsv && $rsv->getMessage()) {
            $this->flashMessenger()->addInfoMessage($rsv->getMessage());
        }

        $title = "Validation des enseignements";

        if ($typeVolumeHoraire->isPrevu()) {
            $title .= " prévisionnels";
        } elseif ($typeVolumeHoraire->isRealise()) {
            $title .= " réalisés";
        }

        $services = [
            'valides'     => [],
            'non-valides' => [],
        ];

        $validations = $this->getProcessusValidationEnseignement()->lister($typeVolumeHoraire, $intervenant, $filterStructure);
        foreach ($validations as $validation) {
            $key                  = $validation->getId() ? 'valides' : 'non-valides';
            $vid                  = $this->getProcessusValidationEnseignement()->getValidationId($validation);
            $sList                = $this->getProcessusValidationEnseignement()->getServices($typeVolumeHoraire, $validation);
            $services[$key][$vid] = $sList;
        }

        /* Messages */
        if (empty($services['non-valides'])) {
            if ($role->getIntervenant()) {
                $message = sprintf(
                    "Tous vos enseignements %s ont été validés.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnels" : "réalisés"
                );
            } else {
                $message = sprintf(
                    "Aucun enseignement %s n'est en attente de validation.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnel" : "réalisé"
                );
            }
            $this->flashMessenger()->addSuccessMessage($message);
        }

        return compact('title', 'typeVolumeHoraire', 'intervenant', 'validations', 'services');
    }



    public function validerAction()
    {
        $this->initFilters();

        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        /* @var $typeVolumeHoraire TypeVolumeHoraire */

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $structure = $this->getEvent()->getParam('structure');
        /* @var $structure Structure */


        $validation = $this->getProcessusValidationEnseignement()->creer($intervenant, $structure);

        if ($this->isAllowed($validation, Privileges::ENSEIGNEMENT_VALIDATION)) {
            if ($this->getRequest()->isPost()) {
                try {
                    $this->getProcessusValidationEnseignement()->enregistrer($typeVolumeHoraire, $validation);
                    $this->updateTableauxBord($validation->getIntervenant(), true);
                    $this->flashMessenger()->addSuccessMessage(
                        "Validation effectuée avec succès."
                    );
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
                }
            }
        } else {
            $this->flashMessenger()->addErrorMessage('Vous n\'avez pas le droit de valider ces enseignements.');
        }

        return new MessengerViewModel();
    }



    public function devaliderAction()
    {
        $this->initFilters();

        $validation = $this->getEvent()->getParam('validation');
        /* @var $validation Validation */

        if ($this->isAllowed($validation, Privileges::ENSEIGNEMENT_DEVALIDATION)) {
            if ($this->getRequest()->isPost()) {
                try {
                    $this->getProcessusValidationEnseignement()->supprimer($validation);
                    $this->updateTableauxBord($validation->getIntervenant(), true);
                    $this->flashMessenger()->addSuccessMessage(
                        "Dévalidation effectuée avec succès."
                    );
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
                }
            }
        } else {
            $this->flashMessenger()->addErrorMessage('Vous n\'avez pas le droit de dévalider ces enseignements.');
        }

        return new MessengerViewModel();
    }



    private function updateTableauxBord(Intervenant $intervenant, $validation=false)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'formule',
            'validation_enseignement'
        ], $intervenant);

        if (!$validation){
            $this->getServiceWorkflow()->calculerTableauxBord(['service_saisie','service','piece_jointe_fournie'], $intervenant);
        }
    }
}
