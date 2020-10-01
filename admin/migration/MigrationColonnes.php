<?php





class MigrationColonnes extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Migration de certaines colonnes de la base de données avant update";
    }



    public function utile(): bool
    {
        return true;
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
        //Renommage des colonnes de la table PAYS
        if ($this->manager->hasOldColumn('PAYS', 'LIBELLE_COURT')
            && $this->manager->hasOldColumn('PAYS', 'LIBELLE_LONG')
            && $this->manager->hasNewColumn('PAYS', 'LIBELLE')) {
            $console->println("Renommage de la colonne LIBELLE_LONG en LIBELLE dans la table PAYS");
            $sql = "ALTER TABLE PAYS RENAME COLUMN LIBELLE_LONG TO LIBELLE";
            $console->println($sql);
            $bdd->exec($sql);
            $console->println("Renommage de la colonne effectué");
        }
        if ($this->manager->hasOldColumn('PAYS', 'SOURCE_CODE')
            && $this->manager->hasNewColumn('PAYS', 'CODE')) {
            $console->println("Renommage de la colonne SOURCE_CODE en CODE dans la table PAYS");
            $sql = "ALTER TABLE PAYS RENAME COLUMN SOURCE_CODE TO CODE";
            $console->println($sql);
            $bdd->exec($sql);
            $console->println("Renommage de la colonne effectué");
        }
        //Renommage des colonnes de la table DEPATREMENT
        if ($this->manager->hasOldColumn('DEPARTEMENT', 'LIBELLE_COURT')
            && $this->manager->hasOldColumn('DEPARTEMENT', 'LIBELLE_LONG')
            && $this->manager->hasNewColumn('DEPARTEMENT', 'LIBELLE')) {
            $console->println("Renommage de la colonne LIBELLE_LONG en LIBELLE dans la table DEPARTEMENT");
            $sql = "ALTER TABLE DEPARTEMENT RENAME COLUMN LIBELLE_LONG TO LIBELLE";
            $console->println($sql);
            $bdd->exec($sql);
            $console->println("Renommage de la colonne effectué");
        }
        //Renommage des colonnes de la table STATUT_INTERVENANT
        if ($this->manager->hasOldColumn('STATUT_INTERVENANT', 'SOURCE_CODE')
            && $this->manager->hasNewColumn('STATUT_INTERVENANT', 'CODE')) {
            $console->println("Renommage de la colonne SOURCE_CODE en CODE dans la table STATUT_INTERVENANT");
            $sql = "ALTER TABLE STATUT_INTERVENANT RENAME COLUMN SOURCE_CODE TO CODE";
            $console->println($sql);
            $bdd->exec($sql);
            $console->println("Renommage de la colonne effectué");
        }
    }



    protected function after()
    {

    }
}

