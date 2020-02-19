<?php





class FormuleMigrationVolumeHoraireStructuresTestVersCode extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;

    protected $data;



    public function description(): string
    {
        return "Prise en compte des nouveaux codes structures sur FORMULE_TEST_VOLUME_HORAIRE";
    }



    public function utile(): bool
    {
        return
            $this->manager->hasOldColumn('FORMULE_TEST_VOLUME_HORAIRE', 'STRUCTURE_TEST_ID')
            &&
            (
                $this->manager->hasNewColumn('FORMULE_TEST_VOLUME_HORAIRE', 'STRUCTURE_CODE')
                ||
                $this->manager->hasColumn('FORMULE_TEST_VOLUME_HORAIRE', 'STRUCTURE_CODE')
            );
    }



    public function action(string $contexte)
    {
        switch ($contexte) {
            case self::CONTEXTE_PRE:
                $this->avant();
            break;
            case self::CONTEXTE_POST:
                $this->apres();
            break;
        }
    }



    public function avant()
    {
        $this->manager->sauvegarderTable('FORMULE_TEST_STRUCTURE', 'FORMULE_TEST_STRUCTURE__MIGR_V');
        $this->manager->sauvegarderTable('FORMULE_TEST_VOLUME_HORAIRE', 'FORMULE_TEST_VH__MIGR');
    }



    public function apres()
    {
        $bdd = $this->manager->getSchema()->getBdd();

        $sql  = "
        SELECT 
          V.ID ID,
          S.LIBELLE STRUCTURE_CODE,
          S.UNIVERSITE,
          V.PARAM_1,
          F.PACKAGE_NAME
        FROM 
          FORMULE_TEST_VH__MIGR V 
          JOIN FORMULE_TEST_STRUCTURE__MIGR_V S ON S.ID = V.STRUCTURE_TEST_ID
          JOIN FORMULE_TEST_VOLUME_HORAIRE NV ON NV.ID = V.ID
          JOIN FORMULE_TEST_INTERVENANT FTI ON FTI.ID = V.INTERVENANT_TEST_ID
          JOIN FORMULE F ON F.ID = FTI.FORMULE_ID
        WHERE 
          NV.STRUCTURE_CODE IS NULL
        ";
        $data = $bdd->select($sql);
        foreach ($data as $d) {
            $params = ['STRUCTURE_CODE' => $d['STRUCTURE_CODE']];
            if ($d['UNIVERSITE'] == 1) {
                $params['STRUCTURE_CODE'] = '__UNIV__';
            }
            if ($d['PACKAGE_NAME'] == 'FORMULE_LYON2') {
                $param1 = strtoupper($d['PARAM_1']);
                if ($param1 == 'D4DAC10000') $params['STRUCTURE_CODE'] = $param1;
            } elseif ($d['PACKAGE_NAME'] == 'FORMULE_NANTERRE') {
                $param1 = strtoupper($d['PARAM_1']);
                if ($param1 == 'KE8' || $param1 == 'UP10') {
                    $params['STRUCTURE_CODE'] = $param1;
                    $params['PARAM_1']        = null;
                }
            }
            $bdd->getTable('FORMULE_TEST_VOLUME_HORAIRE')->update(
                $params,
                ['ID' => $d['ID']],
                );
        }

        $this->manager->supprimerSauvegarde('FORMULE_TEST_VH__MIGR');
        $this->manager->supprimerSauvegarde('FORMULE_TEST_STRUCTURE__MIGR_V');
    }

}