<?php

namespace BddAdmin;


use BddAdmin\Ddl\DdlTable;

class Table
{

    use BddAwareTrait;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $ddl;

    /**
     * @var array
     */
    private $transformCache = [];



    /**
     * @inheritDoc
     */
    public function __construct(Bdd $bdd, string $name)
    {
        $this->setBdd($bdd);
        $this->name = $name;
    }



    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }



    /**
     * @return array
     */
    public function getDdl(): array
    {
        if (empty($this->ddl)) {
            $ddlObject = $this->getBdd()->getDdl(Bdd::DDL_TABLE);
            $this->ddl = $ddlObject->get($this->name)[$this->name];
        }

        return $this->ddl;
    }



    public function hasHistorique(): bool
    {
        $ddl      = $this->getDdl();
        $hasHisto = isset($ddl['columns']['HISTO_CREATION']) && isset($ddl['columns']['HISTO_MODIFICATION']) && isset($ddl['columns']['HISTO_DESTRUCTION']);

        return $hasHisto;
    }



    /**
     * @param array|integer|null $where
     * @param string|null        $orderBy
     *
     * @return array
     * @throws Exception\BddCompileException
     * @throws Exception\BddException
     * @throws Exception\BddIndexExistsException
     */
    public function select($where = null, array $options = []): array
    {
        /* Initialisation des entrées */
        $defaultOptions = [
            'orderBy' => '',
            'key'     => null,
        ];
        $options        = array_merge($defaultOptions, $options);

        $ddl = $this->getDdl();

        /* Construction et exécution de la requête */
        $cols = '';
        foreach ($ddl['columns'] as $colDdl) {
            if ($cols != '') $cols .= ', ';
            if ($colDdl['type'] == Bdd::TYPE_DATE) {
                $cols .= 'to_char(' . $colDdl['name'] . ',\'YYYY-mm-dd\') ' . $colDdl['name'];
            } else {
                $cols .= $colDdl['name'];
            }
        }
        $sql    = "SELECT $cols FROM \"$this->name\"";
        $params = [];
        $sql    .= $this->makeWhere($where, $options, $params);

        if ($options['orderBy']) {
            $sql .= ' ORDER BY ' . $options['orderBy'];
        }
        $select = $this->getBdd()->select($sql, $params);

        /* Mise en forme des résultats */
        $data = [];
        foreach ($select as $d) {
            foreach ($d as $c => $v) {
                $d[$c] = $this->sqlToVal($v, $ddl['columns'][$c]);
            }
            $keyValue        = $this->makeKey($d, $options['key']);
            $data[$keyValue] = $d;
        }

        return $data;
    }



    /**
     * @param array $data
     * @param array $options
     *
     * @return bool
     * @throws Exception\BddCompileException
     * @throws Exception\BddException
     * @throws Exception\BddIndexExistsException
     */
    public function insert(array $data, array $options = []): bool
    {
        if (!isset($data['ID']) && $this->hasId() && $this->hasSequence()) {
            $data['ID'] = $this->getBdd()->sequenceNextVal($this->ddl['sequence']);
        }

        if (isset($options['histo-user-id']) && $options['histo-user-id'] && $this->hasHistorique()) {
            if (!isset($data['HISTO_CREATION'])) $data['HISTO_CREATION'] = new \DateTime();
            if (!isset($data['HISTO_CREATEUR_ID'])) $data['HISTO_CREATEUR_ID'] = $options['histo-user-id'];
            if (!isset($data['HISTO_MODIFICATION'])) $data['HISTO_MODIFICATION'] = new \DateTime();
            if (!isset($data['HISTO_MODIFICATEUR_ID'])) $data['HISTO_MODIFICATEUR_ID'] = $options['histo-user-id'];
        }

        $cols = array_keys($data);
        $cols = implode(', ', $cols);

        $vals = '';
        foreach ($data as $col => $val) {
            if ($vals != '') $vals .= ',';

            $transVal = ':' . $col;
            if (isset($options['columns'][$col]['transformer'])) {
                $transVal = '(' . sprintf($options['columns'][$col]['transformer'], $transVal) . ')';
            }
            $vals .= $transVal;
        }
        $sql = "INSERT INTO \"$this->name\" ($cols) VALUES ($vals)";

        return $this->getBdd()->exec($sql, $data);
    }



    public function update(array $data, $where = null, array $options = []): bool
    {
        $params = [];

        if (isset($options['histo-user-id']) && $options['histo-user-id'] && $this->hasHistorique()) {
            if (!isset($data['HISTO_MODIFICATION'])) $data['HISTO_MODIFICATION'] = new \DateTime();
            if (!isset($data['HISTO_MODIFICATEUR_ID'])) $data['HISTO_MODIFICATEUR_ID'] = $options['histo-user-id'];
        }

        $dataSql = '';
        foreach ($data as $col => $val) {
            if ($dataSql != '') $dataSql .= ',';

            $transVal = ':new_' . $col;
            if (isset($options['columns'][$col]['transformer'])) {
                $transVal = '(' . sprintf($options['columns'][$col]['transformer'], $transVal) . ')';
            }
            $dataSql               .= $col . '=' . $transVal;
            $params['new_' . $col] = $val;
        }

        $sql = "UPDATE \"$this->name\" SET $dataSql" . $this->makeWhere($where, $options, $params);

        return $this->getBdd()->exec($sql, $params);
    }



    /**
     * @param int|string|array|null $where
     * @param array                 $options
     *
     * @return bool
     */
    public function delete($where = null, array $options = []): bool
    {
        $params = [];
        $sql    = "DELETE FROM \"$this->name\"" . $this->makeWhere($where, $options, $params);

        return $this->getBdd()->exec($sql, $params);
    }



    /**
     * Vide une table
     *
     * @param string $table
     *
     * @return bool
     */
    public function truncate(): bool
    {
        $sql = "TRUNCATE TABLE \"$this->name\"";

        return $this->getBdd()->exec($sql);
    }



    public function merge(array $data, $key, array $options = []): array
    {
        $result = ['insert' => 0, 'update' => 0, 'delete' => 0];

        /* Initialisation */
        $defaultOptions = [
            'where'              => null,
            'key'                => $key,
            'delete'             => true,
            'insert'             => true,
            'update'             => true,
            'update-cols'        => [],
            'update-ignore-cols' => [],
            'update-only-null'   => [],
        ];
        $options        = array_merge($defaultOptions, $options);

        $ddl  = $this->getDdl();
        $diff = [];


        /* Chargement des données actuelles, à mettre à jour */
        $oldData = $this->select($options['where'], $options);
        foreach ($oldData as $k => $d) {
            if (!isset($diff[$k])) {
                $diff[$k] = ['old' => [], 'new' => []];
            }
            $diff[$k]['old'] = $d;
        }


        /* Mise en forme des nouvelles données */
        foreach ($data as $d) {
            foreach ($d as $c => $v) {
                if (isset($options['columns'][$c]['transformer'])) {
                    $d[$c] = $this->transform($v, $options['columns'][$c]['transformer'], $ddl['columns'][$c]);
                }
            }
            $k = $this->makeKey($d, $key);

            if (!isset($diff[$k])) {
                $diff[$k] = ['old' => [], 'new' => []];
            }
            $diff[$k]['new'] = $d;
        }

        $traitementOptions = [];
        if (isset($options['histo-user-id'])) {
            $traitementOptions['histo-user-id'] = $options['histo-user-id'];
        }

        /* Traitement */
        foreach ($diff as $dr) {
            $old = $dr['old'];
            $new = $dr['new'];

            if (empty($old)) { // INSERT
                if ($options['insert']) {
                    $this->insert($new, $traitementOptions);
                    $result['insert']++;
                }
            } elseif (empty($new)) { // DELETE
                if ($options['delete']) {
                    $this->delete($this->makeKeyArray($old, $key));
                    $result['delete']++;
                }
            } elseif ($options['update']) { // UPDATE si différent!!
                $toUpdate = [];
                foreach ($old as $c => $ov) {
                    $newc = isset($new[$c]) ? $new[$c] : null;
                    $oldc = isset($old[$c]) ? $old[$c] : null;
                    if ($newc instanceof \DateTime) $newc = $newc->format('Y-m-d');
                    if ($oldc instanceof \DateTime) $oldc = $oldc->format('Y-m-d');
                    if ($newc !== $oldc && array_key_exists($c, $new) && $c != 'ID') {
                        $ok = empty($options['update-cols']); // OK par défaut si une liste n'a pas été établie manuellement

                        if (in_array($c, $options['update-cols'])) $ok = true;
                        if (in_array($c, $options['update-ignore-cols'])) $ok = false;
                        if (in_array($c, $options['update-only-null']) && $oldc !== null) $ok = false;

                        if ($ok) {
                            $toUpdate[$c] = $new[$c];
                        }
                    }
                }
                if (!empty($toUpdate)) {
                    $this->update($toUpdate, $this->makeKeyArray($old, $key), $traitementOptions);
                    $result['update']++;
                }
            }
        }

        return $result;
    }



    private function makeKeyArray(array $data, $key): array
    {
        if (!$key && $this->hasId()) {
            $key = 'ID';
        }
        $key = (array)$key;

        $keyArray = [];
        foreach ($key as $kc) {
            $keyArray[$kc] = $data[$kc];
        }

        return $keyArray;
    }



    private function makeKey(array $data, $key): string
    {
        $keyArray = $this->makeKeyArray($data, $key);

        $keyVal = '';
        foreach ($keyArray as $v) {
            if ($keyVal != '') $keyVal .= '_';
            if ($v instanceof \DateTime) {
                $keyVal .= $v->format('Y-m-d');
            } else {
                $keyVal .= (string)$v;
            }
        }

        return $keyVal;
    }



    /**
     * @param int|string|array|null $where
     * @param array                 $options
     *
     * @return string
     */
    private function makeWhere($where, array $options, array &$params): string
    {
        if (is_string($where) && (
                false !== strpos($where, '=')
                || false !== strpos($where, ' IN ')
                || false !== strpos($where, ' IN(')
                || false !== strpos($where, ' IS ')
                || false !== strpos($where, ' NOT ')
                || false !== strpos($where, '<')
                || false !== strpos($where, '>')
            )
        ) {
            return ' WHERE ' . $where;
        }
        if ($where && !is_array($where) && $this->hasId()) {
            $where = ['ID' => $where];
        }


        if ($where) {
            $whereSql = '';
            foreach ($where as $c => $v) {
                if ($whereSql != '') {
                    $whereSql .= ' AND ';
                }


                if (isset($options['columns'][$c]['transformer'])) {
                    $transVal   = ':' . $c;
                    $transVal   = '(' . sprintf($options['columns'][$c]['transformer'], $transVal) . ')';
                    $whereSql   .= $c . ' = ' . $transVal;
                    $params[$c] = $v;
                } else {
                    if ($v === null) {
                        $whereSql .= $c . ' IS NULL';
                    } else {
                        $transVal   = ':' . $c;
                        $whereSql   .= $c . ' = ' . $transVal;
                        $params[$c] = $v;
                    }
                }
            }

            return ' WHERE ' . $whereSql;
        }

        return '';
    }



    /**
     * @return bool
     */
    protected function hasId(): bool
    {
        $ddl = $this->getDdl();

        return isset($ddl['columns']['ID']);
    }



    /**
     * @return bool
     */
    protected function hasSequence(): bool
    {
        $ddl = $this->getDdl();

        return $ddl['sequence'] != null;
    }



    protected function transform($value, string $transformer, array $ddl)
    {
        if (!isset($this->transformCache[$transformer][$value])) {
            $val = $this->getBdd()->select(sprintf($transformer, ':val'), ['val' => $value]);
            if (isset($val[0])) {
                $this->transformCache[$transformer][$value] = $this->sqlToVal(current($val[0]), $ddl);
            } else {
                $this->transformCache[$transformer][$value] = null;
            }
        }

        return $this->transformCache[$transformer][$value];
    }



    protected function sqlToVal($value, array $ddl)
    {
        if ($value === null) return null;

        switch ($ddl['type']) {
            case Bdd::TYPE_INT:
                if (1 == $ddl['precision']) {
                    return $value === '1';
                } else {
                    return (int)$value;
                }
            case Bdd::TYPE_BOOL:
                return (bool)$value;
            case Bdd::TYPE_FLOAT:
                return (float)$value;
            case Bdd::TYPE_STRING:
            case Bdd::TYPE_CLOB:
                return $value;
            case Bdd::TYPE_DATE:
                if (!$value) return null;
                $date = \DateTime::createFromFormat('Y-m-d', $value);
                $date->setTime(0, 0, 0);

                return $date;
            case Bdd::TYPE_BLOB:
                return $value;
            default:
                throw new \Exception("Type de donnée " . $ddl['type'] . " non géré.");
        }

        return $value;
    }
}