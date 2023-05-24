<?php

namespace BddAdmin;

class DataUpdater
{
    use BddAwareTrait;

    private array $config      = [];

    private array $sources     = [];

    private array $sourcesData = [];

    private array $actions     = [
        'install' => 'Insertion des données',
        'update'  => 'Contrôle et mise à jour des données',
    ];



    public function __construct(Bdd $bdd)
    {
        $this->setBdd($bdd);
    }



    public function run(string $action, ?string $table = null)
    {
        if (!isset($this->actions[$action])) {
            throw new \Exception('Action "' . $action . '" inconnue');
        }

        if ($table && !isset($this->config[$table])) {
            throw new \Exception('La table "' . $table . '" n\'est pas de directives de configuration : action impossible à  réaliser');
        }

        $config = $table ? [$table => $this->config[$table]] : $this->config;

        $this->getBdd()->logBegin($this->actions[$action]);

        foreach ($config as $table => $config) {
            $actions = (array)$config['actions'];
            unset($config['actions']);
            if (in_array($action, $actions)) {
                $this->syncTable($table, $action, $config);
            }
        }

        $this->getBdd()->logEnd();
    }



    private function syncTable(string $table, string $action, array $config)
    {
        $tableObject = $this->getBdd()->getTable($table);
        $ddl         = $tableObject->getDdl();

        $data = null;
        foreach ($this->sources as $i => $source) {
            if (is_object($source) && method_exists($source, $table)) {
                $data = $source->$table($action);
                break;
            }
            if (is_array($source) && isset($source[$table])) {
                $data = $source[$table];
                break;
            }
            if (is_string($source)) {
                if (!isset($this->sourcesData[$i]) && file_exists($source)) {
                    $this->sourcesData[$i] = require $source;
                }
                if (isset($this->sourcesData[$i][$table])) {
                    $data = $this->sourcesData[$i][$table];
                    break;
                }
            }
        }

        if (null === $data) {
            throw new \Exception('Données sources introuvables pour la table "' . $table . '"');
        }

        $result = $tableObject->merge(
            $data,
            $config['key'] ?? 'ID',
            $config['options'] ?? []
        );
        if ($result['insert'] + $result['update'] + $result['delete'] > 0) {
            $msg = str_pad($table, 31, ' ');
            $msg .= 'Insert: ' . $result['insert'] . ', Update: ' . $result['update'] . ', Delete: ' . $result['delete'];
            $this->getBdd()->logMsg($msg);
        }
    }



    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }



    /**
     * @param array $config
     *
     * @return DataUpdater
     */
    public function setConfig(array $config): DataUpdater
    {
        $this->config = $config;

        return $this;
    }



    public function addConfig(string $table, array $config): DataUpdater
    {
        $this->config[$table] = $config;

        return $this;
    }



    /**
     * @return string[]|array[]|Object[]
     */
    public function getSources(): array
    {
        return $this->sources;
    }



    public function addSource(string|array|object $source): DataUpdater
    {
        $this->sources[] = $source;

        return $this;
    }



    /**
     * @return array|string[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }



    public function addAction(string $name, string $libelle): DataUpdater
    {
        $this->actions[$name] = $libelle;

        return $this;
    }

}