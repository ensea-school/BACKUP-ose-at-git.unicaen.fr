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
        $this->exporter();
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
        foreach ($this->services as $service) {
            $uuid = $service['UUID'];
            if ($this->tauxRemuUuid[$uuid] == false) {
                $service['TAUX_REMU_ID']        = null;
                $service['TAUX_REMU_MAJORE_ID'] = null;
            } else {
                //Calcul de la valeur et date du taux
                $tauxRemuId       = $service['TAUX_REMU_ID'];
                $tauxRemuMajoreId = $service['TAUX_REMU_MAJORE_ID'];
                if ($service['CONTRAT_ID'] != null) {
                    $date                        = $service['DATE_DEBUT'] > $service['DATE_CREATION'] ? $service['DATE_DEBUT'] : $service['DATE_CREATION'];
                    $tauxRemuValeur              = $this->getServiceTauxRemu()->tauxValeur($tauxRemuId, $date);
                    $service['TAUX_REMU_VALEUR'] = $tauxRemuValeur;
                    $service['TAUX_REMU_DATE']   = $date;

                    if ($tauxRemuMajoreId != null) {
                        $tauxRemuMajoreValeur               = $this->getServiceTauxRemu()->tauxValeur($tauxRemuMajoreId, $date);
                        $service['TAUX_REMU_MAJORE_VALEUR'] = $tauxRemuMajoreValeur;
                        $service['TAUX_REMU_MAJORE_DATE']   = $date;
                    }
                }
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

            $this->services[]          = $serviceContrat;
            $this->tauxRemuUuid[$uuid] = false;
            if (!$this->tauxRemuUuid[$uuid]) {
                $taux_remu_temp            = $taux_remu;
                $this->tauxRemuUuid[$uuid] = true;
            } elseif ($taux_remu_temp != $taux_remu && $this->tauxRemuUuid[$uuid]) {
                $this->tauxRemuUuid[$uuid] = false;
            }
        }
    }



    protected
    function heuresAContractualiserSql(): string
    {
        return $this->getServiceBdd()->getViewDefinition('V_TBL_CONTRAT');
    }



    private function exporter()
    {
        if (empty($sap->lignesAPayer)) {
            return;
        }

        foreach ($this->services as $service) {

            $ldata           = [
                "INTERVENANT_ID"          => $service["INTERVENANT_ID"],
                "ANNEE_ID"                => $service["ANNEE_ID"],
                "STRUCTURE_ID"            => $service["STRUCTURE_ID"],
                "EDITE"                   => $service["EDITE"],
                "SIGNE"                   => $service["SIGNE"],
                "ACTIF"                   => $service["ACTIF"],
                "AUTRE"                   => $service["AUTRE"],
                "AUTRE_LIBELLE"           => $service["AUTRE_LIBELLE"],
                "CM"                      => $service["CM"],
                "CONTRAT_ID"              => $service["CONTRAT_ID"],
                "CONTRAT_PARENT_ID"       => $service["CONTRAT_PARENT_ID"],
                "DATE_CREATION"           => $service["DATE_CREATION"],
                "DATE_DEBUT"              => $service["DATE_DEBUT"],
                "DATE_FIN"                => $service["DATE_FIN"],
                "HETD"                    => 0,//$service["HETD"],
                "HEURES"                  => $service["HEURES"],
                "MISSION_ID"              => $service["MISSION_ID"],
                "SERVICE_ID"              => $service["SERVICE_ID"],
                "SERVICE_REFERENTIEL_ID"  => $service["SERVICE_REFERENTIEL_ID"],
                "TAUX_CONGES_PAYES"       => $service["TAUX_CONGES_PAYES"],
                "TAUX_REMU_DATE"          => $service["TAUX_REMU_DATE"],
                "TAUX_REMU_ID"            => $service["TAUX_REMU_ID"],
                "TAUX_REMU_MAJORE_DATE"   => $service["TAUX_REMU_MAJORE_DATE"],
                "TAUX_REMU_MAJORE_ID"     => $service["TAUX_REMU_MAJORE_ID"],
                "TAUX_REMU_MAJORE_VALEUR" => $service["TAUX_REMU_MAJORE_VALEUR"],
                "TAUX_REMU_VALEUR"        => $service["TAUX_REMU_VALEUR"],
            ];
            $this->tblData[] = $ldata;
        }
    }
}