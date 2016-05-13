<?php

namespace Application\Controller;

use Application\Entity\Db\ElementPedagogique;
use Application\Form\Service\Traits\RechercheFormAwareTrait;
use Application\Form\Service\Traits\SaisieAwareTrait;
use Application\Processus\Traits\ServiceProcessusAwareTrait;
use Application\Processus\Traits\ValidationEnseignementProcessusAwareTrait;
use Application\Processus\Traits\ValidationProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\LocalContextAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use UnicaenApp\View\Model\MessengerViewModel;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;
use Application\Exception\DbException;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Service\Recherche;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireAwareTrait;
use Application\Service\Traits\ElementPedagogiqueAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use Application\Service\Traits\TypeInterventionAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\ServiceReferentielAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\PeriodeAwareTrait;

/**
 * Description of ServiceController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceController extends AbstractController
{
    use ServiceProcessusAwareTrait;
    use ContextAwareTrait;
    use ServiceAwareTrait;
    use VolumeHoraireAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use TypeInterventionAwareTrait;
    use IntervenantAwareTrait;
    use ServiceReferentielAwareTrait;
    use EtatVolumeHoraireAwareTrait;
    use ValidationAwareTrait;
    use StructureAwareTrait;
    use EtapeAwareTrait;
    use PeriodeAwareTrait;
    use LocalContextAwareTrait;
    use SaisieAwareTrait;
    use RechercheFormAwareTrait;
    use ValidationEnseignementProcessusAwareTrait;



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

        $canAddService = true; /* A REVOIR ! ! ! */

        /*if (!$this->isAllowed($serviceProto, Privileges::ENSEIGNEMENT_VISUALISATION)) {
            $eStr = 'L\'accès au service ' . lcfirst($this->getTypeVolumeHoraire()->getLibelle()) . ' est interdit.';
            throw new UnAuthorizedException($eStr);
        }*/

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



    /**
     * Clôture de la saisie du réalisé.
     *
     * GET  : affichage du bouton permettant de clôturer la saisie.
     * POST : création d'une validation pour clôturer la saisie, ou suppression pour déclôturer.
     *
     * @return ViewModel
     */
    public function cloturerSaisieAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        if (!$intervenant) {
            return false; // désactive la vue
        }

        $structure  = $intervenant->getStructure();
        $tvh        = $this->getTypeVolumeHoraire();
        $validation = $this->getServiceValidation()->findValidationClotureServices($intervenant, $tvh); // clôture existante
        $viewModel  = new ViewModel();

        if (!$this->isAllowed($intervenant, Privileges::ENSEIGNEMENT_CLOTURE)) {
            return false; // désactive la vue
        }
        if (TypeVolumeHoraire::CODE_REALISE !== $tvh->getCode()) {
            return false; // désactive la vue
        }

        if (!$validation) {
            $validation = $this->getServiceValidation()->createValidationClotureServices($intervenant, $structure, $tvh);
        }

        if ($this->getRequest()->isPost()) {
            $cloturer = $this->params()->fromPost('cloturer');
            if (null === $cloturer || $validation->getId() && 1 === $cloturer || !$validation->getId() && 0 === $cloturer) {
                exit;
            }
            if ($cloturer) {
                $this->em()->persist($validation);
            } else {
                $this->em()->remove($validation);
            }
            $this->em()->flush();
        }

        if ($validation->getId()) {
            $dateCloture = $validation->getHistoModification()->format(\Application\Constants::DATETIME_FORMAT);
            $this->messenger()->addMessage("La saisie du service réalisé a été clôturée le $dateCloture.", 'success');
        }

        $avertissement = "<strong>Attention!</strong> <br />"
            . "Assurez-vous d'avoir saisi la totalité de vos services réalisés (enseignements et référentiel), "
            . "quelle que soit la composante d'intervention. <br />"
            . "Cliquer sur le bouton ci-dessous vous empêchera de revenir sur votre saisie.";
        $confirm       = "Attention! "
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
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervernant');
        /* @var $intervenant Intervenant  */



        $this->initFilters();
        if ($intervenant) {
            $localContext = $this->getServiceLocator()->get('applicationLocalContext');
            /* @var $localContext \Application\Service\LocalContext */
            $localContext->setIntervenant($intervenant);
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



    /**
     * Totaux de services et de référentiel par intervenant.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function resumeAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervernant');
        /* @var $intervenant Intervenant  */

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
                if ($this->isAllowed($service, 'update')) {
                    try {
                        $this->getServiceService()->setRealisesFromPrevus($service);
                    } catch (\Exception $e) {
                        $errors[] = $e->getMessage();
                    }
                }
            }
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
        $id      = (int)$this->params()->fromRoute('id', 0);
        $service = $this->getServiceService()->get($id);
        $title   = "Suppression de service";
        $form    = new \Application\Form\Supprimer('suppr');
        $form->setServiceLocator($this->getServiceLocator()->get('formElementManager'));
        $form->init();
        $form->add(new \Zend\Form\Element\Hidden('type-volume-horaire'));
        $viewModel = new \Zend\View\Model\ViewModel();

        $service->setTypeVolumeHoraire($typeVolumeHoraire);
        if (!$this->isAllowed($service, 'delete')) {
            throw new \LogicException("Cette opération n'est pas autorisée.");
        }

        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->get('type-volume-horaire')->setValue($typeVolumeHoraire->getId());

        if ($this->getRequest()->isPost()) {
            $errors = [];
            try {
                if ($typeVolumeHoraire->getCode() === \Application\Entity\Db\TypeVolumeHoraire::CODE_REALISE) {
                    // destruction des volumes horaires associés
                    foreach ($service->getVolumeHoraire() as $vh) {
                        if ($vh->getTypeVolumeHoraire() === $typeVolumeHoraire) {
                            $this->getServiceVolumeHoraire()->delete($vh);
                        }
                    }
                } else {
                    // destruction du service même
                    $this->getServiceService()->delete($service);
                }
            } catch (\Exception $e) {
                $e        = DbException::translate($e);
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

        $filterStructure = $role->getStructure(); // pour filtrer les affichages à la structure concernée uniquement

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($this->params()->fromRoute('type-volume-horaire-code', 'PREVU'));

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
        /* @var $structure Structure */

        if ($this->isAllowed($validation, Privileges::ENSEIGNEMENT_DEVALIDATION)) {
            if ($this->getRequest()->isPost()) {
                try {
                    $this->getProcessusValidationEnseignement()->supprimer($validation);

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
            $typeVolumeHoraireCode   = $this->params()->fromRoute('type-volume-horaire-code', 'PREVU');
            $this->typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getByCode($typeVolumeHoraireCode);
        }

        return $this->typeVolumeHoraire;
    }
}
