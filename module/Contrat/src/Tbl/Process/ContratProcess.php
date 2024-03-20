<?php

namespace Contrat\Tbl\Process;


use Application\Entity\Db\Parametre;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Paiement\Service\TauxRemuServiceAwareTrait;
use ServiceAContractualiser;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;

class ContratProcess implements ProcessInterface
{
    use BddServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use TauxRemuServiceAwareTrait;
    private string  $codeEns      = 'ENS';

    private string  $codeMis      = 'MIS';

    private array   $tauxRemuUuid = [];

    protected array $services     = [];

    protected array $tblData      = [];

    //Regle sur les contrats enseignement "contrat_ens" des parametres generaux
    private string $regleCE;

    //Regle sur les contrats mission "contrat_mis" des parametres generaux
    private string $regleCM;

    //Regle sur les avenants "avenant" des parametres generaux
    private string $regleA;



    public function __construct()
    {
        /* new process */
    }



    protected function init()
    {
        $parametres = $this->getServiceParametres();

        $regleA       = $parametres->get('avenant');
        $this->regleA = $regleA;

        $this->services = [];
        $this->tblData  = [];
    }



    public function run(TableauBord $tableauBord, array $params = [])
    {
        $this->init();
        $this->loadAContractualiser($params);
        $this->traitement();
        $this->enregistrement($tableauBord, $params);
    }



    public function debug(array $params = []): array
    {
        $this->init();
        $this->heuresAContractualiserSql($params);
        $this->traitement();

        return $this->services;
    }



    protected function traitement()
    {
        foreach ($this->services as $servicesByUuid) {
            $uuid = $servicesByUuid['UUID'];
            if($this->tauxRemuUuid[$uuid] == false){
                $servicesByUuid['TAUX_REMU'] = null;
                $servicesByUuid['TAUX_REMU_MAJORE'] = null;
            }else {
                //Calcul de la valeur et date du taux
                $tauxRemuId = $servicesByUuid['TAUX_REMU'];
                $tauxRemuMajoreId = $servicesByUuid['TAUX_REMU_MAJORE'];
                $tauxRemuValeur = $this->getServiceTauxRemu()->tauxValeur($tauxRemuId, );
            }


        }
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

        $servicesContrat = $conn->executeQuery($sql);
        $taux_remu_temp  = 0;

        while ($serviceContrat = $servicesContrat->fetchAssociative()) {
            $uuid      = $serviceContrat['UUID'];
            $avenant   = $serviceContrat['AVENANT'];
            $taux_remu = $serviceContrat['TAUX_REMU'];
            if ($this->regleA == Parametre::AVENANT_DESACTIVE && $avenant == 2) {
                $serviceContrat['ACTIF'] = 0;
            }

            $this->services[$uuid][] = $serviceContrat;
            if (!$this->tauxRemuUuid[$uuid]) {
                $taux_remu_temp            = $taux_remu;
                $this->tauxRemuUuid[$uuid] = true;
            } elseif ($taux_remu_temp != $taux_remu) {
                $this->tauxRemuUuid[$uuid] = false;
            }
        }
    }



    protected
    function heuresAContractualiserSql(): string
    {
        return $this->getServiceBdd()->getViewDefinition('V_TBL_CONTRAT');
    }
}