<?php

namespace Service\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\ElementPedagogique;
use Enseignement\Entity\Db\Service;
use Application\Entity\Db\Validation;
use Service\Form\RechercheFormAwareTrait;
use Enseignement\Processus\EnseignementProcessusAwareTrait;
use Laminas\View\Model\ViewModel;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Service\Service\RechercheServiceAwareTrait;
use Service\Service\ResumeServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Laminas\Http\Request;
use Application\Entity\Db\Intervenant;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Recherche;
use Application\Service\Traits\ContextServiceAwareTrait;

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
            \Application\Entity\Db\Validation::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            ElementPedagogique::class,
        ]);
    }



    public function indexAction()
    {
        $this->initFilters();

        $annee  = $this->getServiceContext()->getAnnee();
        $action = $this->getRequest()->getQuery('action', null);
        $tri    = ('trier' == $action) ? $this->getRequest()->getQuery('tri', null) : null;

        $viewHelperParams = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $viewModel        = new ViewModel();

        $canAddService = Privileges::ENSEIGNEMENT_PREVU_EDITION || Privileges::ENSEIGNEMENT_REALISE_EDITION;

        $params             = $this->getEvent()->getRouteMatch()->getParams();
        $params['action']   = 'recherche';
        $rechercheViewModel = $this->forward()->dispatch(ServiceController::class, $params);
        $viewModel->addChild($rechercheViewModel, 'recherche');

        $recherche = $this->getServiceRecherche()->loadRecherche();

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
        $annee  = $this->getServiceContext()->getAnnee();
        $action = $this->getRequest()->getQuery('action', null);
        $tri    = null;
        if ('trier' == $action) $tri = $this->getRequest()->getQuery('tri', null);


        $this->rechercheAction();
        $recherche = $this->getServiceRecherche()->loadRecherche();

        $viewModel = new \Laminas\View\Model\ViewModel();

        $params           = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'recherche';
        $listeViewModel   = $this->forward()->dispatch(ServiceController::class, $params);
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
            $resumeServices = $this->getServiceResume()->getTableauBord($recherche, $params);
        } else {
            $resumeServices = null;
        }

        $viewModel->setVariables(compact('annee', 'action', 'resumeServices'));
        $viewModel->setTemplate('service/service/index');

        return $viewModel;
    }



    public function intervenantSaisieAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Enseignement\Entity\Db\Service::class,
            \Enseignement\Entity\Db\VolumeHoraire::class,
            \Application\Entity\Db\CheminPedagogique::class,
            \Referentiel\Entity\Db\ServiceReferentiel::class,
            \Referentiel\Entity\Db\VolumeHoraireReferentiel::class,
            \Application\Entity\Db\Validation::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            \Application\Entity\Db\ElementPedagogique::class,
        ]);

        $typeVolumeHoraireCode = $this->params()->fromRoute('type-volume-horaire-code');
        $typeVolumeHoraire     = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        if ($this->params()->fromQuery('menu', false) !== false) { // pour gérer uniquement l'affichage du menu
            $vh = new ViewModel();
            $vh->setTemplate('application/intervenant/menu');

            return $vh;
        }

        $campagneSaisie = $this->getServiceCampagneSaisie()->getBy($intervenant->getStatut()->getTypeIntervenant(), $typeVolumeHoraire);
        if (!$campagneSaisie->estOuverte()) {

            $role = $this->getServiceContext()->getSelectedIdentityRole();
            if ($message = $campagneSaisie->getMessage($role)) {
                if ($role->getIntervenant()) {
                    $this->flashMessenger()->addErrorMessage($message);
                } else {
                    $this->flashMessenger()->addWarningMessage($message);
                }
            }
        }

        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getSaisi();

        $vm = new ViewModel();
        $vm->setTemplate('services/intervenant/saisie');

        /* Liste des services */
        $this->getServiceLocalContext()->setIntervenant($intervenant); // passage au contexte pour le présaisir dans le formulaire de saisie
        $recherche = new Recherche($typeVolumeHoraire, $etatVolumeHoraire);
        $recherche->setIntervenant($intervenant);

        if ($this->isAllowed($intervenant, $typeVolumeHoraire->getPrivilegeEnseignementVisualisation())) {
            $services = $this->getProcessusEnseignement()->getEnseignements($recherche);
        } else {
            $services = false;
        }

        /* Services référentiels (si nécessaire) */
        if ($this->isAllowed($intervenant, $typeVolumeHoraire->getPrivilegeReferentielVisualisation())) {
            $servicesReferentiel = $this->getProcessusServiceReferentiel()->getServices($intervenant, $recherche);
        } else {
            $servicesReferentiel = false;
        }

        /* Totaux HETD */
        $params = $this->getEvent()->getRouteMatch()->getParams();
        $this->getEvent()->setParam('typeVolumeHoraire', $typeVolumeHoraire);
        $this->getEvent()->setParam('etatVolumeHoraire', $etatVolumeHoraire);
        $params['action'] = 'formuleTotauxHetd';
        $widget           = $this->forward()->dispatch('Application\Controller\Intervenant', $params);
        if ($widget) $vm->addChild($widget, 'formuleTotauxHetd');

        /* Clôture de saisie (si nécessaire) */
        if ($typeVolumeHoraire->isRealise() && $intervenant->getStatut()->getCloture()) {
            $cloture = $this->getServiceValidation()->getValidationClotureServices($intervenant);
        } else {
            $cloture = null;
        }

        $vm->setVariables(compact('intervenant', 'typeVolumeHoraire', 'services', 'servicesReferentiel', 'cloture', 'role'));

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
                    $this->getServiceWorkflow()->calculerTableauxBord('cloture_realise', $intervenant);
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
                    $this->getServiceWorkflow()->calculerTableauxBord('cloture_realise', $intervenant);
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
            $filters['STRUCTURE_AFF_ID OR STRUCTURE_ENS_ID'] = $structure->getId();
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
