<?php

namespace Application\Controller;

use Application\Entity\Db\Service;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\Validation;
use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\VolumeHoraireReferentiel;
use Application\Form\Paiement\Traits\MiseEnPaiementFormAwareTrait;
use Application\Form\Paiement\Traits\MiseEnPaiementRechercheFormAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\MiseEnPaiementAwareTrait;
use Application\Service\Traits\PeriodeAwareTrait;
use Application\Service\Traits\PersonnelAwareTrait;
use Application\Service\Traits\ServiceAPayerAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Application\Service\Traits\TypeIntervenantAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;
use UnicaenApp\Exporter\Pdf;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\Paiement\MiseEnPaiementRecherche;

/**
 * @method \Application\Controller\Plugin\Context     context()
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PaiementController extends AbstractActionController
{
    use ContextAwareTrait;
    use IntervenantAwareTrait;
    use ServiceAwareTrait;
    use StructureAwareTrait;
    use PersonnelAwareTrait;
    use PeriodeAwareTrait;
    use MiseEnPaiementAwareTrait;
    use ServiceAPayerAwareTrait;
    use TypeIntervenantAwareTrait;
    use MiseEnPaiementFormAwareTrait;
    use MiseEnPaiementRechercheFormAwareTrait;
    use SessionContainerTrait;



    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
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
        if (! isset($session->cgtIndex)) $session->cgtIndex = 0;
        $result = $session->cgtIndex;
        $session->cgtIndex ++;
        return $result;
    }


    protected function isChangeIndexSaved( $changeIndex ){
        $session = $this->getSessionContainer();
        if (! isset($session->cht)) $session->cht = [];
        return isset($session->cht[$changeIndex]) && $session->cht[$changeIndex];
    }


    protected function setChangeIndexSaved( $changeIndex ){
        $session = $this->getSessionContainer();
        if (! isset($session->cht)) $session->cht = [];
        $session->cht[$changeIndex] = true;

        return $this;
    }


    public function demandeMiseEnPaiementAction()
    {
        // pour empêcher le ré-enregistrement avec un rafraichissement (F5)
        $postChangeIndex = (int)$this->params()->fromPost('change-index');
        $changeIndex = $this->getChangeIndex();

        $this->initFilters();
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant \Application\Entity\Db\Intervenant */
        $saved = false;
        if ($this->getRequest()->isPost() && ! $this->isChangeIndexSaved($postChangeIndex)) {
            $changements = $this->params()->fromPost('changements', '{}');
            $changements = Json::decode($changements, Json::TYPE_ARRAY);
            $this->getServiceMiseEnPaiement()->saveChangements($changements);
            $this->setChangeIndexSaved($postChangeIndex);
            $saved = true;
        }
        $servicesAPayer = $this->getServiceServiceAPayer()->getListByIntervenant($intervenant);

        $dateDerniereModif   = null;
        $dernierModificateur = null;

        foreach ($servicesAPayer as $sap) {
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

        return compact('intervenant', 'changeIndex', 'servicesAPayer', 'saved', 'dateDerniereModif', 'dernierModificateur');
    }



    public function etatPaiementAction()
    {
        $this->initFilters();

        $etat = $this->params()->fromRoute('etat');

        $rechercheForm = $this->getFormPaiementMiseEnPaiementRecherche();
        $recherche     = new MiseEnPaiementRecherche;
        $recherche->setEtat($etat); // données à mettre en paiement uniquement
        $rechercheForm->bind($recherche);

        $typeIntervenant = $this->context()->typeIntervenantFromPost('type-intervenant');

        $qb = $this->getServiceStructure()->finderByMiseEnPaiement();
        $this->getServiceStructure()->finderByRole($this->getServiceContext()->getSelectedIdentityRole(), $qb);
        $this->getServiceMiseEnPaiement()->finderByTypeIntervenant($typeIntervenant, $qb);
        $this->getServiceMiseEnPaiement()->finderByEtat($etat, $qb);
        $structures = $this->getServiceStructure()->getList($qb);
        $rechercheForm->populateStructures($structures);
        if (count($structures) == 1) {
            $structure = current($structures);
            $rechercheForm->get('structure')->setValue($structure->getId());
            $noData = false;
        } elseif (count($structures) == 0) {
            $noData    = true;
            $structure = $this->context()->structureFromPost();
            /* @var $structure \Application\Entity\Db\Structure */
        } else {
            $noData    = false;
            $structure = $this->context()->structureFromPost();
            /* @var $structure \Application\Entity\Db\Structure */
        }

        $periode = null;
        if ($structure) {
            $qb = $this->getServicePeriode()->finderByMiseEnPaiement($structure);
            $this->getServiceMiseEnPaiement()->finderByTypeIntervenant($typeIntervenant, $qb);
            $this->getServiceMiseEnPaiement()->finderByEtat($etat, $qb);
            $periodes = $this->getServicePeriode()->getList($qb);
            $rechercheForm->populatePeriodes($periodes);

            if (count($periodes) == 1) {
                $periode = current($periodes);
                $rechercheForm->get('periode')->setValue($periode->getId());
            } else {
                $periode = $this->context()->periodeFromPost();
                /* @var $periode \Application\Entity\Db\Periode */
            }

            $qb = $this->getServiceIntervenant()->finderByMiseEnPaiement($structure, $periode);
            $this->getServiceIntervenant()->finderByAnnee( $this->getServiceContext()->getAnnee(), $qb );
            $this->getServiceMiseEnPaiement()->finderByTypeIntervenant($typeIntervenant, $qb);
            $this->getServiceMiseEnPaiement()->finderByEtat($etat, $qb);
            $rechercheForm->populateIntervenants($this->getServiceIntervenant()->getList($qb));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $rechercheForm->setData($request->getPost());
            $rechercheForm->isValid();
        }

        $etatPaiement = null;
        if ($recherche->getIntervenants()->count() > 0) {
            $etatPaiement = $this->getServiceMiseEnPaiement()->getEtatPaiement($recherche);
        }

        if ($this->params()->fromPost('exporter-pdf') !== null && $this->isAllowed(Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_PDF))) {
            $this->etatPaiementPdf($typeIntervenant, $etat, $structure, $periode, $etatPaiement);
        } elseif ($this->params()->fromPost('exporter-csv-etat') !== null && $this->isAllowed(Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_CSV))) {
            return $this->etatPaiementCsv($recherche);
        } else {
            return compact('rechercheForm', 'etatPaiement', 'etat', 'noData');
        }
    }



    protected function etatPaiementPdf($typeIntervenant, $etat, $structure, $periode, $etatPaiement)
    {
        $exp = new Pdf($this->getServiceLocator()->get('view_manager')->getRenderer());

        switch ($etat) {
            case MiseEnPaiement::A_METTRE_EN_PAIEMENT   :
                $fileName = 'demande_mise_en_paiement';
                $title    = 'Demandes de mises en paiement';
                //$exp->setWatermark("Demandes");
                break;
            default                                     :
                $fileName = 'etat_paiement';
                $title    = 'État de paiement';
        }

        if ($structure) $fileName .= '_' . strtolower($structure->getSourceCode());
        if ($periode) $fileName .= '_' . strtolower($periode->getLibelleCourt());
        $fileName .= '_' . date('Y-m-d');
        $exp->setOrientationPaysage();


        // Création du pdf, complétion et envoi au navigateur

        $htmlTitle = '<h1>' . $title . '</h1>';
        $htmlTitle .= ucfirst($structure->getLibelleLong());

        if ($periode) {
            $htmlTitle .= '<br />Paye du mois de ' . lcfirst($periode->getLibelleAnnuel($this->getServiceContext()->getAnnee()));
        }

        $exp->setHeaderTitle($htmlTitle)
            ->setHeaderSubtitle('Année universitaire ' . $this->getServiceContext()->getAnnee())
            ->setMarginBottom(25)
            ->setMarginTop(25 + ($periode ? 5 : 0));

        $drh = $this->getServicePersonnel()->getDrh();

        $variables = compact('typeIntervenant', 'structure', 'periode', 'etatPaiement', 'drh', 'etat');
        $exp->addBodyScript('application/paiement/etat-paiement-pdf.phtml', true, $variables, 1);
        $exp->export($fileName, Pdf::DESTINATION_BROWSER_FORCE_DL);
    }



    protected function etatPaiementCsv(MiseEnPaiementRecherche $recherche)
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $options = [];
        if ($role->getStructure()) {
            $options['composante'] = $role->getStructure();
        }

        $csvModel = new \UnicaenApp\View\Model\CsvModel();
        $csvModel->setHeader([
            'Année',
            'État',
            'Composante',
            'Date de mise en paiement',
            'Période',
            'Statut',
            'N° intervenant',
            'Intervenant',
            'N° INSEE',
            'Centre de coûts ou EOTP (code)',
            'Centre de coûts ou EOTP (libellé)',
            'Domaine fonctionnel (code)',
            'Domaine fonctionnel (libelle)',
            'HETD',
            'HETD (%)',
            'HETD (€)',
            'Rém. FC D714.60',
            'EXERCICE AA',
            'EXERCICE AA (€)',
            'EXERCICE AC',
            'EXERCICE AC (€)',
        ]);

        $data = $this->getServiceMiseEnPaiement()->getEtatPaiementCsv($recherche, $options);
        foreach ($data as $d) {
            $csvModel->addLine($d);
        }
        $csvModel->setFilename('etat-paiement.csv');

        return $csvModel;
    }



    public function misesEnPaiementCsvAction()
    {
        $this->initFilters();
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $recherche = new MiseEnPaiementRecherche;
        $options   = [];
        if ($role->getStructure()) {
            $options['composante'] = $role->getStructure();
        }

        return $this->etatPaiementCsv($recherche, $options);
    }



    public function extractionWinpaieAction()
    {
        $this->initFilters();
        $periode = $this->params()->fromRoute('periode');
        $periode = $this->getServicePeriode()->getRepo()->findOneBy(['code' => $periode]);

        $type = $this->params()->fromRoute('type');
        $type = $this->getServiceTypeIntervenant()->getRepo()->findOneBy(['code' => $type]);

        if (empty($type)) {
            $types = $this->getServiceTypeIntervenant()->getList();

            return compact('types');
        } elseif (empty($periode)) {
            $annee = $this->getServiceContext()->getAnnee();
            $qb = $this->getServicePeriode()->finderByMiseEnPaiement();
            $this->getServiceMiseEnPaiement()->finderByEtat(MiseEnPaiement::MIS_EN_PAIEMENT, $qb);
            $periodes = $this->getServicePeriode()->getList($qb);

            return compact('type', 'periodes', 'annee');
        } else {
            $recherche = new MiseEnPaiementRecherche;
            $recherche->setPeriode($periode);
            $recherche->setTypeIntervenant($type);
            $csvModel = new \UnicaenApp\View\Model\CsvModel();
            $csvModel->setHeader([
                'Insee',
                'Nom',
                'Carte',
                'Code origine',
                'Retenue',
                'Sens',
                'MC',
                'NBU',
                'Montant',
                'Libellé',
            ]);
            $data = $this->getServiceMiseEnPaiement()->getExportWinpaie($recherche);
            foreach ($data as $d) {
                $csvModel->addLine($d);
            }
            $csvModel->setFilename(str_replace( ' ', '_', 'ose-export-winpaie-' . strtolower($recherche->getPeriode()->getLibelleAnnuel($this->getServiceContext()->getAnnee())) . '-' . strtolower($recherche->getTypeIntervenant()->getLibelle()) . '.csv'));

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
            $periode = $this->getServicePeriode()->get($periodeId);
            /* @var $periode \Application\Entity\Db\Periode */

            $dateMiseEnPaiementValue = $form->get('date-mise-en-paiement')->getValue();
            if ($dateMiseEnPaiementValue) {
                $dateMiseEnPaiement = \DateTime::createFromFormat('d/m/Y', $dateMiseEnPaiementValue);
            }else{
                $dateMiseEnPaiement = $periode->getDatePaiement($this->getServiceContext()->getAnnee()); // à défaut
            }

            $intervenants = $this->getServiceIntervenant()->get(explode(',', $intervenants));
            try {
                $this->getServiceMiseEnPaiement()->mettreEnPaiement($structure, $intervenants, $periode, $dateMiseEnPaiement);
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        return compact('form', 'title', 'errors');
    }
}