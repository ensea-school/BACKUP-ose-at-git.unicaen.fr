<?php





class MigrationDepartement extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Migration de la table DEPARTEMENT";
    }



    public function utile(): bool
    {
        return $this->manager->hasOldColumn('DEPARTEMENT', 'LIBELLE_COURT')
            && $this->manager->hasOldColumn('DEPARTEMENT', 'LIBELLE_LONG')
            && $this->manager->hasNewColumn('DEPARTEMENT', 'LIBELLE');
    }



    public function action(string $contexte)
    {
        if ($contexte == self::CONTEXTE_PRE) {
            $this->before();
        } else {
            $this->after();
        }
    }



    protected function before()
    {
        $bdd     = $this->manager->getBdd();
        $console = $this->manager->getOseAdmin()->getConsole();
        $console->println("Renommage de la colonne LIBELLE_LONG en LIBELLE dans la table DEPARTEMENT");
        $sql = "ALTER TABLE DEPARTEMENT RENAME COLUMN LIBELLE_LONG TO LIBELLE";
        $console->println($sql);
        $bdd->exec($sql);
        $console->println("Renommage de la colonne effectué");
    }



    protected function after()
    {

    }
}

