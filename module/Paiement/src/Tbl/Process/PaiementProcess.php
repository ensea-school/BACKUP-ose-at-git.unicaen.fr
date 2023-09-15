<?php

namespace Paiement\Tbl\Process;


use Application\Service\Traits\ParametresServiceAwareTrait;
use Paiement\Service\TauxRemuServiceAwareTrait;
use Paiement\Tbl\Process\Sub\Arrondisseur;
use Paiement\Tbl\Process\Sub\Consolidateur;
use Paiement\Tbl\Process\Sub\Exporteur;
use Paiement\Tbl\Process\Sub\LigneAPayer;
use Paiement\Tbl\Process\Sub\MiseEnPaiement;
use Paiement\Tbl\Process\Sub\Rapprocheur;
use Paiement\Tbl\Process\Sub\Repartiteur;
use Paiement\Tbl\Process\Sub\ServiceAPayer;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;

/**
 * Description of PaiementProcess
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class PaiementProcess implements ProcessInterface
{
    use BddServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use TauxRemuServiceAwareTrait;

    /** @var array|ServiceAPayer[] */
    protected array $services = [];
    protected array $tblData = [];

    protected Repartiteur $repartiteur;
    protected Rapprocheur $rapprocheur;
    protected Consolidateur $consolidateur;
    protected Arrondisseur $arrondisseur;
    protected Exporteur $exporteur;



    public function __construct()
    {
        $this->repartiteur = new repartiteur();
        $this->rapprocheur = new Rapprocheur();
        $this->consolidateur = new Consolidateur();
        $this->arrondisseur = new Arrondisseur();
        $this->exporteur = new Exporteur();
    }



    protected function init()
    {
        $regleRLM = $this->getServiceParametres()->get('regle_repartition_annee_civile');
        $this->rapprocheur->setRegle($regleRLM);

        $reglePaiementAnneeCiv = $this->getServiceParametres()->get('regle_paiement_annee_civile');
        $this->repartiteur->setReglePaiementAnneeCiv($reglePaiementAnneeCiv);

        $pourcS1PourAnneeCivile = (float)$this->getServiceParametres()->get('pourc_s1_pour_annee_civile');
        $this->repartiteur->setPourcS1PourAnneeCivile($pourcS1PourAnneeCivile);

        $this->services = [];
        $this->tblData = [];
    }



    public function run(TableauBord $tableauBord, array $params = [])
    {
        $this->init();

        $this->loadAPayer($params);

        $this->traitement();
    }



    public function getData(array $params = [])
    {
        $conn = $this->getServiceBdd()->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM ('
            . $this->getServiceBdd()->injectKey($this->heuresAPayerSql(), $params)
            . ') t '
            . $this->getServiceBdd()->makeWhere($params);

        $aPayerStmt = $conn->executeQuery($sql);
        $res = [];
        while ($lap = $aPayerStmt->fetchAssociative()) {
            $res[] = $lap;

        }

        return $res;
    }



    public function testData(array $lapData): array
    {
        $this->init();

        foreach ($lapData as $lap) {
            $this->loadLigneAPayer($lap);
        }

        $this->traitement();

        return $this->tblData;
    }



    protected function traitement()
    {
        foreach ($this->services as $serviceAPayer) {
            $this->arrondisseur->arrondir($serviceAPayer);
            $this->consolidateur->consolider($serviceAPayer);
            $this->rapprocheur->rapprocher($serviceAPayer);
            $this->exporteur->exporter($serviceAPayer, $this->tblData);
        }
    }



    protected function loadAPayer(array $params)
    {
        $conn = $this->getServiceBdd()->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM ('
            . $this->getServiceBdd()->injectKey($this->heuresAPayerSql(), $params)
            . ') t '
            . $this->getServiceBdd()->makeWhere($params);

        $aPayerStmt = $conn->executeQuery($sql);
        while ($lap = $aPayerStmt->fetchAssociative()) {
            $this->loadLigneAPayer($lap);
        }
    }



    protected function loadLigneAPayer(array $data)
    {
        $key = $data['KEY'];
        $tauxRemu = (int)$data['TAUX_REMU_ID'];
        $horaireDebut = (string)$data['HORAIRE_DEBUT'];
        $miseEnPaiementId = (int)$data['MISE_EN_PAIEMENT_ID'];

        if (!array_key_exists($key, $this->services)) {
            $sap = new ServiceAPayer();
            $sap->fromBdd($data);
            $this->services[$sap->key] = $sap;
        }

        $lap = new LigneAPayer();
        $lap->tauxValeur = $this->getServiceTauxRemu()->tauxValeur($tauxRemu, $horaireDebut);
        $lap->pourcAA = $this->repartiteur->fromBdd($data);
        $lap->fromBdd($data);

        $this->services[$key]->lignesAPayer[$lap->id] = $lap;

        if ($miseEnPaiementId > 0) {
            $mep = new MiseEnPaiement();
            $mep->fromBdd($data);
            $this->services[$key]->misesEnPaiement[$mep->id] = $mep;
        }
    }



    protected function heuresAPayerSql(): string
    {
        return $this->getServiceBdd()->getViewDefinition('V_TBL_PAIEMENT');

//        return file_get_contents(getcwd() . '/module/Paiement/sql/paiement_process_heures_a_payer.sql');
    }
}