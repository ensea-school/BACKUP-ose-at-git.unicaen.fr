<?php

namespace BddAdmin;

use BddAdmin\Ddl\DdlIndexInterface;
use BddAdmin\Ddl\DdlInterface;
use BddAdmin\Ddl\DdlMaterializedViewInteface;
use BddAdmin\Ddl\DdlPackageInteface;
use BddAdmin\Ddl\DdlPrimaryConstraintInterface;
use BddAdmin\Ddl\DdlRefConstraintInterface;
use BddAdmin\Ddl\DdlSequenceInterface;
use BddAdmin\Ddl\DdlTableInterface;
use BddAdmin\Ddl\DdlTriggerInterface;
use BddAdmin\Ddl\DdlUniqueConstraintInterface;
use BddAdmin\Ddl\DdlViewInterface;
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
     * @var DdlInterface[]
     */
    private $ddlObjects = [];

    /**
     * @var SchemaLoggerInterface
     */
    public $logger;

    /**
     * @var bool
     */
    protected $queryCollect = false;

    /**
     * @var array
     */
    protected $queries = [];



    public function __construct(Bdd $bdd = null)
    {
        if ($bdd) {
            $this->setBdd($bdd);
        }
    }



    protected function object(string $name): DdlInterface
    {
        $ddlClass = $this->getBdd()->getDdlClass($name);

        if (!is_subclass_of($ddlClass, DdlInterface::class)) {
            throw new \Exception($ddlClass . ' n\'est pas un objet DDL valide!!');
        }

        if (!isset($this->ddlObjects[$ddlClass])) {
            $this->ddlObjects[$ddlClass] = new $ddlClass($this->getBdd());
        }

        return $this->ddlObjects[$ddlClass];
    }



    public function index(): DdlIndexInterface
    {
        return $this->object(Bdd::DDL_INDEX);
    }



    public function materializedView(): DdlMaterializedViewInteface
    {
        return $this->object(Bdd::DDL_MATERIALIZED_VIEW);
    }



    public function package(): DdlPackageInteface
    {
        return $this->object(Bdd::DDL_PACKAGE);
    }



    public function primaryConstraint(): DdlPrimaryConstraintInterface
    {
        return $this->object(Bdd::DDL_PRIMARY_CONSTRAINT);
    }



    public function refConstraint(): DdlRefConstraintInterface
    {
        return $this->object(Bdd::DDL_REF_CONSTRAINT);
    }



    public function sequence(): DdlSequenceInterface
    {
        return $this->object(Bdd::DDL_SEQUENCE);
    }



    public function table(): DdlTableInterface
    {
        return $this->object(Bdd::DDL_TABLE);
    }



    public function trigger(): DdlTriggerInterface
    {
        return $this->object(Bdd::DDL_TRIGGER);
    }



    public function uniqueConstraint(): DdlUniqueConstraintInterface
    {
        return $this->object(Bdd::DDL_UNIQUE_CONSTRAINT);
    }



    public function view(): DdlViewInterface
    {
        return $this->object(Bdd::DDL_VIEW);
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

        foreach ($this->ddlTypes as $type) {
            if (!array_key_exists($type, $ddlConfig)) {
                $ddlConfig[$type] = [];
            }
            $includes = isset($ddlConfig[$type]['includes']) ? $ddlConfig[$type]['includes'] : null;
            $excludes = isset($ddlConfig[$type]['excludes']) ? $ddlConfig[$type]['excludes'] : null;
            $options  = isset($ddlConfig[$type]['options']) ? $ddlConfig[$type]['options'] : [];

            if (empty($includes) && empty($excludes)) {
                if ($explicit) {
                    $excludes = '%'; // si pas défini, alors on exclue tout
                } else {
                    $includes = '%'; // si pas défini, alors on inclue tout
                }
            }
        }
        $ddlConfig[$type] = compact('includes', 'excludes', 'options');

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
            $this->object($type)->setOptions($ddlConfig['options']);
            $data[$type] = $this->object($type)->get($ddlConfig['includes'], $ddlConfig['excludes']);
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
        $ddlString = "<?php\n\n//@" . "formatter:off\n\nreturn " . $this->var_export54($ddl) . ";\n\n//@" . "formatter:on\n";

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
     */
    public function create($ddl)
    {
        $this->change('create', $ddl, []);
    }



    /**
     * @param array|Schema $ddl
     * @param array        $ddlConfig
     */
    public function alter($ddl, array $ddlConfig = [])
    {
        $this->change('alter', $ddl, $ddlConfig);
    }



    /**
     * @param array $ddlConfig
     */
    public function drop(array $ddlConfig = [])
    {
        $this->change('drop', [], $ddlConfig);
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

        $this->queryCollect = true;

        $res = [];
        foreach ($this->changements as $changement => $params) {
            [$ddlName, $action, $precision] = explode('.', $changement);
            $noGet = isset($ddlConfig[$ddlName]) ? ($ddlConfig[$ddlName] === false) : false;
            if (!$noGet) {
                $ddlObject = $this->object($ddlName);
                $ddlObject->addOptions($ddlConfig['options']);
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
                if (!empty($this->queries)) {
                    $res[$changement] = $this->queries;
                    $this->queries    = [];
                }
            }
        }

        $this->queryCollect = false;

        return $res;
    }



    private function change(string $mode, $ddl, array $ddlConfig = [])
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
        foreach ($this->changements as $changement => $params) {
            [$ddlName, $action, $precision] = explode('.', $changement);
            $noGet = isset($ddlConfig[$ddlName]) ? ($ddlConfig[$ddlName] === false) : false;
            if (!$noGet) {
                $ddlObject = $this->object($ddlName);

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
            }
        }

        if ($mode != 'drop') { // create ou alter
            if ($this->logger) {
                $this->logger->logTitle("\n" . 'Compilation de tous les objets de la BDD');
            }
            $this->compilerTout();
        }
    }



    /**
     * @param array $ddl
     */
    public function majSequences(array $ddl)
    {
        foreach ($ddl[Bdd::DDL_TABLE] as $tdata) {
            $this->table()->majSequence($tdata);
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

        $compileTypes = [Bdd::DDL_PACKAGE, Bdd::DDL_VIEW, Bdd::DDL_TRIGGER];
        foreach ($compileTypes as $compileType) {
            $object = $this->object($compileType);
            $list   = $object->getList();
            foreach ($list as $name) {
                try {
                    $object->compiler($name);
                } catch (BddCompileException $e) {
                    $errors[$compileType][$name] = $e->getMessage();
                    if ($this->logger) {
                        $this->logger->log($compileType . ' ' . $name . ' : Erreur de compilation');
                    }
                }
            }
        }

        return $errors;
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
            $objects = $this->object($ddlName)->get();
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



    public function queryExec(string $sql, string $description = null)
    {
        if ($this->queryCollect) {
            $this->queries[$sql] = $description;
        } else {
            $this->getBdd()->exec($sql);
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
}