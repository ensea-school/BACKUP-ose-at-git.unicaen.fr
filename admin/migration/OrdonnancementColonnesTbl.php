<?php





class OrdonnancementColonnesTbl extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;

    protected $tbls     = [];



    public function description(): string
    {
        return "Suppression de tableaux de bord dont l'ordonnancement des colonnes a changé";
    }



    public function utile(): bool
    {
        if (count($this->tbls) > 0) return true;

        $bdd = $this->manager->getBdd();

        $sql = "SELECT 
              tbl.table_name,
              tc.column_name,
              tc.column_id position  
            FROM 
              tbl
              JOIN user_tab_columns tc ON tc.table_name = tbl.table_name
            WHERE 
              tbl.table_name IS NOT NULL AND tbl.view_name IS NOT NULL";

        $tblcs      = $bdd->select($sql);
        $this->tbls = [];
        $tables     = $this->manager->getRef()->get('table');
        foreach ($tblcs as $tc) {
            $table = $tc['TABLE_NAME'];
            $col   = $tc['COLUMN_NAME'];
            $pos   = (int)$tc['POSITION'];
            if (isset($tables[$table]['columns'][$col])) {
                if ($pos != $tables[$table]['columns'][$col]['position']) {
                    $this->tbls[$table] = true; // Position différente => la table sera recréée
                }
            } else {
                $this->tbls[$table] = true; // Nouvelle colonne => la table sera recréée
            }
        }

        return count($this->tbls) > 0;
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
        $console->println('');
        foreach ($this->tbls as $table => $null) {
            $console->println("Suppression de la table $table");
            $bdd->table()->drop($table);
        }
    }



    protected function after()
    {
        $console = $this->manager->getOseAdmin()->getConsole();

        $console->begin("Recalcul de tous les tableaux de bord");
        $this->manager->getOseAdmin()->exec('calcul-tableaux-bord');
        $console->end("Tableaux de bord recalculés");
    }

}

