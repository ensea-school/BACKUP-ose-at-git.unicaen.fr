<?php





class MigrationPays extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Migration de la table PAYS";
    }



    public function utile(): bool
    {
        return $this->manager->hasOldColumn('PAYS', 'LIBELLE_COURT')
            && $this->manager->hasOldColumn('PAYS', 'LIBELLE_LONG')
            && $this->manager->hasNewColumn('PAYS', 'LIBELLE');
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
        $console->println("Renommage de la colonne LIBELLE_LONG en LIBELLE dans la table PAYS");
        $sql = "RENAME COLUMN LIBELLE_LONG TO LIBELLE";
        $console->println($sql);
        $bdd->exec($sql);
        $console->println("Renommage de la colonne effectué");
    }



    protected function after()
    {

    }
}

