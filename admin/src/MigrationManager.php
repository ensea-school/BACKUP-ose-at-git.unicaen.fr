<?php





class MigrationManager
{
    /**
     * @var OseAdmin
     */
    protected $oseAdmin;

    /**
     * @var array
     */
    protected $tablesDiff = [];

    /**
     * @var array
     */
    private $actions = [];



    public function __construct(OseAdmin $oseAdmin)
    {
        $this->oseAdmin = $oseAdmin;
    }



    /**
     * @return OseAdmin
     */
    public function getOseAdmin(): OseAdmin
    {
        return $this->oseAdmin;
    }



    public function getBdd(): \BddAdmin\Bdd
    {
        return $this->oseAdmin->getBdd();
    }



    public function initTablesDef(\BddAdmin\Ddl\Ddl $ref, $filters = [])
    {
        $filters = \BddAdmin\Ddl\DdlFilters::normalize($filters);

        if (array_key_exists(\BddAdmin\Ddl\Ddl::TABLE, $ref)) {
            $ref = $ref->get(\BddAdmin\Ddl\Ddl::TABLE);
        } else {
            $ref = [];
        }

        /* On ne parse que les tables */
        $oldRef           = $this->getBdd()->table()->get($filters->get(\BddAdmin\Ddl\Ddl::TABLE));
        $this->tablesDiff = [];
        if (isset($oldRef) && is_array($oldRef)) {
            foreach ($oldRef as $table => $ddl) {
                $this->tablesDiff[$table]['old'] = $ddl;
            }
        }
        if ($ref) {
            foreach ($ref as $table => $ddl) {
                $this->tablesDiff[$table]['new'] = $ddl;
            }
        }
    }



    public function getTableDiff(string $tableName): ?array
    {
        if (!array_key_exists($tableName, $this->tablesDiff)) {
            return null;
        }
        $tableDiff = $this->tablesDiff[$tableName];
        if (!isset($tableDiff['old'])) $tableDiff['old'] = [];
        if (!isset($tableDiff['new'])) $tableDiff['new'] = [];

        return $tableDiff;
    }



    /**
     * Détermine si une table existe dans la base de données avant migration
     *
     * @param string $tableName
     *
     * @return bool
     */
    public function hasTable(string $tableName): bool
    {
        $d = $this->getTableDiff($tableName);

        return isset($d['old']['columns']);
    }



    /**
     * Détermine si une colonne existe dans la base de données avant migration
     *
     * @param string $tableName
     * @param string $columnName
     *
     * @return bool
     */
    public function hasColumn(string $tableName, string $columnName): bool
    {
        $d = $this->getTableDiff($tableName);

        return isset($d['old']['columns'][$columnName]);
    }



    /**
     * Détermine si une table doit être ajoutée
     *
     * @param string $tableName
     *
     * @return bool
     */
    public function hasNewTable(string $tableName): bool
    {
        $d = $this->getTableDiff($tableName);

        return isset($d['new']['columns']) && !isset($d['old']['columns']);
    }



    /**
     * Détermine si une table doit être supprimée
     *
     * @param string $tableName
     *
     * @return bool
     */
    public function hasOldTable(string $tableName): bool
    {
        $d = $this->getTableDiff($tableName);

        return isset($d['old']['columns']) && !isset($d['new']['columns']);
    }



    /**
     * Détermine si une colonne doit être ajoutée
     *
     * @param string $tableName
     * @param string $columnName
     *
     * @return bool
     */
    public function hasNewColumn(string $tableName, string $columnName): bool
    {
        $d = $this->getTableDiff($tableName);

        return isset($d['new']['columns'][$columnName]) && !isset($d['old']['columns'][$columnName]);
    }



    /**
     * Détermine si une colonne doit être supprimée
     *
     * @param string $tableName
     * @param string $columnName
     *
     * @return bool
     */
    public function hasOldColumn(string $tableName, string $columnName): bool
    {
        $d = $this->getTableDiff($tableName);

        return isset($d['old']['columns'][$columnName]) && !isset($d['new']['columns'][$columnName]);
    }



    protected function tableRealExists($tableName): bool
    {
        $sql = "SELECT TABLE_NAME FROM USER_TABLES WHERE TABLE_NAME = :tableName";
        $tn  = $this->getBdd()->select($sql, compact('tableName'), ['fetch' => \BddAdmin\Bdd::FETCH_ONE]);

        return isset($tn['TABLE_NAME']) && $tn['TABLE_NAME'] == $tableName;
    }



    public function sauvegarderTable(string $tableName, string $name)
    {
        if ($this->tableRealExists($tableName) && !$this->tableRealExists($name)) {
            $this->getBdd()->exec("CREATE TABLE $name AS SELECT * FROM $tableName");
        }
    }



    public function supprimerSauvegarde(string $name)
    {
        if ($this->tableRealExists($name)) {
            $this->getBdd()->exec("DROP TABLE $name");
        }
    }



    protected function getMigrationDir()
    {
        return $this->oseAdmin->getOseDir() . 'admin/migration/';
    }



    protected function getMigrationObject(string $action): ?AbstractMigration
    {
        if (!array_key_exists($action, $this->actions)) {
            $file = $this->getMigrationDir() . $action . '.php';
            require_once $file;

            /**
             * @var $object AbstractMigration
             */
            $object = new $action($this, $this);
            if ($object->utile()) {
                $this->actions[$action] = $object;
            } else {
                $this->actions[$action] = null;
            }
        }

        return $this->actions[$action];
    }



    protected function runMigrationAction(string $contexte, string $action)
    {
        $console = $this->oseAdmin->getConsole();

        $migration = $this->getMigrationObject($action);
        if (
            $migration
            && $migration instanceof AbstractMigration
            && ($contexte == $migration->getContexte() || AbstractMigration::CONTEXTE_ALL == $migration->getContexte())
        ) {
            $console->print("[$contexte MIGRATION] " . $migration->description() . ' ... ');
            try {
                $migration->action($contexte);
                $console->println('OK', $console::COLOR_GREEN);
            } catch (\Throwable $e) {
                $console->println('Erreur : ' . $e->getMessage(), $console::COLOR_RED);
            }
        }
    }



    public function testUtile($action): bool
    {
        $migration = $this->getMigrationObject($action);

        return $migration instanceof AbstractMigration;
    }



    public function migration(string $context = 'pre', string $action = null)
    {
        if (!is_dir($this->getMigrationDir())) return;
        $files = scandir($this->getMigrationDir());

        foreach ($files as $i => $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $fileAction = substr($file, 0, -4); // on supprime l'extension PHP
            if ($action === null || $fileAction === $action) {
                $this->runMigrationAction($context, $fileAction);
            }
        }
    }
}