<?php

namespace BddAdmin;

use BddAdmin\Ddl\Ddl;
use BddAdmin\Ddl\DdlDiff;
use BddAdmin\Ddl\DdlFilters;
use BddAdmin\Driver\DriverInterface;
use BddAdmin\Event\EventManagerAwareTrait;
use BddAdmin\Exception\BddCompileException;
use BddAdmin\Exception\BddException;
use BddAdmin\Exception\BddIndexExistsException;
use BddAdmin\Logger\LoggerAwareTrait;
use BddAdmin\Manager\IndexManagerInterface;
use BddAdmin\Manager\ManagerInterface;
use BddAdmin\Manager\MaterializedViewManagerInteface;
use BddAdmin\Manager\PackageManagerInteface;
use BddAdmin\Manager\PrimaryConstraintManagerInterface;
use BddAdmin\Manager\RefConstraintManagerInterface;
use BddAdmin\Manager\SequenceManagerInterface;
use BddAdmin\Manager\TableManagerInterface;
use BddAdmin\Manager\TriggerManagerInterface;
use BddAdmin\Manager\UniqueConstraintManagerInterface;
use BddAdmin\Manager\ViewManagerInterface;
use \Exception;
use Unicaen\OpenDocument\Data;

class Bdd
{
    use EventManagerAwareTrait;
    use LoggerAwareTrait;

    const FETCH_ALL  = 32;
    const FETCH_EACH = 16;
    const FETCH_ONE  = 8;

    const TYPE_INT    = 'int';
    const TYPE_BOOL   = 'bool';
    const TYPE_FLOAT  = 'float';
    const TYPE_STRING = 'string';
    const TYPE_DATE   = 'date';
    const TYPE_BLOB   = 'blob';
    const TYPE_CLOB   = 'clob';

    private array $ddlTypes    = [
        Ddl::SEQUENCE,
        Ddl::TABLE,
        Ddl::PRIMARY_CONSTRAINT,
        Ddl::PACKAGE,
        Ddl::VIEW,
        Ddl::MATERIALIZED_VIEW,
        Ddl::REF_CONSTRAINT,
        Ddl::UNIQUE_CONSTRAINT,
        Ddl::TRIGGER,
        Ddl::INDEX,
    ];

    private array $changements = [
        Ddl::SEQUENCE . '.rename'           => 'Renomage des séquences',
        Ddl::TABLE . '.rename'              => 'Renomage des tables',
        Ddl::VIEW . '.rename'               => 'Renomage des vues',
        Ddl::MATERIALIZED_VIEW . '.rename'  => 'Renomage des vues matérialisées',
        Ddl::PACKAGE . '.rename'            => 'Renomage des packages',
        Ddl::INDEX . '.rename'              => 'Renomage des indexes',
        Ddl::TRIGGER . '.rename'            => 'Renomage des triggers',
        Ddl::PRIMARY_CONSTRAINT . '.rename' => 'Renomage des clés primaires',
        Ddl::REF_CONSTRAINT . '.rename'     => 'Renomage des clés étrangères',
        Ddl::UNIQUE_CONSTRAINT . '.rename'  => 'Renomage des contraintes d\'unicité',
        Ddl::TRIGGER . '.drop'              => 'Suppression des triggers',
        Ddl::SEQUENCE . '.drop'             => 'Suppression des séquences',
        Ddl::VIEW . '.drop'                 => 'Suppression des vues',
        Ddl::MATERIALIZED_VIEW . '.drop'    => 'Suppression des vues matérialisées',
        Ddl::PACKAGE . '.drop'              => 'Suppression des packages',
        Ddl::REF_CONSTRAINT . '.drop'       => 'Suppression des clés étrangères',
        Ddl::PRIMARY_CONSTRAINT . '.drop'   => 'Suppression des clés primaires',
        Ddl::UNIQUE_CONSTRAINT . '.drop'    => 'Suppression des contraintes d\'unicité',
        Ddl::INDEX . '.drop'                => 'Suppression des indexes',
        Ddl::SEQUENCE . '.create'           => 'Création des séquences',
        Ddl::TABLE . '.create'              => 'Création des tables',
        Ddl::TABLE . '.alter'               => 'Modification des tables',
        Ddl::VIEW . '.create'               => 'Création des vues',
        Ddl::PACKAGE . '.create'            => 'Création des packages',
        Ddl::SEQUENCE . '.alter'            => 'Modification des séquences',
        Ddl::PACKAGE . '.alter'             => 'Modification des packages',
        Ddl::VIEW . '.alter'                => 'Modification des vues',
        Ddl::MATERIALIZED_VIEW . '.create'  => 'Création des vues matérialisées',
        Ddl::MATERIALIZED_VIEW . '.alter'   => 'Modification des vues matérialisées',
        Ddl::PRIMARY_CONSTRAINT . '.alter'  => 'Modification des clés primaires',
        Ddl::REF_CONSTRAINT . '.alter'      => 'Modification des clés étrangères',
        Ddl::UNIQUE_CONSTRAINT . '.alter'   => 'Modification des contraintes d\'unicité',
        Ddl::TRIGGER . '.alter'             => 'Modification des triggers',
        Ddl::INDEX . '.alter'               => 'Modification des indexes',
        Ddl::INDEX . '.create'              => 'Création des indexes',
        Ddl::PRIMARY_CONSTRAINT . '.create' => 'Création des clés primaires',
        Ddl::REF_CONSTRAINT . '.create'     => 'Création des clés étrangères',
        Ddl::UNIQUE_CONSTRAINT . '.create'  => 'Création des contraintes d\'unicité',
        Ddl::TRIGGER . '.create'            => 'Création des triggers',
        Ddl::TABLE . '.drop'                => 'Suppression des tables',
    ];

    /**
     * @var array
     */
    private $config;

    /**
     * @var DriverInterface
     */
    private     $driver;

    public bool $debug = false;

    /**
     * @var ManagerInterface[]
     */
    private             $managers     = [];

    protected bool      $queryCollect = false;

    protected array     $queries      = [];

    protected bool      $inCopy       = false;

    private DataUpdater $dataUpdater;

    private array       $options      = [];



    /**
     * Bdd constructor.
     *
     * @param string $host
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            $this->setConfig($config);
        }
    }



    /**
     * @return self
     */
    public function beginTransaction(): self
    {
        $this->driver->beginTransaction();

        return $this;
    }



    /**
     * @return $this
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function commitTransaction(): self
    {
        $this->driver->commitTransaction();

        return $this;
    }



    /**
     * @return $this
     */
    public function rollbackTransaction(): self
    {
        $this->driver->rollbackTransaction();

        return $this;
    }



    /**
     * @param string $sequenceName
     *
     * @return int
     */
    public function sequenceNextVal(string $sequenceName): int
    {
        $r = $this->select("SELECT $sequenceName.NEXTVAL seqval FROM DUAL");

        return (int)$r[0]['SEQVAL'];
    }



    /**
     * @param string $sql
     * @param array  $params
     *
     * @return bool
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function exec(string $sql, array $params = [], array $types = [])
    {
        if ($this->debug) {
            foreach ($params as $p => $v) {
                if (is_array($v)) {
                    $v = 'array';
                }
                $sql = str_replace(':' . $p, $v, $sql);
            }
            echo $sql . ";\n";
        } else {
            $this->driver->exec($sql, $params, $types);
        }

        return true;
    }



    public function queryLogExec(string $sql, string $description = null)
    {
        if ($this->queryCollect) {
            $this->queries[$sql] = $description;
        } else {
            $this->exec($sql);
        }
    }



    /**
     * @param string $sql
     * @param array  $params
     * @param array  $options
     *
     * @return array|null|SelectParser
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function select(string $sql, array $params = [], array $options = [])
    {
        //echo substr($sql, 0, 100) . "\n";

        return $this->driver->select($sql, $params, $options);
    }



    public function selectOne(string $sql, array $params = [], string $column = null): mixed
    {
        $res = $this->select($sql, $params, ['fetch' => self::FETCH_ONE]);

        if (empty($res)) return null;

        if (is_array($res) && !empty($column)){
            return $res[$column];
        }

        return $res;
    }



    /**
     * @param string $name
     *
     * @return Table
     */
    public function getTable(string $name): Table
    {
        $table = new Table($this, $name);

        return $table;
    }



    public function __destruct()
    {
        $this->driver->disconnect();
    }



    public function getConfig(): array
    {
        return $this->config;
    }



    public function setConfig(array $config): self
    {
        $this->config = $config;
        if ($this->driver) {
            $this->driver->disconnect();
        }
        $driverClass  = isset($config['driver']) ? $config['driver'] : 'Oracle';
        $driverClass  = "\BddAdmin\Driver\\$driverClass\Driver";
        $this->driver = new $driverClass($this);
        $this->driver->connect();

        return $this;
    }



    public function manager(string $name): ManagerInterface
    {
        $ddlClass = $this->driver->getDdlClass($name);

        if (!is_subclass_of($ddlClass, ManagerInterface::class)) {
            throw new \Exception($ddlClass . ' n\'est pas un objet DDL valide!!');
        }

        if (!isset($this->managers[$ddlClass])) {
            $this->managers[$ddlClass] = new $ddlClass($this);
        }

        return $this->managers[$ddlClass];
    }



    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }



    public function getOption(string $name)
    {
        if ($this->hasOption($name)) {
            return $this->options[$name];
        } else {
            return null;
        }
    }



    public function setOption(string $name, $value): self
    {
        $this->options[$name] = $value;

        return $this;
    }



    public function getOptions(): array
    {
        return $this->options;
    }



    public function setOptions(array $options, bool $clearOthers = false): self
    {
        if ($clearOthers) {
            $this->options = [];
        }
        foreach ($options as $name => $option) {
            $this->setOption($name, $option);
        }

        return $this;
    }



    public function dataUpdater(): DataUpdater
    {
        if (!isset($this->dataUpdater)) {
            $this->dataUpdater = new DataUpdater($this);
        }

        return $this->dataUpdater;
    }



    public function index(): IndexManagerInterface
    {
        return $this->manager(Ddl::INDEX);
    }



    public function materializedView(): MaterializedViewManagerInteface
    {
        return $this->manager(Ddl::MATERIALIZED_VIEW);
    }



    public function package(): PackageManagerInteface
    {
        return $this->manager(Ddl::PACKAGE);
    }



    public function primaryConstraint(): PrimaryConstraintManagerInterface
    {
        return $this->manager(Ddl::PRIMARY_CONSTRAINT);
    }



    public function refConstraint(): RefConstraintManagerInterface
    {
        return $this->manager(Ddl::REF_CONSTRAINT);
    }



    public function sequence(): SequenceManagerInterface
    {
        return $this->manager(Ddl::SEQUENCE);
    }



    public function table(): TableManagerInterface
    {
        return $this->manager(Ddl::TABLE);
    }



    public function trigger(): TriggerManagerInterface
    {
        return $this->manager(Ddl::TRIGGER);
    }



    public function uniqueConstraint(): UniqueConstraintManagerInterface
    {
        return $this->manager(Ddl::UNIQUE_CONSTRAINT);
    }



    public function view(): ViewManagerInterface
    {
        return $this->manager(Ddl::VIEW);
    }



    /**
     * @param DdlFilters|array|null $filters
     *
     * @return Ddl
     * @throws Exception
     */
    public function getDdl($filters = []): Ddl
    {
        $this->logBegin("Récupération de la DDL");
        $filters = DdlFilters::normalize($filters);
        $ddl     = new Ddl();
        foreach ($this->ddlTypes as $type) {
            if (!($filters->isExplicit() && $filters->get($type)->isEmpty())) {
                $this->logMsg('Traitement des objets de type ' . $type . ' ...', true);
                $ddl->set($type, $this->manager($type)->get($filters[$type]));
            }
        }
        $this->logEnd();

        return $ddl;
    }



    protected function alterDdlObject(ManagerInterface $manager, string $action, array $kold, array $knew): array
    {
        $this->queryCollect = true;
        $this->queries      = [];

        $renames = [];
        foreach ($kold as $koldIndex => $koldData) {
            if (isset($koldData['name'])) {
                $koldName = $koldData['name'];
                $koldData = $manager->prepareRenameCompare($koldData);
                unset($koldData['name']);
                foreach ($knew as $knewIndex => $knewData) {
                    if (isset($knewData['name'])) {
                        $knewName = $knewData['name'];
                        $knewData = $manager->prepareRenameCompare($knewData);
                        if ($koldName !== $knewName) {
                            if ($koldData == $knewData) {
                                $renames[$koldIndex] = $knew[$knewIndex];
                                unset($kold[$koldIndex]);
                                unset($knew[$knewName]);
                            }
                        }
                    }
                }
            }
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

        foreach ($names as $oldName => $name) {
            switch ($action) {
                case 'rename':
                    $manager->rename($oldName, $name);
                break;
                case 'drop':
                    $manager->drop($kold[$name]);
                break;
                case 'alter':
                    $manager->alter($kold[$name], $knew[$name]);
                break;
                case 'create':
                    $manager->create($knew[$name]);
                break;
            }
        }
        $this->queryCollect = false;

        return $this->queries;
    }



    /**
     * @param Bdd|Ddl|array| $ddl
     */
    public function create($ddl, $filters = [])
    {
        $this->logBegin('Mise en place de la base de données');
        $filters = DdlFilters::normalize($filters);
        if ($ddl instanceof self) {
            $ddl = $ddl->getDdl($filters);
        } else {
            $ddl = Ddl::normalize($ddl);
            $ddl->filter($filters);
        }

        foreach ($this->changements as $changement => $label) {
            [$ddlName, $action] = explode('.', $changement);
            if ($action == 'create') {
                $manager = $this->manager($ddlName);
                if (isset($ddl[$ddlName])) {
                    $queries = $this->alterDdlObject($manager, $action, [], $ddl[$ddlName]);
                    if ($queries) {
                        $this->logBegin($label);
                        foreach ($queries as $query => $desc) {
                            $this->logMsg($desc);
                            try {
                                $this->exec($query);
                            } catch (BddCompileException $e) {
                                // ne rien faire => trité après
                            } catch (\Throwable $e) {
                                $this->logError($e);
                            }
                        }
                        $this->logEnd();
                    }
                }
            }
        }
        $this->compilerTout();
        $this->logEnd("Base de données créée");
    }



    /**
     * @param Bdd|Ddl|array    $ddl
     * @param DdlFilters|array $filters
     */
    public function alter($ddl, $filters = [])
    {
        $this->logBegin('Application des changements sur la BDD');
        $filters = DdlFilters::normalize($filters);
        if ($ddl instanceof self) {
            if (!$ddl->getLogger() && $this->getLogger()) {
                $ddl->setLogger($this->getLogger());
            }
            $ddl = $ddl->getDdl($filters);
        } else {
            $ddl = Ddl::normalize($ddl)->filter($filters);
        }

        foreach ($this->changements as $changement => $label) {
            [$ddlName, $action] = explode('.', $changement);

            $manager      = $this->manager($ddlName);
            $objectFilter = $filters->get($ddlName);

            if (!($filters->isExplicit() && $objectFilter->isEmpty())) {
                $objectDdl = isset($ddl[$ddlName]) ? $ddl[$ddlName] : [];
                $this->logMsg("Préparation de l'action \"$label\" ...", true);
                $queries = $this->alterDdlObject($manager, $action, $manager->get($objectFilter), $objectDdl);
                if ($queries) {
                    $this->logBegin($label);
                    foreach ($queries as $query => $desc) {
                        $this->logMsg($desc);
                        try {
                            $this->exec($query);
                        } catch (BddCompileException $e) {
                            // ne rien faire => trité après
                        } catch (\Throwable $e) {
                            $this->logError($e);
                        }
                    }
                    $this->logEnd();
                }
            }
        }

        $this->compilerTout();
        $this->logEnd('Changements appliqués');
    }



    /**
     * @param DdlFilters|array $filters
     *
     * @throws \Exception
     */
    public function drop($filters = [])
    {
        $this->logBegin('Suppression de la base de données');
        $filters = DdlFilters::normalize($filters);

        foreach ($this->changements as $changement => $label) {
            [$ddlName, $action] = explode('.', $changement);

            if ($action == 'drop' && !($filters->isExplicit() && $filters->get($ddlName)->isEmpty())) {
                $manager = $this->manager($ddlName);
                $ddl     = $manager->get($filters->get($ddlName));
                if (!empty($ddl)) {
                    $queries = $this->alterDdlObject($manager, 'drop', $ddl, []);
                    if ($queries) {
                        $this->logBegin($label);
                        foreach ($queries as $query => $desc) {
                            $this->logMsg($desc);
                            try {
                                $this->exec($query);
                            } catch (\Throwable $e) {
                                $this->logError($e);
                            }
                        }
                        $this->logEnd();
                    }
                }
            }
        }
        $this->logEnd("Base de données vide");
    }



    /**
     * @param Bdd|Ddl|array    $ddl
     * @param DdlFilters|array $filters
     * @param bool             $inverse
     *
     * @return DdlDiff
     * @throws \Exception
     */
    public function diff($ddl, $filters = [], bool $inverse = false): DdlDiff
    {
        $this->logBegin('Génération du différentiel de DDLs');
        if ($ddl instanceof self) {
            $ddl = $ddl->getDdl($filters);
        } else {
            $ddl = Ddl::normalize($ddl)->filter($filters);
        }

        $bdd = $this->getDdl($filters);

        if (!$inverse) {
            $old = $bdd;
            $new = $ddl;
        } else {
            $old = $ddl;
            $new = $bdd;
        }
        $diff = new DdlDiff();
        $cc   = count($this->changements);
        $c    = 0;
        foreach ($this->changements as $changement => $label) {
            $c++;
            [$ddlName, $action] = explode('.', $changement);
            $this->logMsg($label . " (opération $c/$cc) ...", true);
            $object  = $this->manager($ddlName);
            $queries = $this->alterDdlObject($object, $action, $old[$ddlName] ?: [], $new[$ddlName] ?: []);
            if (!empty($queries)) {
                $diff->set($changement, $queries);
            }
        }
        $this->logEnd();

        return $diff;
    }



    /**
     * @param Bdd|Ddl|array    $src
     * @param Bdd|Ddl|array    $dest
     * @param DdlFilters|array $filters
     *
     * @return DdlDiff
     * @throws \Exception
     */
    public function diff2($src, $dest, $filters = []): DdlDiff
    {
        if ($src instanceof self) {
            $src = $src->getDdl($filters);
        } else {
            $src = Ddl::normalize($src)->filter($filters);
        }

        if ($dest instanceof self) {
            $dest = $dest->getDdl($filters);
        } else {
            $dest = Ddl::normalize($dest)->filter($filters);
        }

        $diff = new DdlDiff();
        $cc   = count($this->changements);
        $c    = 0;
        foreach ($this->changements as $changement => $label) {
            $c++;
            [$ddlName, $action] = explode('.', $changement);
            $object  = $this->manager($ddlName);
            $queries = $this->alterDdlObject($object, $action, $src[$ddlName] ?: [], $dest[$ddlName] ?: []);
            if (!empty($queries)) {
                $diff->set($changement, $queries);
            }
        }

        return $diff;
    }



    /**
     * @param Bdd|Ddl|array $ddl
     */
    public function majSequences($ddl = null)
    {
        if (!$ddl) {
            $ddl = $this->table()->get();
        } else {
            $ddl = Ddl::normalize($ddl)->get(Ddl::TABLE);
            if (!$ddl) $ddl = [];
        }

        $this->logBegin("Mise à jour de toutes les séquences");
        foreach ($ddl as $tdata) {
            try {
                $this->logMsg("Séquence " . $tdata['sequence'] . " ...", true);
                $this->table()->majSequence($tdata);
            } catch (\Throwable $e) {
                $this->logError($e);
            }
        }
        $this->logEnd();
    }



    public function refreshMaterializedViews($ddl = null)
    {
        if (!$ddl) {
            $ddl = $this->materializedView()->get();
        } else {
            $ddl = Ddl::normalize($ddl)->get(Ddl::MATERIALIZED_VIEW);
            if (!$ddl) $ddl = [];
        }

        $this->logBegin("Recalcul de toutes les vues matérialisées");
        foreach ($ddl as $mv) {
            try {
                $this->logMsg("Vue matérialisée " . $mv['name'] . " ...", true);
                $this->materializedView()->refresh($mv);
            } catch (\Throwable $e) {
                $this->logError($e);
            }
        }
        $this->logEnd();
    }



    /**
     * @return array
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function compilerTout(): array
    {
        $this->logBegin("Compilation de tous les objets de la BDD");
        $errors = [];

        $compileTypes = [Ddl::PACKAGE, Ddl::VIEW, Ddl::TRIGGER];
        foreach ($compileTypes as $compileType) {
            $manager = $this->manager($compileType);
            $list    = $manager->getList();
            foreach ($list as $name) {
                try {
                    $this->logMsg("Compilation de $name ...", true);
                    $manager->compiler($name);
                } catch (BddCompileException $e) {
                    $errors[$compileType][$name] = $e->getMessage();
                    $this->logError($compileType . ' ' . $name . ' : Erreur de compilation');
                }
            }
        }
        $this->logEnd("Fin de la compilation");

        return $errors;
    }



    /**
     * @return bool
     */
    public function isInCopy(): bool
    {
        return $this->inCopy;
    }



    public function copy(Bdd $source, array $filters = [], array $fncs = []): self
    {
        if ($this->getLogger() && !$source->getLogger()) {
            $source->setLogger($this->getLogger());
        }

        $this->logBegin("Duplication d'une base de données");

        $excludes = [];
        foreach ($fncs as $table => $fnc) {
            if (false === $fnc) {
                $excludes[] = $table;
            }
        }
        $tDdl = $source->table()->get(null, $excludes);

        $this->drop();
        $this->create([Ddl::TABLE => $tDdl]);
        $this->inCopy = true;

        $this->logBegin("Copie des données");
        $this->logMsg('');

        $tables = array_keys($tDdl);
        sort($tables);
        foreach ($tables as $table) {
            $fnc = isset($fncs[$table]) ? $fncs[$table] : null;
            if (false !== $fnc) {
                $this->getTable($table)->copy($source, $fnc);
            }
        }
        $this->logMsg('');
        $this->logEnd("Copie terminée");

        $this->inCopy = false;

        if (array_key_exists(Ddl::TABLE, $filters)) {
            $filters[Ddl::TABLE]['excludes'][] = '%';
        } else {
            $filters[Ddl::TABLE] = ['excludes' => '%'];
        }

        $this->create($source, $filters);

        $this->logEnd();

        return $this;
    }



    /**
     * @param string                $filename
     * @param DdlFilters|array|null $filters
     * @param array                 $fncs
     *
     * @throws Exception
     */
    public function save(string $filename, $filters = [], array $fncs = [])
    {
        $this->logBegin("Sauvegarde de la base de données");

        if (file_exists($filename)) {
            throw new Exception('Le fichier existe déjà, merci de le supprimer ou bien de trouver un nouveau nom');
        }

        if (!class_exists('ZipArchive')) {
            throw new Exception('Zip extension not loaded');
        }
        $archive = new \ZipArchive();
        $archive->open($filename, \ZipArchive::CREATE);

        foreach ($fncs as $table => $fnc) {
            if (false === $fnc) {
                if (!isset($filters['table']['excludes'])) {
                    $filters['table']['excludes'] = [];
                }
                $filters['table']['excludes'][] = $table;
            }
        }
        $ddl = $this->getDdl($filters);

        $archive->addFromString('bdd.ddl', $ddl->saveToString());

        $tables = array_keys($ddl->get(Ddl::TABLE));
        sort($tables);
        $tmpNames = [];
        foreach ($tables as $table) {
            $fnc = isset($fncs[$table]) ? $fncs[$table] : null;
            if (false !== $fnc) {
                $tmpname    = tempnam(sys_get_temp_dir(), uniqid());
                $tmpNames[] = $tmpname;
                $this->getTable($table)->save($tmpname, $fnc);
                if (file_exists($tmpname)) {
                    $archive->addFile($tmpname, $table . '.tbl');
                }
            }
        }

        $archive->close();
        foreach ($tmpNames as $tmpName) {
            if (file_exists($tmpName)) {
                unlink($tmpName);
            }
        }
        $this->logEnd();
    }



    public function load(string $filename, $filters = [], array $fncs = [])
    {
        $this->logBegin("Restauration de la base de données");

        if (!file_exists($filename)) {
            throw new \Exception("Le fichier $filename n\'existe pas ou bien il n'a pas été trouvé");
        }

        $tmpPath = tempnam(sys_get_temp_dir(), uniqid());
        unlink($tmpPath);

        $archive = new \ZipArchive();
        if (true !== $archive->open($filename)) {
            throw new Exception('La sauvegarde n\'est pas lisible');
        }
        $archive->extractTo($tmpPath);
        $archive->close();

        $ddl = new Ddl();
        $ddl->loadFromFile($tmpPath . '/bdd.ddl');
        $ddl->filter($filters);

        $tDdl = $ddl->get(Ddl::TABLE);

        $this->drop();
        $this->create([Ddl::TABLE => $tDdl]);

        $tables = array_keys($tDdl);

        sort($tables);
        $this->inCopy = true;
        foreach ($tables as $table) {
            if (file_exists($tmpPath . '/' . $table . '.tbl')) {
                $fnc = isset($fncs[$table]) ? $fncs[$table] : null;
                if (false !== $fnc) {
                    $this->getTable($table)->load($tmpPath . '/' . $table . '.tbl', $fnc);
                }
            }
        }
        $this->inCopy = false;
        $this->create($ddl, [Ddl::TABLE => ['excludes' => '%']]);

        $this->majSequences($ddl);

        $this->logEnd();

        $dir = opendir($tmpPath);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                unlink($tmpPath . '/' . $file);
            }
        }
        closedir($dir);
        rmdir($tmpPath);

        return $this;
    }
}