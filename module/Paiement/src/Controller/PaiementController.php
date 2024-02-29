<?php

namespace Paiement\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Role;
use Application\Entity\Db\Validation;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Enseignement\Entity\Db\VolumeHoraire;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use Paiement\Entity\Db\MiseEnPaiement;
use Paiement\Entity\Db\TypeRessource;
use Paiement\Entity\MiseEnPaiementRecherche;
use Paiement\Form\Paiement\MiseEnPaiementFormAwareTrait;
use Paiement\Form\Paiement\MiseEnPaiementRechercheFormAwareTrait;
use Paiement\Service\DotationServiceAwareTrait;
use Paiement\Service\MiseEnPaiementServiceAwareTrait;
use Paiement\Service\NumeroPriseEnChargeServiceAwareTrait;
use Paiement\Service\TypeRessourceServiceAwareTrait;
use Paiement\Tbl\Process\PaiementDebugger;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use UnicaenApp\Traits\SessionContainerTrait;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;

/**
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PaiementController extends AbstractController
{
    use ContextServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use MiseEnPaiementServiceAwareTrait;
    use TypeIntervenantServiceAwareTrait;
    use MiseEnPaiementFormAwareTrait;
    use MiseEnPaiementRechercheFormAwareTrait;
    use SessionContainerTrait;
    use TypeRessourceServiceAwareTrait;
    use DotationServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use EtatSortieServiceAwareTrait;
    use TableauBordServiceAwareTrait;
    use NumeroPriseEnChargeServiceAwareTrait;

    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs
     * éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters ()
    {
        $this->em()->getFilters()->enable('historique')->init([
            MiseEnPaiement::class,
            VolumeHoraire::class,
            ServiceReferentiel::class,
            VolumeHoraireReferentiel::class,
            Validation::class,
            TypeRessource::class,
        ]);
    }



    public function indexAction ()
    {
        return [];
    }



    public function demandeMiseEnPaiementAction ()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $this->initFilters();
        $intervenant = $this->getEvent()->getParam('intervenant');
        //Un intervenant n'a pas le droit de voir cette page de demande de mise en paiement
        if ($role->getIntervenant()) {
            //On redirige vers la visualisation des mises en paiement
            $this->redirect()->toRoute('intervenant/mise-en-paiement/visualisation', ['intervenant' => $intervenant->getId()]);
        }


        return compact('intervenant');
    }



    public function demandeMiseEnPaiementAllAction ()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $this->initFilters();
        $intervenant = $this->getEvent()->getParam('intervenant');
        if ($this->getRequest()->isPost() && !$role->getIntervenant()) {
            $post = file_get_contents('php://input') ?? $_POST;

            $demandes = json_decode($post, true);
            $error    = 0;
            $budget   = 0;
            $success  = 0;
            foreach ($demandes as $demande) {
                try {
                    $this->getServiceMiseEnPaiement()->verifierBudgetDemandeMiseEnPaiement($demande);
                    $this->getServiceMiseEnPaiement()->verifierDemandeMiseEnPaiement($intervenant, $demande);
                    $this->getServiceMiseEnPaiement()->ajouterDemandeMiseEnPaiement($intervenant, $demande);
                    $this->updateTableauxBord($intervenant);
                } catch (\Exception $e) {
                    if ($e->getCode() == 3) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                    if ($e->getCode() == 4) {
                        $budget++;
                    }
                    continue;
                }
                $success++;
            }
            $this->updateTableauxBord($intervenant);
            //Traitement des messages de succes ou d'erreur (Toast)
            if ($success == 0) {
                $this->flashMessenger()->addInfoMessage('Aucun demande de mise en paiement a effectué pour cette composante');
            }
            if ($success > 0) {
                if ($success > 1) {
                    $this->flashMessenger()->addSuccessMessage($success . " demandes de mise en paiement ont été effectué pour cette composante.");
                } else {
                    $this->flashMessenger()->addSuccessMessage($success . " demande de mise en paiement a été effectué pour cette composante.");
                }
            }
            if ($error > 0) {
                if ($error > 1) {
                    $this->flashMessenger()->addErrorMessage("Attention, $error demandes de mise en paiement n'ont pas pu être traité pour cette composante car vous n'avez pas sélectionné un centre de coût et/ou un domaine fonctionnel.");
                } else {
                    $this->flashMessenger()->addErrorMessage("Attention, $error demande de mise en paiement n'a pas pu être traité pour cette composante car vous n'avez pas sélectionné un centre de coût et/ou un domaine fonctionnel.");
                }
            }

            if ($budget > 0) {
                if ($budget > 1) {
                    $this->flashMessenger()->addErrorMessage("Attention, $budget demandes de mise en paiement n'ont pas pu être traité pour cette composante car votre budget ne permet plus d'en faire la demande.");
                } else {
                    $this->flashMessenger()->addErrorMessage("Attention, $budget demande de mise en paiement n'a pas pu être traité pour cette composante car car votre budget ne permet plus d'en faire la demande.");
                }
            }

            return true;
        }

        return false;
    }



    public function supprimerMiseEnPaiementAction ()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $this->initFilters();
        $idDmep      = $this->params()->fromRoute('mise-en-paiement');
        $intervenant = $this->getEvent()->getParam('intervenant');
        //Un intervenant ne peut pas supprimer des demandes de mise en paiement
        if ($role->getIntervenant()) {
            //On redirige vers la visualisation des mises en paiement
            return false;
        }
        //on supprimer la demande de mise en paiement
        try {
            $this->getServiceMiseEnPaiement()->supprimerDemandeMiseEnPaiement($idDmep);
            $this->flashMessenger()->addSuccessMessage("Demande de mise en paiement supprimer.");
            $this->updateTableauxBord($intervenant);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());

            return false;
        }

        return true;
    }



    public function ajouterMiseEnPaiementAction ()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $this->initFilters();
        $intervenant = $this->getEvent()->getParam('intervenant');
        if ($role instanceof Role && $role->getIntervenant()) {
            //On redirige vers la visualisation des mises en paiement
            return false;
        }
        if ($this->getRequest()->isPost() && !$role->getIntervenant()) {
            $post = file_get_contents('php://input') ?? $_POST;

            $datas = current(json_decode($post, true));

            try {

                $this->getServiceMiseEnPaiement()->verifierBudgetDemandeMiseEnPaiement($datas);
                $this->getServiceMiseEnPaiement()->verifierDemandeMiseEnPaiement($intervenant, $datas);
                $this->getServiceMiseEnPaiement()->ajouterDemandeMiseEnPaiement($intervenant, $datas);
                $this->updateTableauxBord($intervenant);
                $this->flashMessenger()->addSuccessMessage("Demande de mise en paiement effecutée");
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());

                return false;
            }

            //On recalcule le tableau de bord paiement de l'intervenant concerné
            return true;
        }

        return false;
    }



    public function listeServiceAPayerAction ()
    {
        $structure = null;
        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $this->initFilters();
        $intervenant = $this->getEvent()->getParam('intervenant');
        //Un intervenant ne peut pas récuperer les datas de demande de mise en paiement
        if ($role->getIntervenant() && $role instanceof \Application\Acl\Role) {
            return new AxiosModel([]);
        }
        //$this->updateTableauxBord($intervenant);
        if ($role->getPerimetre()->isComposante()) {
            $structure = $role->getStructure();
        }
        $servicesAPayer = $this->getServiceMiseEnPaiement()->getDemandeMiseEnPaiementResume($intervenant, $structure);


        return new AxiosModel($servicesAPayer);
    }



    function demandeMiseEnPaiementLotAction ()
    {
        $title        = 'Demande de mise en paiement par lot';
        $intervenants = [];
        $structures   = $this->getServiceStructure()->getStructuresDemandeMiseEnPaiement();
        if ($this->getRequest()->isPost()) {
            //On récupere les données post notamment la structure recherchée
            $idStructure  = $this->getRequest()->getPost('structure');
            $structure    = $this->em()->find(Structure::class, $idStructure);
            $intervenants = $this->getServiceMiseEnPaiement()->getListByStructure($structure);

            return new AxiosModel($intervenants);
        }

        return compact('title', 'structures', 'intervenants');
    }



    function processDemandeMiseEnPaiementLotAction ()
    {

        if ($this->getRequest()->isPost()) {
            $datasIntervenant = $this->getRequest()->getPost('intervenant');
            if (empty($datasIntervenant)) {
                return false;
            }
            $intervenantIds = array_keys($datasIntervenant);
            foreach ($intervenantIds as $id) {
                $intervenant = $this->getServiceIntervenant()->get($id);
                if ($intervenant) {
                    $this->getServiceMiseEnPaiement()->demandesMisesEnPaiementIntervenant($intervenant);
                }
            }
            $this->flashMessenger()->addSuccessMessage("Les demandes de mise en paiement ont bien été effectuée");

            return $this->redirect()->toRoute('paiement/demande-mise-en-paiement-lot');
        }
    }



    public function visualisationMiseEnPaiementAction ()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $dql = "
        SELECT
          p
        FROM
          Paiement\Entity\Db\TblPaiement p
        WHERE
          p.intervenant = :intervenant";

        $query     = $this->em()->createQuery($dql)->setParameter('intervenant', $intervenant);
        $paiements = $query->getResult();

        return compact('intervenant', 'paiements');
    }



    public function editionMiseEnPaiementAction ()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $mep       = $this->params()->fromPost('mep', []);
        $paiements = [];
        /* @var $paiements MiseEnPaiement[] */

        $structure = $this->getServiceContext()->getSelectedIdentityRole()->getStructure();

        $parameters = [
            'intervenant' => $intervenant,
        ];

        if ($structure) {
            $parameters['structure'] = $structure->idsFilter();
        }

        $dql = "
            SELECT
              mep, frs, fr, pp, s, cc, df, ep, str
            FROM
              Paiement\Entity\Db\MiseEnPaiement mep
              JOIN mep.formuleResultatService frs
              JOIN frs.formuleResultat fr
              LEFT JOIN mep.periodePaiement pp
              JOIN frs.service s
              JOIN s.intervenant i
              LEFT JOIN i.structure istr
              LEFT JOIN mep.centreCout cc
              LEFT JOIN mep.domaineFonctionnel df
              LEFT JOIN s.elementPedagogique ep
              LEFT JOIN ep.structure str
            WHERE
              fr.intervenant = :intervenant
              AND mep.histoDestruction IS NULL
              " . ($structure ? 'AND COALESCE(str.ids,istr.ids) LIKE :structure' : '') . "
        ";

        $res       = $this->em()->createQuery($dql)->setParameters($parameters);
        $paiements = array_merge($paiements, $res->getResult());

        $dql = "
            SELECT
              mep, frsr, fr, pp, sr, cc, df, f, str
            FROM
              Paiement\Entity\Db\MiseEnPaiement mep
              JOIN mep.formuleResultatServiceReferentiel frsr
              JOIN frsr.formuleResultat fr
              LEFT JOIN mep.periodePaiement pp
              JOIN frsr.serviceReferentiel sr
              JOIN sr.structure str
              LEFT JOIN mep.centreCout cc
              LEFT JOIN mep.domaineFonctionnel df
              LEFT JOIN sr.fonctionReferentiel f
            WHERE
              fr.intervenant = :intervenant
              AND mep.histoDestruction IS NULL
              " . ($structure ? 'AND str.ids LIKE :structure' : '') . "
        ";

        $res       = $this->em()->createQuery($dql)->setParameters($parameters);
        $paiements = array_merge($paiements, $res->getResult());

        $dql = "
            SELECT
              mep, m
            FROM
              Paiement\Entity\Db\MiseEnPaiement mep
              JOIN mep.mission m
              JOIN m.structure str
            WHERE
              m.intervenant = :intervenant
              AND mep.histoDestruction IS NULL
              " . ($structure ? 'AND str.ids LIKE :structure' : '') . "
        ";

        $res       = $this->em()->createQuery($dql)->setParameters($parameters);
        $paiements = array_merge($paiements, $res->getResult());

        foreach ($paiements as $index => $paiement) {
            if (isset($mep[$paiement->getId()]) && $mep[$paiement->getId()] == "1") {
                if ($paiement->getPeriodePaiement()) {
                    $paiement->setPeriodePaiement(null);
                    $paiement->setDateMiseEnPaiement(null);
                    $this->getServiceMiseEnPaiement()->save($paiement);
                } else {
                    $this->getServiceMiseEnPaiement()->delete($paiement);
                    unset($paiements[$index]);
                }
            }
        }
        $this->updateTableauxBord($intervenant);

        return compact('intervenant', 'paiements');
    }



    public function etatPaiementAction ()
    {
        $this->initFilters();

        $recherche = new MiseEnPaiementRecherche;
        $recherche->setEtat($this->params()->fromRoute('etat')); // données à mettre en paiement uniquement
        $recherche->setAnnee($this->getServiceContext()->getAnnee());

        $rechercheForm = $this->getFormPaiementMiseEnPaiementRecherche();
        $rechercheForm->setPeriodeFilter(MiseEnPaiement::MIS_EN_PAIEMENT == $recherche->getEtat());
        $rechercheForm->bind($recherche);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $rechercheForm->setData($request->getPost());
            $rechercheForm->populateAll();
            $rechercheForm->isValid();
        } else {
            $rechercheForm->populateAll();
        }

        $etatSortie = $this->getServiceEtatSortie()->getByParametre('es_etat_paiement');

        if ($this->params()->fromPost('exporter-pdf') !== null && ($this->isAllowed(Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_PDF)) || $this->isAllowed(Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_PDF_ETAT)))) {
            $document = $this->getServiceEtatSortie()->genererPdf($etatSortie, $recherche->getFilters());
            $document->download($this->makeFilenameFromRecherche($recherche) . '.pdf');
        } elseif ($this->params()->fromPost('exporter-csv-etat') !== null && $this->isAllowed(Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_CSV))) {
            $csvModel = $this->getServiceEtatSortie()->genererCsv($etatSortie, $recherche->getFilters());
            $csvModel->setFilename($this->makeFilenameFromRecherche($recherche) . '.csv');

            return $csvModel;
        } else {
            $etatPaiement = null;
            if ($recherche->getIntervenants()->count() > 0) {
                $etatPaiement = $this->getServiceMiseEnPaiement()->getEtatPaiement($recherche);
            }

            return compact('recherche', 'rechercheForm', 'etatPaiement');
        }
    }



    private function makeFilenameFromRecherche (MiseEnPaiementRecherche $recherche)
    {
        if ($recherche->getEtat() == MiseEnPaiement::A_METTRE_EN_PAIEMENT) {
            $filename = 'demande_mise_en_paiement';
        } else {
            $filename = 'etat_paiement';
        }

        if ($recherche->getStructure()) $filename .= '_' . strtolower($recherche->getStructure()->getSourceCode());
        if ($recherche->getPeriode()) $filename .= '_' . strtolower($recherche->getPeriode()->getLibelleCourt());
        $filename .= '_' . date('Y-m-d');

        return $filename;
    }



    public function misesEnPaiementCsvAction ()
    {
        $this->initFilters();
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $etatSortie = $this->getServiceEtatSortie()->getByParametre('es_etat_paiement');

        $recherche = new MiseEnPaiementRecherche;
        $recherche->setAnnee($this->getServiceContext()->getAnnee());
        if ($role->getStructure()) {
            $recherche->setStructure($role->getStructure());
        }

        $csvModel = $this->getServiceEtatSortie()->genererCsv($etatSortie, $recherche->getFilters());
        $csvModel->setFilename($this->makeFilenameFromRecherche($recherche) . '.csv');

        return $csvModel;
    }



    public function extractionPaieAction ()
    {
        $this->initFilters();
        $periode = $this->params()->fromRoute('periode');
        $periode = $this->getServicePeriode()->getRepo()->findOneBy(['code' => $periode]);
        $type    = $this->params()->fromRoute('type');
        $type    = $this->getServiceTypeIntervenant()->getRepo()->findOneBy(['code' => $type]);

        $annee = $this->getServiceContext()->getAnnee();
        $role  = $this->getServiceContext()->getSelectedIdentityRole();

        if (empty($type)) {
            $types = $this->getServiceTypeIntervenant()->getList();

            return compact('types');
        } elseif (empty($periode)) {
            $qb = $this->getServicePeriode()->finderByMiseEnPaiement();
            $this->getServiceMiseEnPaiement()->finderByEtat(MiseEnPaiement::MIS_EN_PAIEMENT, $qb);
            $periodes = $this->getServicePeriode()->getList($qb);

            return compact('type', 'periodes', 'annee');
        } else {
            $recherche = new MiseEnPaiementRecherche;
            $recherche->setAnnee($annee);
            $recherche->setStructure($role->getStructure());
            $recherche->setPeriode($periode);
            $recherche->setTypeIntervenant($type);
            $filters = $recherche->getFilters();

            $etatSortie = $this->getServiceEtatSortie()->getByParametre('es_extraction_paie');
            $csvModel   = $this->getServiceEtatSortie()->genererCsv($etatSortie, $filters, ['periode' => $periode, 'annee' => $annee]);
            $csvModel->setFilename(str_replace(' ', '_', 'ose-export-paie-' . strtolower($recherche->getPeriode()->getLibelleAnnuel($recherche->getAnnee())) . '-' . strtolower($recherche->getTypeIntervenant()->getLibelle()) . '.csv'));

            return $csvModel;
        }
    }



    public function extractionPaiePrimeAction ()
    {
        $this->initFilters();
        $periode = $this->params()->fromRoute('periode');
        $annee   = $this->getServiceContext()->getAnnee();
        $role    = $this->getServiceContext()->getSelectedIdentityRole();
        if (empty($periode)) {
            $periodes = $this->getServicePeriode()->getPaiement();
        } else {
            $periode   = $this->getServicePeriode()->getByCode($periode);
            $recherche = new MiseEnPaiementRecherche;
            $recherche->setAnnee($annee);
            $recherche->setStructure($role->getStructure());
            $recherche->setPeriode($periode);
            $filters = $recherche->getFilters();

            $etatSortie = $this->getServiceEtatSortie()->getRepo()->findOneBy(['code' => 'winpaie-indemnites']);
            $csvModel   = $this->getServiceEtatSortie()->genererCsv($etatSortie, $filters, ['periode' => $periode, 'annee' => $annee]);
            $csvModel->setFilename(str_replace(' ', '_', 'ose-export-indemnite-' . strtolower($periode->getLibelleAnnuel($annee)) . '.csv'));

            return $csvModel;
        }


        return compact('periodes', 'annee');
    }



    public function imputationSihamAction ()
    {
        $this->initFilters();

        $recherche = new MiseEnPaiementRecherche;
        $recherche->setEtat($this->params()->fromRoute('etat'));
        $recherche->setAnnee($this->getServiceContext()->getAnnee());

        $rechercheForm = $this->getFormPaiementMiseEnPaiementRecherche();
        $rechercheForm->setStructureFilter(false);
        $rechercheForm->bind($recherche);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $rechercheForm->setData($request->getPost());
            $rechercheForm->populateAll();
            $rechercheForm->isValid();
        } else {
            $rechercheForm->populateAll();
        }

        $etatSortie = $this->getServiceEtatSortie()->getRepo()->findOneBy(['code' => 'imputation-budgetaire']);

        if ($this->params()->fromPost('exporter-csv-imputation') !== null && $this->isAllowed(Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_CSV))) {
            $csvModel = $this->getServiceEtatSortie()->genererCsv($etatSortie, $recherche->getFilters());
            if ($recherche->getPeriode() != null) {
                $csvModel->setFilename(str_replace(' ', '_', 'imputation_siham_' . strtolower($recherche->getPeriode()->getLibelleAnnuel($recherche->getAnnee())) . '_' . strtolower(($recherche->getTypeIntervenant()) ? $recherche->getTypeIntervenant()->getLibelle() : 'vactaire_et_permanent') . '.csv'));
            }

            return $csvModel;
        } else {
            return compact('recherche', 'rechercheForm');
        }
    }



    public function miseEnPaiementAction ()
    {
        $this->initFilters();
        $title        = 'Mise en paiement';
        $structure    = $this->getEvent()->getParam('structure');
        $intervenants = $this->params('intervenants');

        $form    = $this->getFormPaiementMiseEnPaiement();
        $errors  = [];
        $request = $this->getRequest();
        if ($request->isPost() && $this->isAllowed(Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_MISE_EN_PAIEMENT))) {
            $form->setData($request->getPost());
            $form->isValid();

            $periodeId = $form->get('periode')->getValue();
            $periode   = $this->getServicePeriode()->get($periodeId);
            /* @var $periode \Application\Entity\Db\Periode */

            $dateMiseEnPaiementValue = $this->params()->fromPost('date-mise-en-paiement');
            if ($dateMiseEnPaiementValue) {
                $dateMiseEnPaiement = \DateTime::createFromFormat('d/m/Y', $dateMiseEnPaiementValue);
            } else {
                $dateMiseEnPaiement = $periode->getDatePaiement($this->getServiceContext()->getAnnee()); // à défaut
            }

            $intervenants = $this->getServiceIntervenant()->get(explode(',', $intervenants));
            try {
                $this->getServiceMiseEnPaiement()->mettreEnPaiement($structure, $intervenants, $periode, $dateMiseEnPaiement);
                foreach ($intervenants as $intervenant) {
                    $this->updateTableauxBord($intervenant);
                }
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        return compact('form', 'title', 'errors');
    }



    public function pilotageAction ()
    {
        return [];
    }



    public function ecartsEtatsACtion ()
    {

        //Contexte année et structure
        $annee     = $this->getServiceContext()->getAnnee();
        $structure = $this->getServiceContext()->getStructure();

        $filters['ANNEE_ID'] = $annee->getId();
        if ($structure) {
            $filters['STRUCTURE_IDS'] = $structure->idsFilter();
        }
        //On récupére l'état de sortie pour l'export des agréments
        $etatSortie = $this->getServiceEtatSortie()->getRepo()->findOneBy(['code' => 'ecarts-heures-complementaire']);
        $csvModel   = $this->getServiceEtatSortie()->genererCsv($etatSortie, $filters);
        $csvModel->setFilename('ecarts-heures-complementaires-' . $annee->getId() . '.csv');

        return $csvModel;
    }



    public function importNumeroPecAction ()
    {
        $this->initFilters();
        $title = 'Import des numéros de prise en charge';

        if ($this->getRequest()->isPost()) {
            $files                      = $this->getRequest()->getFiles()->toArray();
            $datas                      = $this->getRequest()->getPost();
            $importFile                 = $files['importFile'];
            $serviceNumeroPriseEnCharge = $this->getServiceNumeroPriseEnCharge();

            return $serviceNumeroPriseEnCharge->treatImportFile($importFile, $datas['modeleImport']);
        }

        return compact('title');
    }



    public function detailsCalculsAction ()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $tblPaiement = $this->getServiceTableauBord()->getTableauBord('paiement');
        $debugger    = new PaiementDebugger($tblPaiement->getProcess());
        $debugger->run($intervenant);

        return compact('intervenant', 'debugger');
    }



    /**
     * @param Intervenant $intervenant
     */
    private function updateTableauxBord ($intervenant)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'paiement',
        ], $intervenant);
    }
}