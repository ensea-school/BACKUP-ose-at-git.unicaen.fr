<?php

namespace Application\Controller;

use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;
use UnicaenApp\Exporter\Pdf;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\Paiement\MiseEnPaiementRecherche;

/**
 * @method \Application\Controller\Plugin\Context     context()
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PaiementController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')
                ->disableForEntity('Application\Entity\Db\ElementPedagogique')
                ->disableForEntity('Application\Entity\Db\Etape')
                ->disableForEntity('Application\Entity\Db\Etablissement')
                ->disableForEntity('Application\Entity\Db\FonctionReferentiel');
    }

    public function indexAction()
    {
        return [];
    }

    public function demandeMiseEnPaiementAction()
    {
        $this->initFilters();
        $intervenant        = $this->context()->mandatory()->intervenantFromRoute(); /* @var $intervenant \Application\Entity\Db\Intervenant */
        $annee              = $this->context()->getGlobalContext()->getAnnee();
        $saved = false;
        if ($this->getRequest()->isPost()) {
            $changements = $this->params()->fromPost('changements', '{}');
            $changements = Json::decode($changements, Json::TYPE_ARRAY);
            //var_dump($changements);
            $this->getServiceMiseEnPaiement()->saveChangements($changements);
            $saved = true;
        }
        $servicesAPayer     = $this->getServiceServiceAPayer()->getListByIntervenant($intervenant, $annee);
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
        $this->getServiceStructure()->finderByRole( $this->getContextProvider()->getSelectedIdentityRole(), $qb );
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

        if ( $this->params()->fromPost('exporter-pdf') !== null ){
            $this->etatPaiementPdf( $etat, $structure, $periode, $etatPaiement );
        }elseif ( $this->params()->fromPost('exporter-csv-winpaie') !== null ){
            return $this->exportCsvWinpaie( $recherche );
        }elseif ( $this->params()->fromPost('exporter-csv-etat') !== null ){
            return $this->etatPaiementCsv( $recherche );
        }else{
            return compact( 'rechercheForm', 'etatPaiement', 'etat', 'noData' );
        }
    }

    public function misesEnPaiementCsvAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();

        $recherche = new MiseEnPaiementRecherche;
        $options = [];
        if ($role instanceof \Application\Interfaces\StructureAwareInterface && $role->getStructure()){
            $options['composante'] = $role->getStructure();
        }
        return $this->etatPaiementCsv( $recherche, $options );
    }

    protected function etatPaiementPdf( $etat, $structure, $periode, $etatPaiement )
    {
        $exp = new Pdf($this->getServiceLocator()->get('view_manager')->getRenderer());

        switch( $etat  ){
            case MiseEnPaiement::A_METTRE_EN_PAIEMENT   : 
                $fileName = 'demande_mise_en_paiement';
                $title = 'Demande de mise en paiement';
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
        $exp    ->setHeaderSubtitle('Année universitaire '.$this->getContextProvider()->getGlobalContext()->getAnnee())
                ->setMarginBottom(25)
                ->setMarginTop(25);

        $drh = $this->getServicePersonnel()->getDrh();

        $variables = compact( 'structure', 'periode', 'title', 'etatPaiement', 'drh', 'etat' );
        $exp->addBodyScript('application/paiement/etat-paiement-pdf.phtml', true, $variables, 1);
        $exp->export($fileName, Pdf::DESTINATION_BROWSER_FORCE_DL);
    }

    protected function etatPaiementCsv( MiseEnPaiementRecherche $recherche )
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();

        $options = [];
        if ($role instanceof \Application\Interfaces\StructureAwareInterface && $role->getStructure()){
            $options['composante'] = $role->getStructure();
        }

        $csvModel = new \UnicaenApp\View\Model\CsvModel();
        $csvModel->setHeader([
            'Année',
            'État',
            'Date de mise en paiement',
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

    protected function exportCsvWinpaie( MiseEnPaiementRecherche $recherche )
    {
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

    public function miseEnPaiementAction()
    {
        $title = 'Mise en paiement';
        $structure    = $this->context()->mandatory()->structureFromRoute();
        $intervenants = $this->params('intervenants');

        $form = $this->getFormMiseEnPaiement();
        $errors = [];
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->isValid();

            $periode            = $form->get('periode')->getValue();
            //$dateMiseEnPaiement = $form->get('date-mise-en-paiement')->getValue();

            $periode = $this->getServicePeriode()->get($periode); /* @var $periode \Application\Entity\Db\Periode */
            $dateMiseEnPaiement = $periode->getDatePaiement($this->context()->getGlobalContext()->getAnnee()); // date forcée car plus de saisie possible!
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

    /**
     * @return \Application\Service\Intervenant
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('applicationIntervenant');
    }

    /**
     * @return \Application\Service\Personnel
     */
    protected function getServicePersonnel()
    {
        return $this->getServiceLocator()->get('applicationPersonnel');
    }

    /**
     * @return \Application\Service\Periode
     */
    protected function getServicePeriode()
    {
        return $this->getServiceLocator()->get('applicationPeriode');
    }

    /**
     * @return \Application\Service\Structure
     */
    protected function getServiceStructure()
    {
        return $this->getServiceLocator()->get('applicationStructure');
    }

    /**
     * @return \Application\Service\MiseEnPaiement
     */
    protected function getServiceMiseEnPaiement()
    {
        return $this->getServiceLocator()->get('applicationMiseEnPaiement');
    }

    /**
     * @return \Application\Service\ServiceAPayer
     */
    protected function getServiceServiceAPayer()
    {
        return $this->getServiceLocator()->get('applicationServiceAPayer');
    }
}