<?php





class MigrationStatutIntervenant extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Migration de la table STATUT_INTERVENANT";
    }



    public function utile(): bool
    {
        return $this->manager->hasOldColumn('PAYS', 'SOURCE_CODE')
            && $this->manager->hasNewColumn('PAYS', 'CODE');
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
        $console->println("Renommage de la colonne SOURCE_CODE en LIBELLE dans la table STATUT_INTERVENANT");
        $sql = "ALTER TABLE STATUT_INTERVENANT RENAME COLUMN SOURCE_CODE TO CODE";
        $console->println($sql);
        $bdd->exec($sql);
        $console->println("Renommage de la colonne effectué");
    }



    protected function after()
    {

    }
}

