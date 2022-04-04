<?php

use BddAdmin\Bdd;
use BddAdmin\Ddl\Ddl;
use BddAdmin\Ddl\DdlFilters;

class MigrationManager
{
    /**
     * @var OseAdmin
     */
    protected $oseAdmin;

    /**
     * @var Ddl
     */
    protected $ref;

    /**
     * @var Ddl
     */
    protected $old;

    /**
     * @var DdlFilters
     */
    protected $filters;

    /**
     * @var array
     */
    private $actions = [];



    public function __construct(OseAdmin $oseAdmin, Ddl $ref, $filters = [])
    {
        $this->oseAdmin = $oseAdmin;
        $this->ref      = $ref;
        $this->filtres  = DdlFilters::normalize($filters);
    }



    /**
     * @return OseAdmin
     */
    public function getOseAdmin(): OseAdmin
    {
        return $this->oseAdmin;
    }



    public function getBdd(): Bdd
    {
        return $this->oseAdmin->getBdd();
    }



    /**
     * Retourne la nouvelle DDL de la base de données
     *
     * @return Ddl
     */
    public function getRef(): Ddl
    {
        return $this->ref;
    }



    /**
     * Retourne ll'ancienne DDL de la base de données
     *
     * @return Ddl
     */
    public function getOld(): Ddl
    {
        return $this->old;
    }



    /**
     * Détermine si un objet existe dans la base de données avant migration
     *
     * @param string $type
     * @param string $name
     *
     * @return bool
     */
    public function has(string $type, string $name): bool
    {
        return isset($this->old->get($type)[$name]);
    }



    /**
     * @param string $type
     * @param string $Name
     *
     * @return bool
     */
    public function hasNew(string $type, string $Name): bool
    {
        return !isset($this->old->get($type)[$Name]) && isset($this->ref->get($type)[$Name]);
    }



    /**
     * @param string $type
     * @param string $Name
     *
     * @return bool
     */
    public function hasOld(string $type, string $Name): bool
    {
        if (Ddl::TABLE == $type) {
            return $this->tableRealExists($Name) && !isset($this->ref->get(Ddl::TABLE)[$Name]);
        } else {
            return isset($this->old->get($type)[$Name]) && !isset($this->ref->get($type)[$Name]);
        }
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
        return isset($this->old->get(Ddl::TABLE)[$tableName]['columns'][$columnName]);
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
        $old = $this->old->get(DDl::TABLE);
        $new = $this->ref->get(Ddl::TABLE);

        return isset($new[$tableName]['columns'][$columnName]) && !isset($old[$tableName]['columns'][$columnName]);
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
        $old = $this->old->get(DDl::TABLE);
        $new = $this->ref->get(Ddl::TABLE);

        return !isset($new[$tableName]['columns'][$columnName]) && isset($old[$tableName]['columns'][$columnName]);
    }



    protected function tableRealExists($tableName): bool
    {
        $sql = "SELECT TABLE_NAME FROM USER_TABLES WHERE TABLE_NAME = :tableName";
        $tn  = $this->getBdd()->select($sql, compact('tableName'), ['fetch' => Bdd::FETCH_ONE]);

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
        if (!$this->old) {
            $this->old = $this->oseAdmin->getBdd()->getDdl($this->filters);
        }

        if (!is_dir($this->getMigrationDir())) return;
        $files = scandir($this->getMigrationDir());
        sort($files);

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