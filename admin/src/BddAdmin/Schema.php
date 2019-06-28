<?php

namespace BddAdmin;

use BddAdmin\Ddl\DdlAbstract;
use BddAdmin\Ddl\DdlIndex;
use BddAdmin\Ddl\DdlMaterializedView;
use BddAdmin\Ddl\DdlPackage;
use BddAdmin\Ddl\DdlPrimaryConstraint;
use BddAdmin\Ddl\DdlRefConstraint;
use BddAdmin\Ddl\DdlSequence;
use BddAdmin\Ddl\DdlTable;
use BddAdmin\Ddl\DdlTrigger;
use BddAdmin\Ddl\DdlUniqueConstraint;
use BddAdmin\Ddl\DdlView;
use BddAdmin\Exception\BddCompileException;


class Schema
{
    use BddAwareTrait;

    /**
     * @var array
     */
    private $ddlClasses  = [
        DdlSequence::class,
        DdlTable::class,
        DdlPrimaryConstraint::class,
        DdlPackage::class,
        DdlView::class,
        DdlMaterializedView::class,
        DdlRefConstraint::class,
        DdlUniqueConstraint::class,
        DdlTrigger::class,
        DdlIndex::class,
    ];

    private $changements = [
        DdlSequence::class . '.rename.'          => ['label' => 'Renomage des séquences'],
        DdlTable::class . '.rename.'             => ['label' => 'Renomage des tables'],
        DdlView::class . '.rename.'              => ['label' => 'Renomage des vues'],
        DdlMaterializedView::class . '.rename.'  => ['label' => 'Renomage des vues matérialisées'],
        DdlPackage::class . '.rename.'           => ['label' => 'Renomage des packages'],
        DdlIndex::class . '.rename.'             => ['label' => 'Renomage des indexes'],
        DdlTrigger::class . '.rename.'           => ['label' => 'Renomage des triggers'],
        DdlPrimaryConstraint::class . '.rename.' => ['label' => 'Renomage des clés primaires'],
        DdlRefConstraint::class . '.rename.'     => ['label' => 'Renomage des clés étrangères'],
        DdlUniqueConstraint::class . '.rename.'  => ['label' => 'Renomage des contraintes d\'unicité'],

        DdlTrigger::class . '.drop.'           => ['label' => 'Suppression des triggers'],
        DdlSequence::class . '.drop.'          => ['label' => 'Suppression des séquences'],
        DdlView::class . '.drop.'              => ['label' => 'Suppression des vues'],
        DdlMaterializedView::class . '.drop.'  => ['label' => 'Suppression des vues matérialisées'],
        DdlPackage::class . '.drop.'           => ['label' => 'Suppression des packages'],
        DdlRefConstraint::class . '.drop.'     => ['label' => 'Suppression des clés étrangères'],
        DdlPrimaryConstraint::class . '.drop.' => ['label' => 'Suppression des clés primaires'],
        DdlUniqueConstraint::class . '.drop.'  => ['label' => 'Suppression des contraintes d\'unicité'],
        DdlIndex::class . '.drop.'             => ['label' => 'Suppression des indexes'],

        DdlSequence::class . '.create.'          => ['label' => 'Création des séquences'],
        DdlTable::class . '.create.'             => ['label' => 'Création des tables'],
        DdlView::class . '.create.'              => ['label' => 'Création des vues'],
        DdlPackage::class . '.create.definition' => ['label' => 'Création des définitions de packages'],
        DdlMaterializedView::class . '.create.'  => ['label' => 'Création des vues matérialisées'],
        DdlPackage::class . '.create.body'       => ['label' => 'Création des corps de packages'],
        DdlIndex::class . '.create.'             => ['label' => 'Création des indexes'],
        DdlPrimaryConstraint::class . '.create.' => ['label' => 'Création des clés primaires'],
        DdlRefConstraint::class . '.create.'     => ['label' => 'Création des clés étrangères'],
        DdlUniqueConstraint::class . '.create.'  => ['label' => 'Création des contraintes d\'unicité'],
        DdlTrigger::class . '.create.'           => ['label' => 'Création des triggers'],

        DdlSequence::class . '.alter.'                     => ['label' => 'Modification des séquences'],
        DdlTable::class . '.alter.noNotNull|noDropColumns' => ['label' => 'Modification des tables'],
        DdlPrimaryConstraint::class . '.alter.'            => ['label' => 'Modification des clés primaires'],
        DdlPackage::class . '.alter.'                      => ['label' => 'Modification des packages'],
        DdlView::class . '.alter.'                         => ['label' => 'Modification des vues'],
        DdlMaterializedView::class . '.alter.'             => ['label' => 'Modification des vues matérialisées'],
        DdlRefConstraint::class . '.alter.'                => ['label' => 'Modification des clés étrangères'],
        DdlUniqueConstraint::class . '.alter.'             => ['label' => 'Modification des contraintes d\'unicité'],
        DdlTrigger::class . '.alter.'                      => ['label' => 'Modification des triggers'],
        DdlIndex::class . '.alter.'                        => ['label' => 'Modification des indexes'],

        DdlTable::class . '.alter.noNotNull|noDropColumns' => ['label' => 'Modification des tables'],
        DdlTable::class . '.drop.'                         => ['label' => 'Suppression des tables'],
    ];

    /**
     * @var DdlAbstract[]
     */
    private $ddlObjects = [];

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
     * @param string $class
     *
     * @return DdlAbstract
     * @throws Exception
     */
    public function getDdlObject(string $class, $autoClear = false): DdlAbstract
    {
        if (!is_subclass_of($class, DdlAbstract::class)) {
            throw new \Exception($class . ' n\'est pas un objet DDL valide!!');
        }

        if (!isset($this->ddlObjects[$class])) {
            $this->ddlObjects[$class] = new $class($this->getBdd());
        }

        if ($autoClear) {
            $this->ddlObjects[$class]->clearQueries();
            $this->ddlObjects[$class]->clearOptions();
        }

        return $this->ddlObjects[$class];
    }



    /**
     * @param array  $ddlConfig
     * @param string $class
     * @param string $key
     *
     * @return mixed|null
     */
    protected function ddlConfigGet(array $ddlConfig, string $class, string $key = null)
    {
        if (array_key_exists($class::ALIAS, $ddlConfig)) {
            $ddlConfigClass = $ddlConfig[$class::ALIAS];
        } elseif (array_key_exists($class, $ddlConfig)) {
            $ddlConfigClass = $ddlConfig[$class];
        } else {
            $ddlConfigClass = [];
        }

        if (null === $key) {
            return $ddlConfigClass;
        }

        if (isset($ddlConfigClass[$key])) {
            return $ddlConfigClass[$key];
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
            foreach ($this->ddlClasses as $class) {
                if (empty($this->ddlConfigGet($ddlConfig, $class))) {
                    $ddlConfig[$class] = ['excludes' => '%']; // si pas défini, alors on exclue tout
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
        foreach ($this->ddlClasses as $class) {
            if ($this->ddlConfigGet($ddlConfig, $class) !== false) {
                $includes = $this->ddlConfigGet($ddlConfig, $class, 'includes');
                $excludes = $this->ddlConfigGet($ddlConfig, $class, 'excludes');
                $options  = $this->ddlConfigGet($ddlConfig, $class, 'options');
                if ($options) {
                    $this->getDdlObject($class)->setOptions($options);
                }
                $data[$class] = $this->getDdlObject($class)->get($includes, $excludes);
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
        foreach ($ddl as $ddlClass => $ddlConf) {
            if (empty($this->ddlConfigGet($ddlConfig, $ddlClass))){
                unset($ddl[$ddlClass]);
            }else {
                foreach ($ddlConf as $name => $config) {
                    if (!$this->ddlFilterObject($name, $this->ddlConfigGet($ddlConfig, $ddlClass))) {
                        unset($ddl[$ddlClass][$name]);
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



    /**
     * @param array  $ddl
     * @param string $filename
     */
    public function saveToFile(array $ddl, string $filename)
    {
        $ddlString = '<?php return ' . var_export($ddl, true) . ';';

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



    private function checkRenames(DdlAbstract $ddlObject, array $kold, array $knew)
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



    private function change(string $mode, $ddl, array $ddlConfig = [], $autoExecute = true): array
    {
        if ($ddl instanceof self) {
            $ddl = $ddl->getDdl($ddlConfig);
        } elseif($mode != 'create') {
            $ddl = $this->ddlFilter($ddl, $ddlConfig);
        }

        $ddlConfig = $this->prepareDdlConfig($ddlConfig);
        $res       = [];
        foreach ($this->changements as $changement => $params) {
            list($class, $action, $precision) = explode('.', $changement);
            $noGet = isset($ddlConfig[$class]) ? ($ddlConfig[$class] === false) : false;
            if (!$noGet) {
                $ddlObject = $this->getDdlObject($class, true);

                $includes = $this->ddlConfigGet($ddlConfig, $class, 'includes');
                $excludes = $this->ddlConfigGet($ddlConfig, $class, 'excludes');
                $options  = $this->ddlConfigGet($ddlConfig, $class, 'options');
                if ($options) {
                    $ddlObject->addOptions((array)$options);
                }

                $kold = [];
                $knew = [];
                if ($mode != 'create') { // alter ou drop
                    $kold = $ddlObject->get($includes, $excludes);
                }
                if ($mode != 'drop') { // create ou alter
                    $knew = isset($ddl[$class]) ? $ddl[$class] : [];
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
            if ($this->logger){
                $this->logger->logTitle('Compilation de tous les objets de la BDD');
            }
            if (empty($this->compilerTout()) && $this->logger){
                $this->logger->log('Compilation effectuée avec succès.');
            }
        }

        return $res;
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
        if (!isset($ddl[DdlTable::class])) {
            return [];
        }

        $ddlObject = $this->getDdlObject(DdlTable::class, true);
        foreach ($ddl[DdlTable::class] as $tdata) {
            $ddlObject->majSequence($tdata);
        }

        if ($ddlObject->getQueries()) {
            if ($autoExecute) {
                return [DdlTable::class . '.majSequences' => $ddlObject->execQueries()];
            } else {
                return [DdlTable::class . '.majSequences' => $ddlObject->getQueries()];
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
                        if ($onlyFirstLine && false !== strpos($qr,"\n")){
                            $qr = substr($qr,0,strpos($qr,"\n"));
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
        $sql     = "SELECT object_type, object_name FROM user_objects where object_type IN ('VIEW','PACKAGE','TRIGGER')";
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
                if($this->logger){
                    $this->logger->log($type.' '.$name.' : Erreur de compilation');
                }
            }
        }

        return $errors;
    }

}