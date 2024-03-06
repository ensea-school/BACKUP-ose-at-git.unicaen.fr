<?php

namespace Contrat\Tbl\Process;


use Application\Service\Traits\ParametresServiceAwareTrait;
use ServiceAContractualiser;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;

class ContratProcess implements ProcessInterface
{
    use BddServiceAwareTrait;
    use ParametresServiceAwareTrait;

    /** @var array|ServiceAContractualiser[] */
    protected array $services = [];
    protected array $tblData = [];


    public function __construct()
    {
        /* new process */
    }



    protected function init()
    {
        $parametres = $this->getServiceParametres();

        $regleCE = $parametres->get('contrat_ens');
        $regleCM = $parametres->get('contrat_mis');
        $regleA = $parametres->get('avenant');
//        $this->process->setRegle($regleRLM);
//        ou
//        $this->variable = $regleRLM

        $this->services = [];
        $this->tblData = [];
    }



    public function run(TableauBord $tableauBord, array $params = [])
    {
        $this->init();
        $this->loadAContractualiser($params);
        $this->traitement();
        $this->enregistrement($tableauBord, $params);
    }



    public function getData(array $params = []): array
    {
        $conn = $this->getServiceBdd()->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM ('
            . $this->getServiceBdd()->injectKey($this->heuresAContractualiserSql(), $params)
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
            $this->loadLigneAContractualiser($lap);
        }

        $this->traitement();

        return $this->tblData;
    }



    public function debug(array $params = []): array
    {
        $this->init();
        $this->heuresAContractualiserSql($params);
        $this->traitement(false, false);

        return $this->services;
    }



    protected function traitement(bool $export=true, bool $consolidation=true)
    {
        /*traitement des données*/
    }



    protected function enregistrement(TableauBord $tableauBord, array $params)
    {
        // Enregistrement en BDD
        $key = $tableauBord->getOption('key');

        $table = \OseAdmin::instance()->getBdd()->getTable('TBL_CONTRAT');

        // on force la DDL pour éviter de faire des requêtes en plus
        $table->setDdl(['columns' => array_fill_keys($tableauBord->getOption('cols'), [])]);
        // on merge dans la table
        $table->merge($this->tblData, $key, ['where' => $params]);
        // on vide pour limiter la conso de RAM
        $this->tblData = [];
    }



    protected function loadAContractualiser(array $params)
    {
        $conn = $this->getServiceBdd()->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM ('
            . $this->getServiceBdd()->injectKey($this->heuresAContractualiserSql(), $params)
            . ') t '
            . $this->getServiceBdd()->makeWhere($params);

        $aPayerStmt = $conn->executeQuery($sql);
        while ($lap = $aPayerStmt->fetchAssociative()) {
            $this->loadLigneAContractualiser($lap);
        }
    }



    protected function loadLigneAContractualiser(array $data)
    {

    }



    protected function heuresAContractualiserSql(): string
    {
        return $this->getServiceBdd()->getViewDefinition('V_TBL_CONTRAT');
    }
}