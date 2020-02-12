<?php

namespace BddAdmin\Data;

use BddAdmin\Bdd;
use BddAdmin\BddAwareTrait;

class Updater
{

    use BddAwareTrait;



    /**
     * @inheritDoc
     */
    public function __construct(Bdd $bdd)
    {
        $this->setBdd($bdd);
    }



    /**
     * @param string       $table
     * @param array        $data
     * @param string|array $key
     * @param array        $ignoredCols
     *
     * @throws \BddAdmin\Exception\BddCompileException
     * @throws \BddAdmin\Exception\BddException
     * @throws \BddAdmin\Exception\BddIndexExistsException
     */
    public function update(string $table, array $data, $key, array $options = [])
    {
        $ddlObject = new \BddAdmin\Ddl\DdlTable($this->getBdd());
        $ddl       = $ddlObject->get($table)[$table];

        $tableWithId       = isset($ddl['columns']['ID']);
        $tableWithSequence = !empty($ddl['sequence']);

        /* Initialisation */
        $records   = [];
        $newRecord = [
            'id'   => null,
            'bdd'  => [],
            'data' => [],
        ];
        if ($tableWithId) {
            $options['ID']['ignore'] = true;
        }


        /* Chargement des enregistrements actuels */
        $result = $this->getBdd()->select('SELECT * FROM ' . $ddl['name']);
        foreach ($result as $record) {
            $keyVal = $this->makeValWithKey($record, $key);
            if (!isset($records[$keyVal])) {
                $records[$keyVal] = $newRecord;
            }

            if ($tableWithId) {
                $records[$keyVal]['id'] = (int)$record['ID'];
            }
            foreach ($ddl['columns'] as $colName => $colDdl) {
                if (!isset($options[$colName]['ignore']) || !$options[$colName]['ignore']) {
                    $colVal                            = $record[$colName];
                    $records[$keyVal]['bdd'][$colName] = $this->sqlToVal($colVal, $colDdl);
                }
            }
        }


        /* Chargement des données */
        foreach ($data as $d) {
            foreach ($d as $k => $v) {
                if (isset($options[$k]['transformer'])) {
                    $d[$k] = $this->transform($v, $options[$k]['transformer'], $ddl['columns'][$k]);
                }
            }

            $keyVal = $this->makeValWithKey($d, $key);
            if (!isset($records[$keyVal])) {
                $records[$keyVal] = $newRecord;
            }

            foreach ($ddl['columns'] as $colName => $colDdl) {
                if (!isset($options[$colName]['ignore']) || !$options[$colName]['ignore']) {
                    $records[$keyVal]['data'][$colName] = $d[$colName];
                }
            }
        }


        /* Mise à jour de la table */
        foreach ($records as $index => $record) {
            if (empty($record['bdd'])) { // INSERT
                if (empty($record['id']) && $tableWithSequence) {
                    $record['id'] = $this->getBdd()->sequenceNextVal($ddl['sequence']);
                }
                $new = [];
                if ($tableWithId) {
                    $new['ID'] = $record['id'];
                }
                foreach ($ddl['columns'] as $colName => $colDdl) {
                    if (!isset($options[$colName]['ignore']) || !$options[$colName]['ignore']) {
                        $new[$colName] = $record['data'][$colName];
                    }
                }
                $this->getBdd()->insert($ddl['name'], $new);
            } elseif (empty($record['data'])) { // DELETE
                $this->getBdd()->delete($ddl['name'], $record['id']);
            } else { // UPDATE ?
                $diff = [];
                foreach ($ddl['columns'] as $colName => $colDdl) {
                    if (!isset($options[$colName]['ignore']) || !$options[$colName]['ignore']) {
                        if ($record['bdd'][$colName] !== $record['data'][$colName]) {
                            $diff[$colName] = $record['data'][$colName];
                        }
                    }
                }
                if (!empty($diff)) {
                    $this->getBdd()->update($ddl['name'], $diff, ['ID' => $record['id']]);
                }
            }
        }
    }



    protected function transform($value, string $transformer, array $ddl)
    {
        $val    = $this->getBdd()->select(sprintf($transformer, ':val'), ['val' => $value]);
        $result = $this->sqlToVal(current($val[0]), $ddl);

        return $result;
    }



    protected function makeValWithKey(array $data, $key)
    {
        $result = '';
        $key    = (array)$key;
        foreach ($key as $k) {
            if ($result != '') {
                $result .= '_&@&@&@&_';
            }
            $result .= $data[$k];
        }

        return $result;
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
            case Bdd::TYPE_BLOB
                return $value;
            default:
                throw new \Exception("Type de donnée " . $ddl['type'] . " non géré.");
        }

        return $value;
    }

}