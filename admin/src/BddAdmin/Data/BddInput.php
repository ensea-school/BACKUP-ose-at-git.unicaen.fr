<?php

namespace BddAdmin\Data;

use BddAdmin\Bdd;
use BddAdmin\BddAwareTrait;
use BddAdmin\Ddl\DdlTable;

class BddInput extends AbstractInput
{
    use BddAwareTrait;

    /**
     * @var array
     */
    private $includes = [];

    /**
     * @var array
     */
    private $excludes = [];

    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var array
     */
    private $transformers = [];

    /**
     * @var string
     */
    private $currentTable;

    /***
     * @var array
     */
    private $currentDdl;

    /**
     * @var AbstractOutput
     */
    private $currentOutput;



    protected function transformerMatch(string $transformer, string $table, string $column)
    {
        [$mt, $mc] = explode('.', strtoupper($transformer));

        $ok = true;
        if (!($mt == $table || $mt == '*')) { // si ça ne passe pas
            if (0 === strpos($mt, '!')) {
                if ($table == substr($mt, 1)) { // match sur le négatif
                    $ok = false;
                }
            } else {
                $ok = false;
            }
        }

        if (!($mc == $column || $mc == '*')) { // si ça ne passe pas
            if (0 === strpos($mc, '!')) {
                if ($column == substr($mc, 1)) { // match sur le négatif
                    $ok = false;
                }
            } else {
                $ok = false;
            }
        }

        return $ok;
    }



    protected function sqlToVal($value, array $ddl)
    {
        switch ($ddl['type']) {
            case Bdd::TYPE_BOOL:
                return (bool)$value;
            case Bdd::TYPE_INT:
                if (1 == $ddl['precision']) {
                    return $value === '1';
                } else {
                    return (int)$value;
                }

            case Bdd::TYPE_FLOAT:
                return (float)$value;
            case Bdd::TYPE_STRING:
            case Bdd::TYPE_CLOB:
                return $value;
            case Bdd::TYPE_DATE:
                $date = \DateTime::createFromFormat('Y-m-d', $value);
                $date->setTime(0, 0, 0, 0);

                return $date;
            case Bdd::TYPE_BLOB:
                return $value;
            default:
                throw new \Exception("Type de donnée " . $ddl['type'] . " non géré.");
        }

        return $value;
    }



    /**
     * @inheritDoc
     */
    public function applyConfig(array $config)
    {
        if (isset($config['bdd'])) {
            $this->setBdd($config['bdd']);
        }
        if (isset($config['includes'])) {
            $this->setIncludes((array)$config['includes']);
        }
        if (isset($config['excludes'])) {
            $this->setExcludes((array)$config['excludes']);
        }
        if (isset($config['filters'])) {
            $this->setFilters((array)$config['filters']);
        }
        if (isset($config['transformers'])) {
            $this->setTransformers($config['transformers']);
        }
    }



    public function lire(AbstractOutput $output)
    {
        $bdd = $this->getBdd();
        if (!$bdd) {
            throw new \Exception('Une base de données doit être spécifiée!');
        }

        $ddl = new DdlTable($bdd);
        $ddl->addOption($ddl::OPT_NO_TEMPORARY);
        $tables = $ddl->get($this->getIncludes(), $this->getExcludes());

        $this->currentOutput = $output;
        $output->ecrireDebut();
        foreach ($tables as $table => $tddl) {
            $this->currentTable = $table;
            $this->currentDdl   = $tddl;
            $this->transfererTable();
        }
        $output->ecrireFin();
    }



    protected function transfererTable()
    {
        $this->currentOutput->ecrireDebut($this->currentTable);
        $bdd = $this->getBdd();

        $fields = [];
        foreach ($this->currentDdl['columns'] as $name => $column) {
            $fields[$name] = null;
            switch ($column['type']) {
                case Bdd::TYPE_DATE:
                    $fields[$name] = "to_char( $name, 'YYYY-mm-dd' )";
            }
        }

        $sql = "SELECT ";
        $ff  = true;
        foreach ($fields as $name => $val) {
            if (!$ff) $sql .= ', ';
            if ($val) {
                $sql .= $val . ' ' . $name;
            } else {
                $sql .= $name;
            }
            $ff = false;
        }
        $sql .= " FROM " . $this->currentTable;
        if ($filter = $this->getFilters($this->currentTable)) {
            $sql .= " WHERE " . $filter;
        }

        $stmt = $bdd->select($sql, [], $bdd::FETCH_EACH);
        while ($data = $bdd->fetch($stmt)) {
            foreach ($data as $col => $value) {
                $data[$col] = $this->sqlToVal($value, $this->currentDdl['columns'][$col]);
            }
            $this->transfererLigne($data);
        }
        $this->currentOutput->ecrireFin($this->currentTable);
    }



    protected function transfererLigne(array $data)
    {
        /* Application du transformer */
        if ($this->transformers) {
            foreach ($this->transformers as $transformer => $rule) {
                foreach ($data as $column => $value) {
                    if ($this->transformerMatch($transformer, $this->currentTable, $column)) {
                        if (is_callable($rule)) {
                            $data[$column] = $rule($this->currentTable, $column, $this->currentDdl['columns'][$column], $value);
                        } else {
                            $data[$column] = $rule;
                        }
                    }
                }
            }
        }
        $this->currentOutput->ecrire($this->currentTable, $data);
    }



    /**
     * @return array
     */
    public function getIncludes(): array
    {
        return $this->includes;
    }



    /**
     * @param array $includes
     *
     * @return $this
     */
    public function setIncludes(array $includes): self
    {
        $this->includes = $includes;

        return $this;
    }



    /**
     * @return array
     */
    public function getExcludes(): array
    {
        return $this->excludes;
    }



    /**
     * @param array $excludes
     *
     * @return $this
     */
    public function setExcludes(array $excludes): self
    {
        $this->excludes = $excludes;

        return $this;
    }



    /**
     * @param string|null $table
     *
     * @return array|mixed|null
     */
    public function getFilters(?string $table = null)
    {
        if ($table) {
            if (isset($this->filters[$table])) {
                return $this->filters[$table];
            } else {
                return null;
            }
        }

        return $this->filters;
    }



    /**
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }



    /**
     * @return array
     */
    public function getTransformers(): array
    {
        return $this->transformers;
    }



    /**
     * @param array $transformers
     *
     * @return $this
     */
    public function setTransformers(array $transformers): self
    {
        $this->transformers = $transformers;

        return $this;
    }

}