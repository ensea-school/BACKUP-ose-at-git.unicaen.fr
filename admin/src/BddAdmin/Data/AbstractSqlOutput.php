<?php

namespace BddAdmin\Data;


abstract class AbstractSqlOutput extends AbstractOutput
{
    /**
     * @var array
     */
    private $lobs = [];



    public function ecrireDebut(?string $table = null)
    {
        if (!$table) {
            $this->lobs = [];
        }
    }



    public function ecrireFin(?string $table = null)
    {
        if (!$table) {
            foreach ($this->lobs as $filename => $lob) {
                $this->ecrireLob($filename, $lob);
            }
        }
    }



    abstract public function ecrireLob(string $filename, object $lob);



    public function ecrire(string $table, array $data)
    {
        $cols = [];
        $vals = [];
        foreach ($data as $col => $value) {
            if (is_object($value) && get_class($value) == 'OCI-Lob') {
                $id           = isset($data['ID']) ? $data['ID'] : null;
                if ($id) {
                    $filename              = "$table.$col.$id.lob";
                    $this->lobs[$filename] = $value;
                }else{
                    throw new \Exception('ID non défini');
                }
            } else {
                $cols[] = $this->colToSql($col);
                $vals[] = $this->valToSql($value);
            }
        }

        $sql = 'INSERT INTO ' . $this->colToSql($table) . '(' . implode(', ', $cols) . ') VALUES (' . implode(', ', $vals) . ');';

        return $sql . "\n";
    }



    /**
     * @param string $column
     *
     * @return string
     */
    private function colToSql(string $column): string
    {
        if ($column !== strtoupper($column)) {
            return '"' . $column . '"';
        } else {
            return $column;
        }
    }



    /**
     * @param $value
     *
     * @return string
     * @throws \Exception
     */
    private function valToSql($value): string
    {
        if (null === $value) return 'NULL';
        switch (gettype($value)) {
            case 'integer':
            case 'double':
                return (string)$value;
            case 'boolean':
                return $value ? '1' : '0';
            case 'string':
                return "'" . str_replace("'", "''", $value) . "'";
            case 'object':
                switch (get_class($value)) {
                    case 'DateTime':
                        /** @var $value \DateTime */
                        if (0 === (int)$value->format('Hisu')) {
                            return 'to_date(\'' . $value->format('Y-m-d') . '\', \'YYYY-MM-DD\')';
                        } else {
                            return 'to_date(\'' . $value->format('Y-m-d H:i:s') . '\', \'YYYY-MM-DD HH24:MI:SS\')';
                        }
                }
                if (method_exists($value, 'getId')) {
                    return (string)$value->getId();
                }
                if (method_exists($value, '__toString')) {
                    return (string)$value;
                }
            break;
        }
        throw new \Exception($value . ' (type:' . gettype($value) . ') n\'a pas pu être converti en SQL');
    }

}