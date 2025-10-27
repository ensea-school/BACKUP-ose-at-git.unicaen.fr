<?php

namespace Dossier\Tbl\Process;

use Dossier\Tbl\Process\Sub\CalculateurCompletude;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;

/**
 * Description of DossierProcess
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
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



    public function run(TableauBord $tableauBord, array $params = []): void
    {
        $this->loadDossiers($params);
        $this->traitementDossiers();
        $this->enregistrement($tableauBord, $params);

    }



    protected function loadDossiers(array $params): void
    {
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
        foreach ($this->dossiers as $key => $dossier) {
            mpg_lower($dossier);
            $dossierTbl                                 = [];
            $dossierTbl['annee_id']                     = $dossier['annee_id'];
            $dossierTbl['dossier_id']                   = $dossier['dossier_id'];
            $dossierTbl['intervenant_id']               = $dossier['intervenant_id'];
            $dossierTbl['actif']                        = $dossier['dossier'];
            $dossierTbl['validation_id']                = $dossier['validation_id'];
            $dossierTbl['validation_complementaire_id'] = $dossier['validation_complementaire_id'];
            $this->calculateurCompletude->calculer($dossier, $dossierTbl);
            $this->tblData[$dossier['intervenant_id']] = $dossierTbl;
        }
        mpg_upper($this->tblData);
    }



    protected function enregistrement(TableauBord $tableauBord, array $params): void
    {
        try {
            $key   = $tableauBord->getOption('key');
            $table = $this->getBdd()->getTable('TBL_DOSSIER');

            $options = [
                'where'       => $params,
                'transaction' => !isset($params['INTERVENANT_ID']),
            ];

            $table->merge($this->tblData, $key, $options);

            $this->tblData = [];
        } catch (\Exception $e) {
            error_log("Erreur attrapée : " . $e->getMessage());
        }

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
