<?php

namespace Administration\Migration;

use Formule\Service\FormuleServiceAwareTrait;
use Unicaen\BddAdmin\Migration\MigrationAction;

class v24Formules extends MigrationAction
{
    use FormuleServiceAwareTrait;

    public function description(): string
    {
        return "Migration de l'infrastructure des formules";
    }



    public function utile(): bool
    {
        return !$this->manager()->hasColumn('FORMULE_TEST_INTERVENANT', 'SERVICE_DU');
    }



    public function before()
    {
        $bdd = $this->getBdd();

        $vhColRenames = [
            'INTERVENANT_TEST_ID'        => 'FORMULE_INTERVENANT_TEST_ID',
            'A_SERVICE_FI'               => 'HEURES_ATTENDUES_SERVICE_FI',
            'A_SERVICE_FA'               => 'HEURES_ATTENDUES_SERVICE_FA',
            'A_SERVICE_FC'               => 'HEURES_ATTENDUES_SERVICE_FC',
            'A_SERVICE_REFERENTIEL'      => 'HEURES_ATTENDUES_SERVICE_REFERENTIEL',
            'A_HEURES_COMPL_FI'          => 'HEURES_ATTENDUES_COMPL_FI',
            'A_HEURES_COMPL_FA'          => 'HEURES_ATTENDUES_COMPL_FA',
            'A_HEURES_COMPL_FC'          => 'HEURES_ATTENDUES_COMPL_FC',
            'A_HEURES_COMPL_REFERENTIEL' => 'HEURES_ATTENDUES_COMPL_REFERENTIEL',
            'A_HEURES_COMPL_FC_MAJOREES' => 'HEURES_ATTENDUES_PRIMES',
            'C_SERVICE_FI'               => 'HEURES_SERVICE_FI',
            'C_SERVICE_FA'               => 'HEURES_SERVICE_FA',
            'C_SERVICE_FC'               => 'HEURES_SERVICE_FC',
            'C_SERVICE_REFERENTIEL'      => 'HEURES_SERVICE_REFERENTIEL',
            'C_HEURES_COMPL_FI'          => 'HEURES_COMPL_FI',
            'C_HEURES_COMPL_FA'          => 'HEURES_COMPL_FA',
            'C_HEURES_COMPL_FC'          => 'HEURES_COMPL_FC',
            'C_HEURES_COMPL_REFERENTIEL' => 'HEURES_COMPL_REFERENTIEL',
            'C_HEURES_COMPL_FC_MAJOREES' => 'HEURES_PRIMES',
        ];

        $bdd->exec('ALTER TABLE FORMULE_TEST_INTERVENANT RENAME COLUMN C_SERVICE_DU TO SERVICE_DU');
        foreach ($vhColRenames as $old => $new) {
            $bdd->exec("ALTER TABLE FORMULE_TEST_VOLUME_HORAIRE RENAME COLUMN $old TO $new");
        }

        $bdd->exec('ALTER TABLE FORMULE ADD (CODE VARCHAR2(50))');
        $bdd->exec('UPDATE formule SET code = package_name');
    }



    public function after()
    {
        $sTbl = $this->getServiceFormule()->getServiceTableauBord();
        $sTbl->calculer('formule', []);
    }

}