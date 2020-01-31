<?php





class FormuleMigrationIntervenantStructuresTestVersCode extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;

    protected $data;



    public function description(): string
    {
        return "Prise en compte des nouveaux codes structures sur FORMULE_TEST_INTERVENANT";
    }



    public function utile(): bool
    {
        return
            $this->manager->hasOldColumn('FORMULE_TEST_INTERVENANT', 'STRUCTURE_TEST_ID')
            &&
            (
                $this->manager->hasNewColumn('FORMULE_TEST_INTERVENANT', 'STRUCTURE_CODE')
                ||
                $this->manager->hasColumn('FORMULE_TEST_INTERVENANT', 'STRUCTURE_CODE')
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
        $this->manager->sauvegarderTable('FORMULE_TEST_STRUCTURE', 'FORMULE_TEST_STRUCTURE__MIGR_I');
        $this->manager->sauvegarderTable('FORMULE_TEST_INTERVENANT', 'FORMULE_TEST_INTERVENANT__MIGR');
    }



    public function apres()
    {
        $bdd = $this->manager->getSchema()->getBdd();

        $sql  = "
        SELECT 
          I.ID ID,
          S.LIBELLE STRUCTURE_CODE
        FROM 
          FORMULE_TEST_INTERVENANT__MIGR I
          JOIN FORMULE_TEST_STRUCTURE__MIGR_I S ON S.ID = I.STRUCTURE_TEST_ID
          JOIN FORMULE_TEST_INTERVENANT NI ON NI.ID = I.ID 
        WHERE 
          NI.STRUCTURE_CODE IS NULL
        ";
        $data = $bdd->select($sql);
        foreach ($data as $d) {
            $bdd->getTable('FORMULE_TEST_INTERVENANT')->update(
                ['STRUCTURE_CODE' => $d['STRUCTURE_CODE']],
                ['ID' => $d['ID']],
                );
        }

        $this->manager->supprimerSauvegarde('FORMULE_TEST_INTERVENANT__MIGR');
        $this->manager->supprimerSauvegarde('FORMULE_TEST_STRUCTURE__MIGR_I');
    }

}