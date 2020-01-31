<?php





class MigrationManager
{
    /**
     * @var OseAdmin
     */
    protected $oseAdmin;

    /**
     * @var \BddAdmin\Schema
     */
    protected $schema;

    /**
     * @var array
     */
    protected $tablesDiff = [];

    /**
     * @var array
     */
    private $actions = [];



    public function __construct(OseAdmin $oseAdmin, \BddAdmin\Schema $schema)
    {
        $this->oseAdmin = $oseAdmin;
        $this->schema   = $schema;
    }



    /**
     * @return OseAdmin
     */
    public function getOseAdmin(): OseAdmin
    {
        return $this->oseAdmin;
    }



    /**
     * @return \BddAdmin\Schema
     */
    public function getSchema(): \BddAdmin\Schema
    {
        return $this->schema;
    }



    public function initTablesDef(array $ref, array $ddlConfig)
    {
        $tablesKey = \BddAdmin\Ddl\DdlTable::class;

        /* On ne parse que les tables */
        $ddlConfig                        = [$tablesKey => $ddlConfig[$tablesKey]];
        $ddlConfig['explicit']            = true;
        $ddlConfig['include-tables-deps'] = false;
        $oldRef                           = $this->schema->getDdl($ddlConfig);
        $this->tablesDiff                 = [];
        if (isset($oldRef[$tablesKey]) && is_array($oldRef[$tablesKey])) {
            foreach ($oldRef[$tablesKey] as $table => $ddl) {
                $this->tablesDiff[$table]['old'] = $ddl;
            }
        }
        if (isset($ref[$tablesKey]) && is_array($ref[$tablesKey])) {
            foreach ($ref[$tablesKey] as $table => $ddl) {
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
        $tn  = $this->getSchema()->getBdd()->select($sql, compact('tableName'), \BddAdmin\Bdd::FETCH_ONE);

        return isset($tn['TABLE_NAME']) && $tn['TABLE_NAME'] == $tableName;
    }



    public function sauvegarderTable(string $tableName, string $name)
    {
        if ($this->tableRealExists($tableName) && !$this->tableRealExists($name)) {
            $this->getSchema()->getBdd()->exec("CREATE TABLE $name AS SELECT * FROM $tableName");
        }
    }



    public function supprimerSauvegarde(string $name)
    {
        if ($this->tableRealExists($name)) {
            $this->getSchema()->getBdd()->exec("DROP TABLE $name");
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