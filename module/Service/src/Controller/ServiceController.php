<?php

namespace Service\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Application\Provider\Tbl\TblProvider;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Enseignement\Processus\EnseignementProcessusAwareTrait;
use EtatSortie\Service\EtatSortieServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Http\Request;
use Laminas\View\Model\ViewModel;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Referentiel\Processus\ServiceReferentielProcessusAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Recherche;
use Service\Form\RechercheFormAwareTrait;
use Service\Service\CampagneSaisieServiceAwareTrait;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\RechercheServiceAwareTrait;
use Service\Service\ResumeServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Workflow\Entity\Db\Validation;
use Workflow\Service\ValidationServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;

/**
 * Description of ServiceController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceController extends AbstractController
{
    use EnseignementProcessusAwareTrait;
    use ContextServiceAwareTrait;
    use RechercheServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use RechercheFormAwareTrait;
    use WorkflowServiceAwareTrait;
    use EtatSortieServiceAwareTrait;
    use RechercheServiceAwareTrait;
    use ResumeServiceAwareTrait;
    use CampagneSaisieServiceAwareTrait;
    use ServiceReferentielProcessusAwareTrait;
    use ValidationServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;


    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs
     * éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
                                                                  \Enseignement\Entity\Db\Service::class,
                                                                  \Enseignement\Entity\Db\VolumeHoraire::class,
                                                                  \Workflow\Entity\Db\Validation::class,
                                                              ]);
        $this->em()->getFilters()->enable('annee')->init([
                                                             ElementPedagogique::class,
                                                         ]);
    }



    public function indexAction()
    {
        $this->initFilters();

        $annee   = $this->getServiceContext()->getAnnee();
        $action  = $this->getRequest()->getQuery('action', null);
        $element = $this->getRequest()->getQuery('element-pedagogique', null);
        $tri     = ('trier' == $action) ? $this->getRequest()->getQuery('tri', null) : null;

        $viewHelperParams = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $viewModel        = new ViewModel();

        if ($this->getServiceContext()->getStructure()) {
            $this->flashMessenger()->addWarningMessage(
                "Sont visibles ici les référentiels et les enseignements prévisionnels des intervenants affectés "
                . "ou enseignant dans votre structure de responsabilité ou l'une de ses sous-structures."
            );
        }

        $canAddService = Privileges::ENSEIGNEMENT_PREVU_EDITION || Privileges::ENSEIGNEMENT_REALISE_EDITION;

        $params             = $this->getEvent()->getRouteMatch()->getParams();
        $params['action']   = 'recherche';
        $rechercheViewModel = $this->forward()->dispatch(ServiceController::class, $params);
        $viewModel->addChild($rechercheViewModel, 'recherche');

        $recherche = $this->getServiceRecherche()->loadRecherche();
        if ($recherche->getEtape() != null && $recherche->getStructureEns() != null) {
            if (isset($element['element-liste']) && $element['element-liste'] != null) {
                $recherche->setElementPedagogique($this->getServiceElementPedagogique()->get($element['element-liste']));
            } else {
                $recherche->setElementPedagogique(null);
            }
        }

        /* Préparation et affichage */
        if ('afficher' === $action) {
            $services = $this->getProcessusEnseignement()->getEnseignements($recherche);
            /* Services référentiels */
        } else {
            $services = [];
        }
        $typeVolumeHoraire = $recherche->getTypeVolumeHoraire();
        $params            = $viewHelperParams;
        $viewModel->setVariables(compact('services', 'annee', 'typeVolumeHoraire', 'action', 'canAddService', 'params'));
        $viewModel->setTemplate('service/service/index');

        return $viewModel;
    }



    public function resumeAction()
    {
        $annee   = $this->getServiceContext()->getAnnee();
        $action  = $this->getRequest()->getQuery('action', null);
        $tri     = null;
        $element = $this->getRequest()->getQuery('element-pedagogique', null);
        if ('trier' == $action) $tri = $this->getRequest()->getQuery('tri', null);


        $this->rechercheAction();
        $recherche = $this->getServiceRecherche()->loadRecherche();

        $viewModel = new \Laminas\View\Model\ViewModel();

        $params           = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'recherche';
        $listeViewModel   = $this->forward()->dispatch(ServiceController::class, $params);
        $viewModel->addChild($listeViewModel, 'recherche');

        if ($recherche->getEtape() != null && $recherche->getStructureEns() != null) {
            if (isset($element['element-liste']) && $element['element-liste'] != null) {
                $recherche->setElementPedagogique($this->getServiceElementPedagogique()->get($element['element-liste']));
            } else {
                $recherche->setElementPedagogique(null);
            }
        }

        if ('afficher' == $action || 'trier' == $action) {
            $params = [
                'tri'              => $tri,
                'isoler-non-payes' => false,
                'regroupement'     => 'intervenant',
            ];
            if ($structure = $this->getServiceContext()->getStructure()) {
                $params['composante'] = $structure;
            }
            $resumeServices = $this->getServiceResume()->getTableauBord($recherche, $params);
        } else {
            $resumeServices = null;
        }

        $viewModel->setVariables(compact('annee', 'action', 'resumeServices'));
        $viewModel->setTemplate('service/service/index');

        return $viewModel;
    }



    public function intervenantSaisiePrevuAction()
    {
        $prevu = $this->getServiceTypeVolumeHoraire()->getPrevu();

        return $this->intervenantSaisieAction($prevu);
    }



    public function intervenantSaisieRealiseAction()
    {
        $realise = $this->getServiceTypeVolumeHoraire()->getRealise();

        return $this->intervenantSaisieAction($realise);
    }



    protected function intervenantSaisieAction(TypeVolumeHoraire $typeVolumeHoraire)
    {
        $this->em()->getFilters()->enable('historique')->init([
                                                                  \Enseignement\Entity\Db\Service::class,
                                                                  \Enseignement\Entity\Db\VolumeHoraire::class,
                                                                  \OffreFormation\Entity\Db\CheminPedagogique::class,
                                                                  \Referentiel\Entity\Db\ServiceReferentiel::class,
                                                                  \Referentiel\Entity\Db\VolumeHoraireReferentiel::class,
                                                                  \Workflow\Entity\Db\Validation::class,
                                                              ]);
        $this->em()->getFilters()->enable('annee')->init([
                                                             \OffreFormation\Entity\Db\ElementPedagogique::class,
                                                         ]);

        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }
        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getSaisi();

        if ($this->params()->fromQuery('menu', false) !== false) { // pour gérer uniquement l'affichage du menu
            $vh = new ViewModel();
            $vh->setTemplate('intervenant/intervenant/menu');

            return $vh;
        }

        $this->getServiceLocalContext()->setIntervenant($intervenant); // passage au contexte pour le présaisir dans le formulaire de saisie
        $recherche = new Recherche($typeVolumeHoraire, $etatVolumeHoraire);
        $recherche->setIntervenant($intervenant);

        $vm = new ViewModel();


        //        if ($this->params()->fromQuery('menu', false) !== false) { // pour gérer uniquement l'affichage du menu
        //            $vh = new ViewModel();
        //            $vh->setTemplate('intervenant/intervenant/menu');
        //
        //            return $vh;
        //        }

        $campagneSaisie = $this->getServiceCampagneSaisie()->getBy($intervenant->getStatut()->getTypeIntervenant(), $typeVolumeHoraire);
        if (!$campagneSaisie->estOuverte()) {
            if ($message = $campagneSaisie->getMessage((bool)$this->getServiceContext()->getIntervenant())) {
                if ($this->getServiceContext()->getIntervenant()) {
                    $this->flashMessenger()->addErrorMessage($message);
                } else {
                    $this->flashMessenger()->addWarningMessage($message);
                }
            }
        }

        /* Liste des services */
        if ($this->isAllowed($intervenant, $typeVolumeHoraire->getPrivilegeEnseignementVisualisation())) {
            $enseignements = $this->getProcessusEnseignement()->getEnseignements($recherche);
        } else {
            $enseignements = false;
        }

        /* Services référentiels (si nécessaire) */
        if ($this->isAllowed($intervenant, $typeVolumeHoraire->getPrivilegeReferentielVisualisation())) {
            $referentiels = $this->getProcessusServiceReferentiel()->getReferentiels($recherche);
        } else {
            $referentiels = false;
        }

        /* Clôture de saisie (si nécessaire) */
        if ($typeVolumeHoraire->isRealise() && $intervenant->getStatut()->getCloture()) {
            $cloture = $this->getServiceValidation()->getValidationClotureServices($intervenant);
        } else {
            $cloture = null;
        }

        $vm->setTemplate('service/intervenant/saisie');
        $vm->setVariables(compact('intervenant', 'typeVolumeHoraire', 'enseignements', 'referentiels', 'cloture'));

        return $vm;
    }



    public function intervenantClotureAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
                                                                  Validation::class,
                                                              ]);

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $validation = $this->getServiceValidation()->getValidationClotureServices($intervenant);

        if ($this->getRequest()->isPost()) {
            if ($validation->getId()) {
                if (!$this->isAllowed($intervenant, Privileges::CLOTURE_REOUVERTURE)) {
                    throw new \Exception("Vous n'avez pas le droit de déclôturer la saisie de services réalisés d'un intervenant");
                }
                try {
                    $this->getServiceValidation()->delete($validation);
                    $this->getServiceWorkflow()->calculerTableauxBord(TblProvider::CLOTURE_REALISE, $intervenant);
                    $this->flashMessenger()->addSuccessMessage("La saisie du service réalisé a bien été réouverte", 'success');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            } else {
                if (!$this->isAllowed($intervenant, Privileges::CLOTURE_CLOTURE)) {
                    throw new \Exception("Vous n'avez pas le droit de clôturer la saisie de services réalisés d'un intervenant");
                }
                try {
                    $this->getServiceValidation()->save($validation);
                    $this->getServiceWorkflow()->calculerTableauxBord(TblProvider::CLOTURE_REALISE, $intervenant);
                    $this->flashMessenger()->addSuccessMessage("La saisie du service réalisé a bien été clôturée", 'success');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        }

        return new MessengerViewModel;
    }



    public function exportCsvAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        $annee     = $this->getServiceContext()->getAnnee();
        $structure = $this->getServiceContext()->getStructure();

        if (!$intervenant) {
            $rr        = $this->rechercheAction();
            $recherche = $rr['rechercheForm']->getObject();
        } else {
            $recherche = new Recherche;
            $recherche->setTypeVolumeHoraire($this->getServiceTypeVolumehoraire()->getPrevu());
            $recherche->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
            $recherche->setIntervenant($intervenant);
        }

        /* Préparation et affichage */
        $etatSortie = $this->getServiceEtatSortie()->getByParametre('es_services_csv');
        $fileName   = 'Listing des services - ' . date('dmY') . '.csv';

        $filters             = $recherche->getFilters();
        $filters['ANNEE_ID'] = $annee->getId();
        if ($structure) {
            $filters['STRUCTURE_AFF_IDS OR STRUCTURE_ENS_IDS'] = $structure->idsFilter();
        }

        $options = [
            'annee'               => $annee->getLibelle(),
            'type_volume_horaire' => $recherche->getTypeVolumeHoraire()->getLibelle(),
            'etat_volume_horaire' => $recherche->getEtatVolumeHoraire()->getLibelle(),
            'composante'          => $recherche->getStructureAff() ? $recherche->getStructureAff()->getLibelleCourt() : 'Toutes',
            'type_intervenant'    => $recherche->getTypeIntervenant() ? $recherche->getTypeIntervenant()->getLibelle() : 'Tous intervenants',
        ];

        $csv = $this->getServiceEtatSortie()->genererCsv($etatSortie, $filters, $options);
        $csv->setFilename($fileName);

        return $csv;
    }



    public function exportPdfAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        $annee     = $this->getServiceContext()->getAnnee();
        $structure = $this->getServiceContext()->getStructure();

        if (!$intervenant) {
            $rr        = $this->rechercheAction();
            $recherche = $rr['rechercheForm']->getObject();
        } else {
            $recherche = new Recherche();
            $recherche->setTypeVolumeHoraire($this->getServiceTypeVolumehoraire()->getPrevu());
            $recherche->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
            $recherche->setIntervenant($intervenant);
        }

        $etatSortie = $this->getServiceEtatSortie()->getByParametre('es_services_pdf');
        $fileName   = 'Listing des services - ' . date('dmY') . '.pdf';

        $filters             = $recherche->getFilters();
        $filters['ANNEE_ID'] = $annee->getId();
        if ($structure) {
            $filters['STRUCTURE_AFF_ID OR STRUCTURE_ENS_ID'] = $structure->getId();
        }

        $options = [
            'annee'               => $annee->getLibelle(),
            'type_volume_horaire' => $recherche->getTypeVolumeHoraire()->getLibelle(),
            'etat_volume_horaire' => $recherche->getEtatVolumeHoraire()->getLibelle(),
            'composante'          => $recherche->getStructureAff() ? $recherche->getStructureAff()->getLibelleCourt() : 'Toutes',
            'type_intervenant'    => $recherche->getTypeIntervenant() ? $recherche->getTypeIntervenant()->getLibelle() : 'Tous intervenants',
        ];

        $document = $this->getServiceEtatSortie()->genererPdf($etatSortie, $filters, $options);

        $document->download($fileName);
    }



    public function rechercheAction()
    {
        $rechercheForm = $this->getFormServiceRecherche();
        $entity        = $this->getServiceRecherche()->loadRecherche();
        $rechercheForm->bind($entity);

        $request = $this->getRequest();
        /* @var $request Request */
        if ('afficher' === $request->getQuery('action', null)) {
            $rechercheForm->setData($request->getQuery());
            if ($rechercheForm->isValid()) {
                $this->getServiceRecherche()->saveRecherche($entity);
            } else {
                $errors[] = 'Les données de recherche saisies sont invalides.';
            }
        }

        return compact('rechercheForm');
    }



    public function horodatageAction()
    {
        $intervenant       = $this->getEvent()->getParam('intervenant');
        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        $referentiel       = $this->params('referentiel') ? true : false;

        return compact('intervenant', 'typeVolumeHoraire', 'referentiel');
    }
}
