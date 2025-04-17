<?php

namespace Dossier\Tbl\Process;

use Dossier\Tbl\Process\Sub\CalculateurCompletude;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;

/**
 * Description of PaiementProcess
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DossierProcess implements ProcessInterface
{
    use BddServiceAwareTrait;
    use BddAwareTrait;

    protected CalculateurCompletude $calculateurCompletude;

    protected array $dossiers = [];
    protected array $tblData  = [];



    public function __construct()
    {
        $this->calculateurCompletude = new CalculateurCompletude();
    }



    protected function init(): void
    {

    }



    public function run(TableauBord $tableauBord, array $params = []): void
    {
        $this->loadDossiers($params);
        $this->traitementDossiers();


    }



    protected function loadDossiers(array $params): void
    {
        $connexion  = $this->getServiceBdd()->getEntityManager()->getConnection();
        $params     = ['intervenant_id' => '1086999'];
        $sqlDossier = 'SELECT * FROM ('
            . $this->getServiceBdd()->injectKey($this->dossierSql(), $params)
            . ') t '
            . $this->getServiceBdd()->makeWhere($params);


        $dossiers = $this->getBdd()->selectEach($sqlDossier);

        while ($dossier = $dossiers->next()) {
            $this->dossiers[] = $dossier;

        }

        unset($dossiers);
    }



    /**
     * Méthode qui va venir peupler le tblData pour alimenter à terme le tblDossier
     *
     * @return void
     */
    protected function traitementDossiers(): void
    {
        //Ici on va traiter les dossiers pour calculer les différentes complétude
        $i = 0;
        foreach ($this->dossiers as $key => $dossier) {
            $dossierTbl                   = [];
            $dossierTbl['ANNEE_ID']       = $dossier['ANNEE_ID'];
            $dossierTbl['INTERVENANT_ID'] = $dossier['INTERVENANT_ID'];
            $dossierTbl['ACTIF']          = $dossier['DOSSIER'];
            $dossierTbl['VALIDATION_ID']  = $dossier['VALIDATION_ID'];
            $this->calculateurCompletude->calculer($dossier, $dossierTbl);
            $this->tblData[$i] = $dossierTbl;
            $i++;
        }
        dump($this->tblData);
    }



    protected function dossierSql(): string
    {
        return $this->getServiceBdd()->getViewDefinition('V_TBL_DOSSIER');
    }



    public function getCalculateurCompletude(): CalculateurCompletude
    {
        return $this->calculateurCompletude;
    }






}
