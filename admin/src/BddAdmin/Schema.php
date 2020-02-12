<?php

namespace BddAdmin;

use BddAdmin\Ddl\DdlAbstract;
use BddAdmin\Ddl\DdlInterface;
use BddAdmin\Event\EventManagerAwareTrait;
use BddAdmin\Exception\BddCompileException;


class Schema
{
    use BddAwareTrait;
    use EventManagerAwareTrait;

    /**
     * @var array
     */
    private $ddlTypes    = [
        Bdd::DDL_SEQUENCE,
        Bdd::DDL_TABLE,
        Bdd::DDL_PRIMARY_CONSTRAINT,
        Bdd::DDL_PACKAGE,
        Bdd::DDL_VIEW,
        Bdd::DDL_MATERIALIZED_VIEW,
        Bdd::DDL_REF_CONSTRAINT,
        Bdd::DDL_UNIQUE_CONSTRAINT,
        Bdd::DDL_TRIGGER,
        Bdd::DDL_INDEX,
    ];

    private $changements = [
        Bdd::DDL_SEQUENCE . '.rename.'           => ['label' => 'Renomage des séquences'],
        Bdd::DDL_TABLE . '.rename.'              => ['label' => 'Renomage des tables'],
        Bdd::DDL_VIEW . '.rename.'               => ['label' => 'Renomage des vues'],
        Bdd::DDL_MATERIALIZED_VIEW . '.rename.'  => ['label' => 'Renomage des vues matérialisées'],
        Bdd::DDL_PACKAGE . '.rename.'            => ['label' => 'Renomage des packages'],
        Bdd::DDL_INDEX . '.rename.'              => ['label' => 'Renomage des indexes'],
        Bdd::DDL_TRIGGER . '.rename.'            => ['label' => 'Renomage des triggers'],
        Bdd::DDL_PRIMARY_CONSTRAINT . '.rename.' => ['label' => 'Renomage des clés primaires'],
        Bdd::DDL_REF_CONSTRAINT . '.rename.'     => ['label' => 'Renomage des clés étrangères'],
        Bdd::DDL_UNIQUE_CONSTRAINT . '.rename.'  => ['label' => 'Renomage des contraintes d\'unicité'],

        Bdd::DDL_TRIGGER . '.drop.'            => ['label' => 'Suppression des triggers'],
        Bdd::DDL_SEQUENCE . '.drop.'           => ['label' => 'Suppression des séquences'],
        Bdd::DDL_VIEW . '.drop.'               => ['label' => 'Suppression des vues'],
        Bdd::DDL_MATERIALIZED_VIEW . '.drop.'  => ['label' => 'Suppression des vues matérialisées'],
        Bdd::DDL_PACKAGE . '.drop.'            => ['label' => 'Suppression des packages'],
        Bdd::DDL_REF_CONSTRAINT . '.drop.'     => ['label' => 'Suppression des clés étrangères'],
        Bdd::DDL_PRIMARY_CONSTRAINT . '.drop.' => ['label' => 'Suppression des clés primaires'],
        Bdd::DDL_UNIQUE_CONSTRAINT . '.drop.'  => ['label' => 'Suppression des contraintes d\'unicité'],
        Bdd::DDL_INDEX . '.drop.'              => ['label' => 'Suppression des indexes'],

        Bdd::DDL_SEQUENCE . '.create.'          => ['label' => 'Création des séquences'],
        Bdd::DDL_TABLE . '.create.'             => ['label' => 'Création des tables'],
        Bdd::DDL_VIEW . '.create.'              => ['label' => 'Création des vues'],
        Bdd::DDL_PACKAGE . '.create.definition' => ['label' => 'Création des définitions de packages'],
        Bdd::DDL_PACKAGE . '.create.body'       => ['label' => 'Création des corps de packages'],

        Bdd::DDL_SEQUENCE . '.alter.'                     => ['label' => 'Modification des séquences'],
        Bdd::DDL_TABLE . '.alter.noNotNull|noDropColumns' => ['label' => 'Modification des tables'],

        Bdd::DDL_PACKAGE . '.alter.'                      => ['label' => 'Modification des packages'],
        Bdd::DDL_VIEW . '.alter.'                         => ['label' => 'Modification des vues'],
        Bdd::DDL_MATERIALIZED_VIEW . '.create.'           => ['label' => 'Création des vues matérialisées'],
        Bdd::DDL_MATERIALIZED_VIEW . '.alter.'            => ['label' => 'Modification des vues matérialisées'],
        Bdd::DDL_PRIMARY_CONSTRAINT . '.alter.'           => ['label' => 'Modification des clés primaires'],
        Bdd::DDL_REF_CONSTRAINT . '.alter.'               => ['label' => 'Modification des clés étrangères'],
        Bdd::DDL_UNIQUE_CONSTRAINT . '.alter.'            => ['label' => 'Modification des contraintes d\'unicité'],
        Bdd::DDL_TRIGGER . '.alter.'                      => ['label' => 'Modification des triggers'],
        Bdd::DDL_INDEX . '.alter.'                        => ['label' => 'Modification des indexes'],
        Bdd::DDL_TABLE . '.alter.noNotNull|noDropColumns' => ['label' => 'Modification des tables'],

        Bdd::DDL_INDEX . '.create.'              => ['label' => 'Création des indexes'],
        Bdd::DDL_PRIMARY_CONSTRAINT . '.create.' => ['label' => 'Création des clés primaires'],
        Bdd::DDL_REF_CONSTRAINT . '.create.'     => ['label' => 'Création des clés étrangères'],
        Bdd::DDL_UNIQUE_CONSTRAINT . '.create.'  => ['label' => 'Création des contraintes d\'unicité'],
        Bdd::DDL_TRIGGER . '.create.'            => ['label' => 'Création des triggers'],

        Bdd::DDL_TABLE . '.drop.' => ['label' => 'Suppression des tables'],
    ];

    /**
     * @var SchemaLoggerInterface
     */
    public $logger;



    public function __construct(Bdd $bdd = null)
    {
        if ($bdd) {
            $this->setBdd($bdd);
        }
    }



    /**
     * @param array  $ddlConfig
     * @param string $name
     * @param string $key
     *
     * @return mixed|null
     */
    protected function ddlConfigGet(array $ddlConfig, string $name, string $key = null)
    {
        if (array_key_exists($name, $ddlConfig)) {
            $ddlConf = $ddlConfig[$name];
        } else {
            $ddlConf = [];
        }

        if (null === $key) {
            return $ddlConf;
        }

        if (isset($ddlConf[$key])) {
            return $ddlConf[$key];
        } else {
            return [];
        }
    }



    private function prepareDdlConfig(array $ddlConfig): array
    {
        $explicit = false;
        if (isset($ddlConfig['explicit'])) {
            $explicit = (bool)$ddlConfig['explicit'];
            unset($ddlConfig['explicit']);
        }

        if ($explicit) {
            foreach ($this->ddlTypes as $type) {
                if (empty($this->ddlConfigGet($ddlConfig, $type))) {
                    $ddlConfig[$type] = ['excludes' => '%']; // si pas défini, alors on exclue tout
                }
            }
        } else {
            foreach ($this->ddlTypes as $type) {
                if (empty($this->ddlConfigGet($ddlConfig, $type))) {
                    $ddlConfig[$type] = ['includes' => '%']; // si pas défini, alors on inclue tout
                }
            }
        }

        return $ddlConfig;
    }



    /**
     * @param array $ddlConfig
     *
     * @return array
     * @throws Exception
     */
    public function getDdl(array $ddlConfig = []): array
    {
        $ddlConfig = $this->prepareDdlConfig($ddlConfig);
        $data      = [];
        foreach ($this->ddlTypes as $type) {
            if ($this->ddlConfigGet($ddlConfig, $type) !== false) {
                $includes = $this->ddlConfigGet($ddlConfig, $type, 'includes');
                $excludes = $this->ddlConfigGet($ddlConfig, $type, 'excludes');
                $options  = $this->ddlConfigGet($ddlConfig, $type, 'options');
                if ($options) {
                    $this->getBdd()->getDdl($type)->setOptions($options);
                }
                $data[$type] = $this->getBdd()->getDdl($type)->get($includes, $excludes);
            }
        }

        return $data;
    }



    /**
     * @param array $ddl
     * @param array $ddlConfig
     *
     * @return array
     */
    public function ddlFilter(array $ddl, array $ddlConfig): array
    {
        $ddlConfig = $this->prepareDdlConfig($ddlConfig);
        foreach ($ddl as $ddlType => $ddlConf) {
            if (empty($this->ddlConfigGet($ddlConfig, $ddlType))) {
                unset($ddl[$ddlType]);
            } else {
                foreach ($ddlConf as $name => $config) {
                    if (!$this->ddlFilterObject($name, $this->ddlConfigGet($ddlConfig, $ddlType))) {
                        unset($ddl[$ddlType][$name]);
                    }
                }
            }
        }

        return $ddl;
    }



    private function ddlFilterObject(string $name, array $filter): bool
    {
        if (isset($filter['excludes'])) {
            $excludes = (array)$filter['excludes'];
            foreach ($excludes as $exclude) {
                if (preg_match('/^' . str_replace('%', '.*', $exclude) . '$/', $name, $out)) {
                    return false;
                }
            }
        }

        if (isset($filter['includes'])) {
            $includes = (array)$filter['includes'];
            foreach ($includes as $include) {
                if (preg_match('/^' . str_replace('%', '.*', $include) . '$/', $name, $out)) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }



    private function var_export54($var, $indent = "")
    {
        switch (gettype($var)) {
            case "array":
                $indexed   = array_keys($var) === range(0, count($var) - 1);
                $r         = [];
                $maxKeyLen = 0;
                foreach ($var as $key => $value) {
                    $key    = $this->var_export54($key);
                    $keyLen = strlen($key);
                    if ($keyLen > $maxKeyLen) $maxKeyLen = $keyLen;
                }
                foreach ($var as $key => $value) {
                    $key = $this->var_export54($key);
                    $r[] = "$indent    "
                        . ($indexed ? "" : str_pad($key, $maxKeyLen, ' ') . " => ")
                        . $this->var_export54($value, "$indent    ");
                }

                return "[\n" . implode(",\n", $r) . ",\n" . $indent . "]";
            case "boolean":
                return $var ? "TRUE" : "FALSE";
            default:
                return var_export($var, true);
        }
    }



    /**
     * @param array  $ddl
     * @param string $filename
     */
    public function saveToFile(array $ddl, string $filename)
    {
        $ddlString = "<?php\n\n//@formatter:off\n\nreturn " . $this->var_export54($ddl) . ";\n\n//@formatter:on\n";

        file_put_contents($filename, $ddlString);
    }



    /**
     * @param string $filename
     *
     * @return array
     */
    public function loadFromFile(string $filename): array
    {
        return require_once $filename;
    }



    /**
     * @param array|Schema $ddl
     * @param bool         $autoExecute
     *
     * @return array
     */
    public function create($ddl, $autoExecute = true): array
    {
        return $this->change('create', $ddl, [], $autoExecute);
    }



    /**
     * @param array|Schema $ddl
     * @param array        $ddlConfig
     * @param bool         $autoExecute
     *
     * @return array
     */
    public function alter($ddl, array $ddlConfig = [], $autoExecute = true): array
    {
        return $this->change('alter', $ddl, $ddlConfig, $autoExecute);
    }



    /**
     * @param array $ddlConfig
     * @param bool  $autoExecute
     *
     * @return array
     */
    public function drop(array $ddlConfig = [], $autoExecute = true): array
    {
        return $this->change('drop', [], $ddlConfig, $autoExecute);
    }



    private function checkRenames(DdlInterface $ddlObject, array $kold, array $knew)
    {
        $renames = [];
        foreach ($kold as $koldIndex => $koldData) {
            if (isset($koldData['name'])) {
                $koldName = $koldData['name'];
                $koldData = $ddlObject->prepareRenameCompare($koldData);
                unset($koldData['name']);
                foreach ($knew as $knewIndex => $knewData) {
                    if (isset($knewData['name'])) {
                        $knewName = $knewData['name'];
                        $knewData = $ddlObject->prepareRenameCompare($knewData);
                        if ($koldName !== $knewName) {
                            if ($koldData == $knewData) {
                                $renames[$koldIndex] = $knew[$knewIndex];
                            }
                        }
                    }
                }
            }
        }

        return $renames;
    }



    public function diff(array $ddl, bool $inverse = false, array $ddlConfig = []): array
    {
        $ddl = $this->ddlFilter($ddl, $ddlConfig);
        $bdd = $this->getDdl($ddlConfig);

        if (!$inverse) {
            $old = $bdd;
            $new = $ddl;
        } else {
            $old = $ddl;
            $new = $bdd;
        }

        $res = [];
        foreach ($this->changements as $changement => $params) {
            [$ddlName, $action, $precision] = explode('.', $changement);
            $noGet = isset($ddlConfig[$ddlName]) ? ($ddlConfig[$ddlName] === false) : false;
            if (!$noGet) {
                $ddlObject = $this->getBdd()->getDdl($ddlName, true);

                $options = $this->ddlConfigGet($ddlConfig, $ddlName, 'options');
                if ($options) {
                    $ddlObject->addOptions((array)$options);
                }

                $kold = isset($old[$ddlName]) ? $old[$ddlName] : [];
                $knew = isset($new[$ddlName]) ? $new[$ddlName] : [];

                $renames = $this->checkRenames($ddlObject, $kold, $knew);
                foreach ($renames as $koldName => $knewData) {
                    unset($kold[$koldName]);
                    unset($knew[$knewData['name']]);
                }
                switch ($action) {
                    case 'rename':
                        $names = $renames;
                    break;
                    case 'create':
                        $names = array_diff(array_keys($knew), array_keys($kold));
                    break;
                    case 'alter':
                        $names = array_intersect(array_keys($kold), array_keys($knew));
                    break;
                    case 'drop':
                        $names = array_diff(array_keys($kold), array_keys($knew));
                    break;
                }

                if ($precision) {
                    $precisions = explode('|', $precision);
                    foreach ($precisions as $precision) {
                        $ddlObject->addOption($precision);
                    }
                }
                foreach ($names as $oldName => $name) {
                    switch ($action) {
                        case 'rename':
                            $ddlObject->rename($oldName, $name);
                        break;
                        case 'drop':
                            if (!(isset($options['noDrop']) && $options['noDrop'])) {
                                $ddlObject->drop($name);
                            }
                        break;
                        case 'alter':
                            $ddlObject->alter($kold[$name], $knew[$name]);
                        break;
                        case 'create':
                            $ddlObject->create($knew[$name]);
                        break;
                    }
                }
                if ($ddlObject->getQueries()) {
                    $res[$changement] = $ddlObject->getQueries($this->logger);
                }
            }
        }

        return $res;
    }



    private function change(string $mode, $ddl, array $ddlConfig = [], $autoExecute = true): array
    {
        if ($ddl instanceof self) {
            $ddl = $ddl->getDdl($ddlConfig);
        } elseif ($mode != 'create') {
            $ddl = $this->ddlFilter($ddl, $ddlConfig);
        }

        $ddlConfig = $this->prepareDdlConfig($ddlConfig);
        if (isset($ddlConfig['include-tables-deps']) && $ddlConfig['include-tables-deps']) {
            unset($ddlConfig['include-tables-deps']);
            $this->filterIncludeTableDeps($ddl, $ddlConfig);
        }
        $res = [];
        foreach ($this->changements as $changement => $params) {
            [$ddlName, $action, $precision] = explode('.', $changement);
            $noGet = isset($ddlConfig[$ddlName]) ? ($ddlConfig[$ddlName] === false) : false;
            if (!$noGet) {
                $ddlObject = $this->getBdd()->getDdl($ddlName, true);

                $includes = $this->ddlConfigGet($ddlConfig, $ddlName, 'includes');
                $excludes = $this->ddlConfigGet($ddlConfig, $ddlName, 'excludes');
                $options  = $this->ddlConfigGet($ddlConfig, $ddlName, 'options');
                if ($options) {
                    $ddlObject->addOptions((array)$options);
                }

                $kold = [];
                $knew = [];
                if ($mode != 'create') { // alter ou drop
                    $kold = $ddlObject->get($includes, $excludes);
                }
                if ($mode != 'drop') { // create ou alter
                    $knew = isset($ddl[$ddlName]) ? $ddl[$ddlName] : [];
                }

                $renames = $this->checkRenames($ddlObject, $kold, $knew);
                foreach ($renames as $koldName => $knewData) {
                    unset($kold[$koldName]);
                    unset($knew[$knewData['name']]);
                }
                switch ($action) {
                    case 'rename':
                        $names = $renames;
                    break;
                    case 'create':
                        $names = array_diff(array_keys($knew), array_keys($kold));
                    break;
                    case 'alter':
                        $names = array_intersect(array_keys($kold), array_keys($knew));
                    break;
                    case 'drop':
                        $names = array_diff(array_keys($kold), array_keys($knew));
                    break;
                }

                if ($precision) {
                    $precisions = explode('|', $precision);
                    foreach ($precisions as $precision) {
                        $ddlObject->addOption($precision);
                    }
                }
                foreach ($names as $oldName => $name) {
                    switch ($action) {
                        case 'rename':
                            $ddlObject->rename($oldName, $name);
                        break;
                        case 'drop':
                            if (!(isset($options['noDrop']) && $options['noDrop'])) {
                                $ddlObject->drop($name);
                            }
                        break;
                        case 'alter':
                            $ddlObject->alter($kold[$name], $knew[$name]);
                        break;
                        case 'create':
                            $ddlObject->create($knew[$name]);
                        break;
                    }
                }
                if ($ddlObject->getQueries()) {
                    if ($autoExecute) {
                        $res[$changement] = $ddlObject->execQueries($this->logger);
                    } else {
                        $res[$changement] = $ddlObject->getQueries($this->logger);
                    }
                }
            }
        }

        if ($mode != 'drop' && $autoExecute) { // create ou alter
            if ($this->logger) {
                $this->logger->logTitle("\n" . 'Compilation de tous les objets de la BDD');
            }
            if (empty($this->compilerTout()) && $this->logger) {
                //$this->logger->log('Compilation effectuée avec succès.');
            }
        }

        return $res;
    }



    /**
     * On ajoute, en fonction de la liste des tables fournie en includes, tous les objets dépendants des tables en question.
     * Classes parsées :
     *  - DdlIndex::class,
     *  - DdlPrimaryConstraint::class,
     *  - DdlUniqueConstraint::class,
     *  - DdlRefConstraint::class,
     *
     * @param array $ddl
     * @param array $ddlConfig
     */
    private function filterIncludeTableDeps(array $ddl, array &$ddlConfig)
    {
        $tables = array_unique(isset($ddlConfig[Bdd::DDL_TABLE]['includes']) ? $ddlConfig[Bdd::DDL_TABLE]['includes'] : []);

        $ddlDeps = [
            Bdd::DDL_INDEX,
            Bdd::DDL_PRIMARY_CONSTRAINT,
            Bdd::DDL_UNIQUE_CONSTRAINT,
            Bdd::DDL_REF_CONSTRAINT,
        ];

        foreach ($ddlDeps as $ddlName) {
            if (!isset($ddlConfig[$ddlName]['includes'])) {
                $ddlConfig[$ddlName]['includes'] = [];
            }
            $objects = $this->getBdd()->getDdl($ddlName)->get();
            foreach ($objects as $name => $def) {
                if (in_array($def['table'], $tables) && !in_array($name, $ddlConfig[$ddlName]['includes'])) {
                    $ddlConfig[$ddlName]['includes'][] = $name;
                }
            }
        }
    }



    /**
     * @return SchemaLoggerInterface
     */
    public function getLogger() //: ?SchemaLoggerInterface
    {
        return $this->logger;
    }



    /**
     * @param SchemaLoggerInterface $logger
     *
     * @return Schema
     */
    public function setLogger($logger): Schema // (?SchemaLoggerInterface $logger): Schema
    {
        $this->logger = $logger;

        return $this;
    }



    /**
     * @param array $ddl
     * @param bool  $autoExecute
     *
     * @return array
     */
    public function majSequences(array $ddl, $autoExecute = true): array
    {
        if (!isset($ddl[Bdd::DDL_TABLE])) {
            return [];
        }

        $ddlObject = $this->getBdd()->getDdl(Bdd::DDL_TABLE, true);
        foreach ($ddl[Bdd::DDL_TABLE] as $tdata) {
            $ddlObject->majSequence($tdata);
        }

        if ($ddlObject->getQueries()) {
            if ($autoExecute) {
                return [Bdd::DDL_TABLE . '.majSequences' => $ddlObject->execQueries()];
            } else {
                return [Bdd::DDL_TABLE . '.majSequences' => $ddlObject->getQueries($this->logger)];
            }
        }
    }



    /**
     * @param array $queries
     * @param null  $title
     * @param bool  $reduce
     *
     * @return string
     */
    public function queriesToSql(array $queries, $title = null, $onlyFirstLine = false) // (array $queries, ?string $title = null)
    {
        $sql = '';
        if ($title) {
            $sql .= '--------------------------------------------------' . "\n";
            $sql .= '-- ' . "$title\n";
            $sql .= '--------------------------------------------------' . "\n";
            $sql .= "\n\n";
            $sql .= 'SET DEFINE OFF;' . "\n";
            $sql .= "\n\n";
        }
        if (empty($queries)) {
            $sql .= "-- Aucune requête à exécuter.";
        } else {
            foreach ($queries as $key => $qs) {
                if (array_key_exists($key, $this->changements) && $this->changements[$key]) {
                    $label = $this->changements[$key]['label'];
                } else {
                    $label = $key;
                }
                if (!empty($qs)) {
                    $sql .= '--------------------------------------------------' . "\n";
                    $sql .= '-- ' . $label . "\n";
                    $sql .= '--------------------------------------------------' . "\n\n";
                    foreach ($qs as $qr => $description) {
                        $qr = str_replace("\t", "  ", $qr);
                        if ($onlyFirstLine && false !== strpos($qr, "\n")) {
                            $qr = substr($qr, 0, strpos($qr, "\n"));
                        }

                        if (substr(trim($qr), -1) != ';') {
                            $qr .= ';';
                        }
                        $sql .= "$qr\n/\n\n";
                    }
                    $sql .= "\n\n\n";
                }
            }
        }

        return $sql;
    }



    public function dumpErrors(array $errors)
    {
        if (empty($errors)) {
            echo "<h2>L'opération s'est bien déroulée.</h2>";
        } else {
            foreach ($errors as $key => $errs) {
                if (!empty($errs)) {
                    echo "<h2>$key</h2>";
                    foreach ($errs as $name => $qr) {
                        if ($qr instanceof \Exception) {
                            $qrl = explode("\n", $qr);
                            echo "<span style='color:red'>" . $qrl[0] . "</span><br />";
                            echo "<pre>" . $qr->getMessage() . "</pre>";
                        } else {
                            echo "$qr<br />";
                        }
                    }
                }
            }
        }
    }



    /**
     * @return array
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function compilerTout(): array
    {
        $errors = [];

        $bdd     = $this->getBdd();
        $sql     = "SELECT OBJECT_TYPE, OBJECT_NAME FROM USER_OBJECTS WHERE OBJECT_TYPE IN ('VIEW','PACKAGE','TRIGGER')";
        $objects = $bdd->select($sql);
        foreach ($objects as $object) {
            $type = $object['OBJECT_TYPE'];
            $name = $object['OBJECT_NAME'];
            try {
                switch ($type) {
                    case 'PACKAGE':
                        $bdd->exec("ALTER PACKAGE $name COMPILE PACKAGE");
                        $bdd->exec("ALTER PACKAGE $name COMPILE BODY");
                    break;
                    case 'VIEW':
                        $bdd->exec("ALTER VIEW $name COMPILE");
                    break;
                    case 'TRIGGER':
                        $bdd->exec("ALTER TRIGGER $name COMPILE");
                    break;
                }
            } catch (BddCompileException $e) {
                $errors[$type][$name] = $e->getMessage();
                if ($this->logger) {
                    $this->logger->log($type . ' ' . $name . ' : Erreur de compilation');
                }
            }
        }

        return $errors;
    }

}