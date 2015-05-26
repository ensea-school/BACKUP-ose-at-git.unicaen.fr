<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;
use UnicaenApp\Exporter\Pdf;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\Paiement\MiseEnPaiementRecherche;
use Application\Entity\Db\Privilege;

/**
 * @method \Application\Controller\Plugin\Context     context()
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PaiementController extends AbstractActionController
{
    use \Application\Service\Traits\ContextAwareTrait,
        \Application\Service\Traits\IntervenantAwareTrait,
        \Application\Service\Traits\ServiceAwareTrait,
        \Application\Service\Traits\StructureAwareTrait,
        \Application\Service\Traits\PersonnelAwareTrait,
        \Application\Service\Traits\PeriodeAwareTrait,
        \Application\Service\Traits\MiseEnPaiementAwareTrait,
        \Application\Service\Traits\ServiceAPayerAwareTrait
    ;

    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init(
            'Application\Entity\Db\MiseEnPaiement',
            $this->getServiceContext()->getDateObservation()
        );
    }

    public function indexAction()
    {
        return [];
    }

    public function demandeMiseEnPaiementAction()
    {
        $this->initFilters();
        $intervenant        = $this->context()->mandatory()->intervenantFromRoute(); /* @var $intervenant \Application\Entity\Db\Intervenant */
        $saved = false;
        if ($this->getRequest()->isPost()) {
            $changements = $this->params()->fromPost('changements', '{}');
            $changements = Json::decode($changements, Json::TYPE_ARRAY);
            $this->getServiceMiseEnPaiement()->saveChangements($changements);
            $saved = true;
        }
        $servicesAPayer     = $this->getServiceServiceAPayer()->getListByIntervenant($intervenant);
        return compact('intervenant', 'servicesAPayer', 'saved');
    }

    public function etatPaiementAction()
    {
        $this->initFilters();

        $etat = $this->params()->fromRoute('etat');

        $rechercheForm = $this->getFormMiseEnPaiementRecherche();
        $recherche = new MiseEnPaiementRecherche;
        $recherche->setEtat( $etat ); // données à mettre en paiement uniquement
        $rechercheForm->bind($recherche);

        $qb = $this->getServiceStructure()->finderByMiseEnPaiement();
        $this->getServiceStructure()->finderByRole( $this->getServiceContext()->getSelectedIdentityRole(), $qb );
        $this->getServiceMiseEnPaiement()->finderByEtat($etat, $qb);
        $structures = $this->getServiceStructure()->getList($qb);
        $rechercheForm->populateStructures( $structures );

        if (count($structures) == 1){
            $structure = current($structures);
            $rechercheForm->get('structure')->setValue( $structure->getId() );
            $noData = false;
        }elseif(count($structures) == 0){
            $noData = true;
            $structure = $this->context()->structureFromPost(); /* @var $structure \Application\Entity\Db\Structure */
        }else{
            $noData = false;
            $structure = $this->context()->structureFromPost(); /* @var $structure \Application\Entity\Db\Structure */
        }

        $periode = null;
        if ($structure){
            $qb = $this->getServicePeriode()->finderByMiseEnPaiement($structure);
            $this->getServiceMiseEnPaiement()->finderByEtat($etat, $qb);
            $periodes = $this->getServicePeriode()->getList($qb);
            $rechercheForm->populatePeriodes( $periodes );

            if (count($periodes) == 1){
                $periode = current($periodes);
                $rechercheForm->get('periode')->setValue( $periode->getId() );
            }else{
                $periode = $this->context()->periodeFromPost(); /* @var $periode \Application\Entity\Db\Periode */
            }

            $qb = $this->getServiceIntervenant()->finderByMiseEnPaiement( $structure, $periode );
            $this->getServiceMiseEnPaiement()->finderByEtat($etat, $qb);
            $rechercheForm->populateIntervenants( $this->getServiceIntervenant()->getList($qb) );
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $rechercheForm->setData($request->getPost());
            $rechercheForm->isValid();
        }

        $etatPaiement = null;
        if ( $recherche->getIntervenants()->count() > 0 ){
            $etatPaiement = $this->getServiceMiseEnPaiement()->getEtatPaiement( $recherche );
        }

        if ( $this->params()->fromPost('exporter-pdf') !== null && $this->isAllowed('privilege/'.Privilege::MISE_EN_PAIEMENT_EXPORT_PDF) ){
            $this->etatPaiementPdf( $etat, $structure, $periode, $etatPaiement );
        }elseif ( $this->params()->fromPost('exporter-csv-etat') !== null && $this->isAllowed('privilege/'.Privilege::MISE_EN_PAIEMENT_EXPORT_CSV) ){
            return $this->etatPaiementCsv( $recherche );
        }else{
            return compact( 'rechercheForm', 'etatPaiement', 'etat', 'noData' );
        }
    }

    protected function etatPaiementPdf( $etat, $structure, $periode, $etatPaiement )
    {
        $exp = new Pdf($this->getServiceLocator()->get('view_manager')->getRenderer());

        switch( $etat  ){
            case MiseEnPaiement::A_METTRE_EN_PAIEMENT   : 
                $fileName = 'demande_mise_en_paiement';
                $title = 'Demandes de mises en paiement';
                //$exp->setWatermark("Demandes");
            break;
            default                                     :
                $fileName = 'etat_paiement';
                $title = 'État de paiement';
        }

        if ($structure) $fileName .= '_'.strtolower( $structure->getSourceCode() );
        if ($periode)   $fileName .= '_'.strtolower( $periode->getLibelleCourt() );
        $fileName .= '_'.date('Y-m-d');
        $exp->setOrientationPaysage();




        // Création du pdf, complétion et envoi au navigateur

        $htmlTitle = '<h1>'.$title.'</h1>';
        $htmlTitle .= ucfirst($structure->getLibelleLong());

        if ($periode){
            $htmlTitle .= '<br />Paye du mois de '.lcfirst($periode->getLibelleLong());
        }

        $exp    ->setHeaderTitle($htmlTitle)
                ->setHeaderSubtitle('Année universitaire '.$this->getServiceContext()->getAnnee())
                ->setMarginBottom(25)
                ->setMarginTop(25 + ($periode ? 5 : 0));

        $drh = $this->getServicePersonnel()->getDrh();

        $variables = compact( 'structure', 'periode', 'etatPaiement', 'drh', 'etat' );
        $exp->addBodyScript('application/paiement/etat-paiement-pdf.phtml', true, $variables, 1);
        $exp->export($fileName, Pdf::DESTINATION_BROWSER_FORCE_DL);
    }

    protected function etatPaiementCsv( MiseEnPaiementRecherche $recherche )
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $options = [];
        if ($role instanceof \Application\Interfaces\StructureAwareInterface && $role->getStructure()){
            $options['composante'] = $role->getStructure();
        }

        $csvModel = new \UnicaenApp\View\Model\CsvModel();
        $csvModel->setHeader([
            'Année',
            'État',
            'Composante',
            'Date de mise en paiement',
            'Période',
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
        foreach( $data as $d ){
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
        $options = [];
        if ($role instanceof \Application\Interfaces\StructureAwareInterface && $role->getStructure()){
            $options['composante'] = $role->getStructure();
        }
        return $this->etatPaiementCsv( $recherche, $options );
    }

    public function extractionWinpaieAction()
    {
        $this->initFilters();
        $periode = $this->params()->fromRoute('periode');
        $periode = $this->getServicePeriode()->getRepo()->findOneBy(['code' => $periode]);

        if (empty($periode)){
            $qb = $this->getServicePeriode()->finderByMiseEnPaiement();
            $this->getServiceMiseEnPaiement()->finderByEtat(MiseEnPaiement::MIS_EN_PAIEMENT, $qb);
            $periodes = $this->getServicePeriode()->getList( $qb );
            return compact('periodes');
        }else{
            $recherche = new MiseEnPaiementRecherche;
            $recherche->setPeriode($periode);
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
                'Libellé'
            ]);
            $data = $this->getServiceMiseEnPaiement()->getExportWinpaie($recherche);
            foreach( $data as $d ){
                $csvModel->addLine($d);
            }
            $csvModel->setFilename('ose-export-winpaie-'.strtolower($recherche->getPeriode()).'.csv');
            return $csvModel;
        }
    }

    public function miseEnPaiementAction()
    {
        $this->initFilters();
        $title = 'Mise en paiement';
        $structure    = $this->context()->mandatory()->structureFromRoute();
        $intervenants = $this->params('intervenants');

        $form = $this->getFormMiseEnPaiement();
        $errors = [];
        $request = $this->getRequest();
        if ($request->isPost() && $this->isAllowed('privilege/'.Privilege::MISE_EN_PAIEMENT_MISE_EN_PAIEMENT)) {
            $form->setData($request->getPost());
            $form->isValid();

            $periode            = $form->get('periode')->getValue();
            //$dateMiseEnPaiement = $form->get('date-mise-en-paiement')->getValue();

            $periode = $this->getServicePeriode()->get($periode); /* @var $periode \Application\Entity\Db\Periode */
            $dateMiseEnPaiement = $periode->getDatePaiement($this->getServiceContext()->getAnnee()); // date forcée car plus de saisie possible!
            //$dateMiseEnPaiement = \DateTime::createFromFormat('d/m/Y', $dateMiseEnPaiement);

            $intervenants = $this->getServiceIntervenant()->get( explode(',',$intervenants) );
            try{
                $this->getServiceMiseEnPaiement()->mettreEnPaiement($structure, $intervenants, $periode, $dateMiseEnPaiement);
            }catch(\Exception $e){
                $errors[] = $e->getMessage();
            }
        }

        return compact('form','title', 'errors');
    }

    /**
     * @return \Application\Form\Paiement\MiseEnPaiementForm
     */
    protected function getFormMiseEnPaiement()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('PaiementMiseEnPaiementForm');
    }

    /**
     * @return \Application\Form\Paiement\MiseEnPaiementRechercheForm
     */
    protected function getFormMiseEnPaiementRecherche()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('PaiementMiseEnPaiementRechercheForm');
    }
}