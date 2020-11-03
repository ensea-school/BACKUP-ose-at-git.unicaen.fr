<?php





class OrdonnancementColonnesTbl extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_PRE;

    protected $tbls     = [];



    public function description(): string
    {
        return "Suppression de tableaux de bord dont l'ordonnancement des colonnes a changé";
    }



    public function utile(): bool
    {
        $bdd = $this->manager->getBdd();

        $colPosFile  = $this->manager->getOseAdmin()->getOseDir() . 'data/ddl_columns_pos.php';
        $colonnesPos = require $colPosFile;

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
        foreach ($tblcs as $tc) {
            $table = $tc['TABLE_NAME'];
            $col   = $tc['COLUMN_NAME'];
            $pos   = ((int)$tc['POSITION']) - 1;
            if (isset($colonnesPos[$table][$pos])) {
                if ($col != $colonnesPos[$table][$pos]) {
                    $this->tbls[$table] = true;
                }
            }
        }

        return count($this->tbls) > 0;
    }



    public function action(string $contexte)
    {
        if ($contexte == self::CONTEXTE_PRE) {
            $this->before();
        }
    }



    protected function before()
    {
        $bdd = $this->manager->getBdd();

        foreach ($this->tbls as $table => $null) {
            $bdd->table()->drop($table);
        }
    }

}

