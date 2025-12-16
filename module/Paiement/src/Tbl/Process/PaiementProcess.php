<?php

namespace Paiement\Tbl\Process;


use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Paiement\Service\TauxRemuServiceAwareTrait;
use Paiement\Tbl\Process\Sub\Consolidateur;
use Paiement\Tbl\Process\Sub\Exporteur;
use Paiement\Tbl\Process\Sub\LigneAPayer;
use Paiement\Tbl\Process\Sub\MiseEnPaiement;
use Paiement\Tbl\Process\Sub\Rapprocheur;
use Paiement\Tbl\Process\Sub\Repartiteur;
use Paiement\Tbl\Process\Sub\ServiceAPayer;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenTbl\Event;
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
    use BddAwareTrait;
    use AnneeServiceAwareTrait;

    /** @var array|ServiceAPayer[] */
    protected array $services = [];
    protected array $tblData  = [];

    protected Repartiteur   $repartiteur;
    protected Rapprocheur   $rapprocheur;
    protected Consolidateur $consolidateur;
    protected Exporteur     $exporteur;
    protected TableauBord   $tableauBord;



    public function __construct()
    {
        $this->repartiteur   = new repartiteur();
        $this->rapprocheur   = new Rapprocheur();
        $this->consolidateur = new Consolidateur();
        $this->exporteur     = new Exporteur();
    }



    protected function init(): void
    {
        $parametres = $this->getServiceParametres();

        $regleRLM = $parametres->get('regle_repartition_annee_civile');
        $this->rapprocheur->setRegle($regleRLM);

        $reglePaiementAnneeCiv = $parametres->get('regle_paiement_annee_civile');
        $this->repartiteur->setReglePaiementAnneeCiv($reglePaiementAnneeCiv);

        $pourcS1PourAnneeCivile = (float)$parametres->get('pourc_s1_pour_annee_civile');
        $this->repartiteur->setPourcS1PourAnneeCivile($pourcS1PourAnneeCivile);

        $pourcAAReferentiel = (float)$parametres->get('pourc_aa_referentiel');
        $this->repartiteur->setPourAAReferentiel($pourcAAReferentiel);

        $this->services = [];
        $this->tblData  = [];
    }



    public function run(TableauBord $tableauBord, array $params = []): void
    {
        $this->tableauBord = $tableauBord;

        if (empty($params)) {
            $annees = $this->getServiceAnnee()->getActives();
            foreach ($annees as $annee) {
                $this->run($tableauBord, ['ANNEE_ID' => $annee->getId()]);
            }
        } else {
            $this->init();
            $tableauBord->onAction(Event::GET);
            $this->loadAPayer($params);
            $tableauBord->onAction(Event::PROCESS, 0, count($this->tblData));
            $this->traitement();
            $tableauBord->onAction(Event::SET, 0, count($this->tblData));
            $this->enregistrement($tableauBord, $params);
        }
    }



    public function getData(array $params = []): array
    {
        $conn = $this->getServiceBdd()->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM ('
            . $this->getServiceBdd()->injectKey($this->heuresAPayerSql(), $params)
            . ') t '
            . $this->getServiceBdd()->makeWhere($params);

        $aPayerStmt = $conn->executeQuery($sql);
        $res        = [];
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



    public function debug(array $params = []): array
    {
        $this->init();
        $this->loadAPayer($params);
        $this->traitement(false, false);

        return $this->services;
    }



    protected function traitement(bool $export = true, bool $consolidation = true)
    {
        $index      = 0;
        $count      = count($this->services);

        foreach ($this->services as $sid => $serviceAPayer) {
            $index++;
            $this->tableauBord->onAction(Event::PROGRESS, $index, $count);

            $this->repartiteur->repartir($serviceAPayer);
            $this->rapprocheur->rapprocher($serviceAPayer);
            if ($consolidation) {
                $this->consolidateur->consolider($serviceAPayer);
            }
            if ($export) {
                $this->exporteur->exporter($serviceAPayer, $this->tblData);
                unset($this->services[$sid]);// libération de mémoire
            }
        }
    }



    protected function enregistrement(TableauBord $tableauBord, array $params)
    {
        // Enregistrement en BDD
        $key = $tableauBord->getOption('key');

        $table = $this->getBdd()->getTable('TBL_PAIEMENT');

        $options = [
            'where'       => $params,
            'transaction' => !isset($params['INTERVENANT_ID']),
        ];

        // on merge dans la table
        $table->merge($this->tblData, $key, $options);
        // on vide pour limiter la conso de RAM
        $this->tblData = [];
    }



    protected function loadAPayer(array $params)
    {
        $conn = $this->getServiceBdd()->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM ('
            . $this->getServiceBdd()->injectKey($this->heuresAPayerSql(), $params)
            . ') t '
            . $this->getServiceBdd()->makeWhere($params);

        $aPayerStmt = $conn->executeQuery($sql);
        $index = 0;
        while ($lap = $aPayerStmt->fetchAssociative()) {
            $index++;
            $this->tableauBord->onAction(Event::PROGRESS, $index, 0);
            $this->loadLigneAPayer($lap);
        }

        foreach($this->services as $sk => $serviceAPayer) {
            $serviceAPayer->heures = 0;
            foreach( $serviceAPayer->lignesAPayer as $lap ){
                $serviceAPayer->heures += $lap->heuresAA + $lap->heuresAC;
            }
        }
    }



    protected function loadLigneAPayer(array $data)
    {
        $key    = $data['KEY'];
        $lapKey = (int)$data['A_PAYER_ID'];
        $mepKey = (int)$data['MISE_EN_PAIEMENT_ID'];

        if (!array_key_exists($key, $this->services)) {
            $sap = new ServiceAPayer();
            $sap->fromBdd($data);
            $this->services[$key] = $sap;
        }

        $lap             = new LigneAPayer();
        $tauxRemu        = (int)$data['TAUX_REMU_ID'];
        $horaireDebut    = (string)$data['HORAIRE_DEBUT'];
        $lap->tauxValeur = $this->getServiceTauxRemu()->tauxValeur($tauxRemu, $horaireDebut);
        $lap->pourcAA    = $this->repartiteur->fromBdd($data);
        $lap->fromBdd($data);
        if (!array_key_exists($lapKey, $this->services[$key]->lignesAPayer)) {
            $this->services[$key]->lignesAPayer[$lapKey] = $lap;
        }

        if ($mepKey > 0 && !array_key_exists($mepKey, $this->services[$key]->misesEnPaiement)) {
            $mep = new MiseEnPaiement();
            $mep->fromBdd($data);
            $this->services[$key]->misesEnPaiement[$mepKey] = $mep;
        }
    }



    protected function heuresAPayerSql(): string
    {
        return $this->getServiceBdd()->getViewDefinition('V_TBL_PAIEMENT');
    }
}