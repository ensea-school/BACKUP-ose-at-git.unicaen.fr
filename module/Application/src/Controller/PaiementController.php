<?php

namespace Application\Controller;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Service;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\TypeRessource;
use Application\Entity\Db\Validation;
use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\VolumeHoraireReferentiel;
use Application\Entity\Db\WfEtape;
use Application\Form\Paiement\Traits\MiseEnPaiementFormAwareTrait;
use Application\Form\Paiement\Traits\MiseEnPaiementRechercheFormAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DotationServiceAwareTrait;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\MiseEnPaiementServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\ServiceAPayerServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Application\Service\Traits\TypeRessourceServiceAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use Laminas\Json\Json;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\Paiement\MiseEnPaiementRecherche;

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
    use ServiceAPayerServiceAwareTrait;
    use TypeIntervenantServiceAwareTrait;
    use MiseEnPaiementFormAwareTrait;
    use MiseEnPaiementRechercheFormAwareTrait;
    use SessionContainerTrait;
    use TypeRessourceServiceAwareTrait;
    use DotationServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use EtatSortieServiceAwareTrait;


    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs
     * éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            MiseEnPaiement::class,
            Service::class,
            VolumeHoraire::class,
            ServiceReferentiel::class,
            VolumeHoraireReferentiel::class,
            Validation::class,
            TypeRessource::class,
        ]);
    }



    public function indexAction()
    {
        return [];
    }



    /**
     * @return int
     */
    protected function getChangeIndex()
    {
        $session = $this->getSessionContainer();
        if (!isset($session->cgtIndex)) $session->cgtIndex = 0;
        $result = $session->cgtIndex;
        $session->cgtIndex++;

        return $result;
    }



    protected function isChangeIndexSaved($changeIndex)
    {
        $session = $this->getSessionContainer();
        if (!isset($session->cht)) $session->cht = [];

        return isset($session->cht[$changeIndex]) && $session->cht[$changeIndex];
    }



    protected function setChangeIndexSaved($changeIndex)
    {
        $session = $this->getSessionContainer();
        if (!isset($session->cht)) $session->cht = [];
        $session->cht[$changeIndex] = true;

        return $this;
    }



    public function demandeMiseEnPaiementAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $this->initFilters();
        $intervenant = $this->getEvent()->getParam('intervenant');

        //Un intervenant n'a pas le droit de voir cette page de demande de mise en paiement
        if ($role->getIntervenant()) {
            //On redirige vers la visualisation des mises en paiement
            $this->redirect()->toRoute('intervenant/mise-en-paiement/visualisation', ['intervenant' => $intervenant->getId()]);
        }
        // pour empêcher le ré-enregistrement avec un rafraichissement (F5)
        $postChangeIndex = (int)$this->params()->fromPost('change-index');
        $changeIndex     = $this->getChangeIndex();

        /* @var $intervenant \Application\Entity\Db\Intervenant */
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $saved = false;
        if ($this->getRequest()->isPost() && !$this->isChangeIndexSaved($postChangeIndex)) {
            $changements = $this->params()->fromPost('changements', '{}');
            $changements = Json::decode($changements, Json::TYPE_ARRAY);
            $this->getServiceMiseEnPaiement()->saveChangements($changements);
            $this->updateTableauxBord($intervenant);
            $this->setChangeIndexSaved($postChangeIndex);
            $saved = true;
        }
        $servicesAPayer = $this->getServiceServiceAPayer()->getListByIntervenant($intervenant);

        /* On récupère du workflow les raisons de non édition éventuelles (selon sa structure le cas échéant) */
        $workflowEtape  = $this->getServiceWorkflow()->getEtape(WfEtape::CODE_DEMANDE_MEP, $intervenant);
        $etapes         = $workflowEtape->getEtapes();
        $whyNotEditable = [];
        foreach ($etapes as $we) {
            if (!$role->getStructure() || !$we->getStructure() || $role->getStructure() == $we->getStructure()) {
                $sid  = $we->getStructure() ? $we->getStructure()->getId() : 0;
                $deps = $we->getEtapeDeps();
                foreach ($deps as $dep) {
                    if (!isset($whyNotEditable[$sid])) {
                        $whyNotEditable[$sid] = [
                            'structure' => (string)$we->getStructure(),
                            'raisons'   => [],
                        ];
                    }
                    $whyNotEditable[$sid]['raisons'][] = $dep->getWfEtapeDep()->getEtapePrec()->getDescNonFranchie();
                }
            }
        }


        $dateDerniereModif   = null;
        $dernierModificateur = null;

        $typesRessources = $this->getServiceTypeRessource()->getList();
        $structures      = [];

        foreach ($servicesAPayer as $sap) {
            if (null == $role->getStructure() || $sap->getStructure() == $role->getStructure()) {
                $structures[$sap->getStructure()->getId()] = $sap->getStructure();
            }
            $mepListe = $sap->getMiseEnPaiement();
            foreach ($mepListe as $mep) {
                /* @var $mep MiseEnPaiement */
                $dateModification = $mep->getHistoModification();

                if ($dateDerniereModif == null || $dateDerniereModif < $dateModification) {
                    $dateDerniereModif   = $dateModification;
                    $dernierModificateur = $mep->getHistoModificateur();
                }
            }
        }

        $budget = [
            'structures'      => $structures,
            'typesRessources' => $typesRessources,
        ];
        $dot    = $this->getServiceDotation()->getTableauBord($structures);
        $liq    = $this->getServiceMiseEnPaiement()->getTblLiquidation($structures);
        foreach ($structures as $structure) {
            $sid = $structure->getId();
            foreach ($typesRessources as $typeRessource) {
                $trid = $typeRessource->getId();

                $dotation = isset($dot[$sid][$trid]) ? $dot[$sid][$trid] : 0;
                $usage    = isset($liq[$sid][$trid]) ? $liq[$sid][$trid] : 0;

                $budget[$sid][$trid] = compact('dotation', 'usage');
            }
        }

        return compact('intervenant', 'changeIndex', 'servicesAPayer', 'saved', 'dateDerniereModif', 'dernierModificateur', 'budget', 'whyNotEditable');
    }



    public function visualisationMiseEnPaiementAction()
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
          Application\Entity\Db\TblPaiement p
        WHERE
          p.intervenant = :intervenant";

        $query     = $this->em()->createQuery($dql)->setParameter('intervenant', $intervenant);
        $paiements = $query->getResult();

        return compact('intervenant', 'paiements');
    }



    public function editionMiseEnPaiementAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $mep       = $this->params()->fromPost('mep', null);
        $paiements = [];
        /* @var $paiements MiseEnPaiement[] */

        $dql = "
            SELECT
              mep, frs, fr, pp, s, cc, df, ep, str
            FROM
              Application\Entity\Db\MiseEnPaiement mep
              JOIN mep.formuleResultatService frs
              JOIN frs.formuleResultat fr
              LEFT JOIN mep.periodePaiement pp
              JOIN frs.service s
              LEFT JOIN mep.centreCout cc
              LEFT JOIN mep.domaineFonctionnel df
              LEFT JOIN s.elementPedagogique ep
              LEFT JOIN ep.structure str
            WHERE
              fr.intervenant = :intervenant
              AND mep.histoDestruction IS NULL
        ";

        $res       = $this->em()->createQuery($dql)->setParameter('intervenant', $intervenant);
        $paiements = array_merge($paiements, $res->getResult());

        $dql = "
            SELECT
              mep, frsr, fr, pp, sr, cc, df, f, str
            FROM
              Application\Entity\Db\MiseEnPaiement mep
              JOIN mep.formuleResultatServiceReferentiel frsr
              JOIN frsr.formuleResultat fr
              LEFT JOIN mep.periodePaiement pp
              JOIN frsr.serviceReferentiel sr
              LEFT JOIN mep.centreCout cc
              LEFT JOIN mep.domaineFonctionnel df
              LEFT JOIN sr.fonction f
              LEFT JOIN sr.structure str
            WHERE
              fr.intervenant = :intervenant
              AND mep.histoDestruction IS NULL
        ";

        $res       = $this->em()->createQuery($dql)->setParameter('intervenant', $intervenant);
        $paiements = array_merge($paiements, $res->getResult());


        foreach ($paiements as $index => $paiement) {
            if ($mep[$paiement->getId()] == "1") {
                if ($paiement->getPeriodePaiement()) {
                    $paiement->setPeriodePaiement(null);
                    $paiement->setDateMiseEnPaiement(null);
                    $this->getServiceMiseEnPaiement()->save($paiement);
                    $this->updateTableauxBord($intervenant);
                } else {
                    $this->getServiceMiseEnPaiement()->delete($paiement);
                }

                unset($paiements[$index]);
            }
        }

        return compact('intervenant', 'paiements');
    }



    public function etatPaiementAction()
    {
        $this->initFilters();

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $recherche = new MiseEnPaiementRecherche;
        $recherche->setEtat($this->params()->fromRoute('etat')); // données à mettre en paiement uniquement
        $recherche->setAnnee($this->getServiceContext()->getAnnee());
        $recherche->setTypeIntervenant($this->context()->typeIntervenantFromPost('type-intervenant'));

        $rechercheForm = $this->getFormPaiementMiseEnPaiementRecherche();
        $rechercheForm->bind($recherche);

        if ($role->getStructure()) {
            $structures = [$role->getStructure()->getId() => $role->getStructure()];
        } else {
            $qb = $this->getServiceStructure()->finderByMiseEnPaiement();
            $this->getServiceStructure()->finderByRole($role, $qb);
            $this->getServiceMiseEnPaiement()->finderByTypeIntervenant($recherche->getTypeIntervenant(), $qb);
            $this->getServiceMiseEnPaiement()->finderByEtat($recherche->getEtat(), $qb);
            $structures = $this->getServiceStructure()->getList($qb);
        }

        $rechercheForm->populateStructures($structures);

        if (!$recherche->getStructure()) {
            if (count($structures) == 1) {
                $structure = current($structures);
                $recherche->setStructure($structure);
                $rechercheForm->get('structure')->setValue($structure->getId());
                $noData = false;
            } elseif (count($structures) == 0) {
                $noData = true;
                $recherche->setStructure($this->context()->structureFromPost());
            } else {
                $noData = false;
                $recherche->setStructure($this->context()->structureFromPost());
            }
        }

        if ($recherche->getStructure()) {
            $qb = $this->getServicePeriode()->finderByMiseEnPaiement($recherche->getStructure());
            $this->getServiceMiseEnPaiement()->finderByTypeIntervenant($recherche->getTypeIntervenant(), $qb);
            $this->getServiceMiseEnPaiement()->finderByEtat($recherche->getEtat(), $qb);
            $periodes = $this->getServicePeriode()->getList($qb);
            $rechercheForm->populatePeriodes($periodes);
            if (count($periodes) == 1) {
                $recherche->setPeriode(current($periodes));
                $rechercheForm->get('periode')->setValue($recherche->getPeriode()->getId());
            } else {
                $recherche->setPeriode($this->context()->periodeFromPost());
            }

            $qb = $this->getServiceIntervenant()->finderByMiseEnPaiement($recherche->getStructure(), $recherche->getPeriode());
            $this->getServiceIntervenant()->finderByAnnee($recherche->getAnnee(), $qb);
            $this->getServiceMiseEnPaiement()->finderByTypeIntervenant($recherche->getTypeIntervenant(), $qb);
            $this->getServiceMiseEnPaiement()->finderByEtat($recherche->getEtat(), $qb);
            $intervenants = $this->getServiceIntervenant()->getList($qb);
            $rechercheForm->populateIntervenants($intervenants);
            $noData = count($intervenants) == 0;
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $rechercheForm->setData($request->getPost());
            $rechercheForm->isValid();
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

            return compact('recherche', 'rechercheForm', 'etatPaiement', 'noData');
        }
    }



    private function makeFilenameFromRecherche(MiseEnPaiementRecherche $recherche)
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



    public function misesEnPaiementCsvAction()
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



    public function extractionWinpaieAction()
    {
        $this->initFilters();
        $periode = $this->params()->fromRoute('periode');
        $periode = $this->getServicePeriode()->getRepo()->findOneBy(['code' => $periode]);

        $type = $this->params()->fromRoute('type');
        $type = $this->getServiceTypeIntervenant()->getRepo()->findOneBy(['code' => $type]);

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

            $etatSortie = $this->getServiceEtatSortie()->getByParametre('es_winpaie');
            $csvModel   = $this->getServiceEtatSortie()->genererCsv($etatSortie, $filters);
            $csvModel->setFilename(str_replace(' ', '_', 'ose-export-winpaie-' . strtolower($recherche->getPeriode()->getLibelleAnnuel($recherche->getAnnee())) . '-' . strtolower($recherche->getTypeIntervenant()->getLibelle()) . '.csv'));

            return $csvModel;
        }
    }



    public function miseEnPaiementAction()
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



    /**
     * @param Intervenant $intervenant
     */
    private function updateTableauxBord($intervenant)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'paiement',
        ], $intervenant);
    }
}