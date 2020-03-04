<?php

namespace BddAdmin;

use BddAdmin\Ddl\Filter\DdlFilters;
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
use BddAdmin\Logger\LoggerAwareTrait;


class Schema
{
    use BddAwareTrait;
    use EventManagerAwareTrait;
    use LoggerAwareTrait;

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
        Bdd::DDL_SEQUENCE . '.rename'           => 'Renomage des séquences',
        Bdd::DDL_TABLE . '.rename'              => 'Renomage des tables',
        Bdd::DDL_VIEW . '.rename'               => 'Renomage des vues',
        Bdd::DDL_MATERIALIZED_VIEW . '.rename'  => 'Renomage des vues matérialisées',
        Bdd::DDL_PACKAGE . '.rename'            => 'Renomage des packages',
        Bdd::DDL_INDEX . '.rename'              => 'Renomage des indexes',
        Bdd::DDL_TRIGGER . '.rename'            => 'Renomage des triggers',
        Bdd::DDL_PRIMARY_CONSTRAINT . '.rename' => 'Renomage des clés primaires',
        Bdd::DDL_REF_CONSTRAINT . '.rename'     => 'Renomage des clés étrangères',
        Bdd::DDL_UNIQUE_CONSTRAINT . '.rename'  => 'Renomage des contraintes d\'unicité',
        Bdd::DDL_TRIGGER . '.drop'              => 'Suppression des triggers',
        Bdd::DDL_SEQUENCE . '.drop'             => 'Suppression des séquences',
        Bdd::DDL_VIEW . '.drop'                 => 'Suppression des vues',
        Bdd::DDL_MATERIALIZED_VIEW . '.drop'    => 'Suppression des vues matérialisées',
        Bdd::DDL_PACKAGE . '.drop'              => 'Suppression des packages',
        Bdd::DDL_REF_CONSTRAINT . '.drop'       => 'Suppression des clés étrangères',
        Bdd::DDL_PRIMARY_CONSTRAINT . '.drop'   => 'Suppression des clés primaires',
        Bdd::DDL_UNIQUE_CONSTRAINT . '.drop'    => 'Suppression des contraintes d\'unicité',
        Bdd::DDL_INDEX . '.drop'                => 'Suppression des indexes',
        Bdd::DDL_SEQUENCE . '.create'           => 'Création des séquences',
        Bdd::DDL_TABLE . '.create'              => 'Création des tables',
        Bdd::DDL_VIEW . '.create'               => 'Création des vues',
        Bdd::DDL_PACKAGE . '.create'            => 'Création des packages',
        Bdd::DDL_SEQUENCE . '.alter'            => 'Modification des séquences',
        Bdd::DDL_PACKAGE . '.alter'             => 'Modification des packages',
        Bdd::DDL_VIEW . '.alter'                => 'Modification des vues',
        Bdd::DDL_MATERIALIZED_VIEW . '.create'  => 'Création des vues matérialisées',
        Bdd::DDL_MATERIALIZED_VIEW . '.alter'   => 'Modification des vues matérialisées',
        Bdd::DDL_PRIMARY_CONSTRAINT . '.alter'  => 'Modification des clés primaires',
        Bdd::DDL_REF_CONSTRAINT . '.alter'      => 'Modification des clés étrangères',
        Bdd::DDL_UNIQUE_CONSTRAINT . '.alter'   => 'Modification des contraintes d\'unicité',
        Bdd::DDL_TRIGGER . '.alter'             => 'Modification des triggers',
        Bdd::DDL_INDEX . '.alter'               => 'Modification des indexes',
        Bdd::DDL_TABLE . '.alter'               => 'Modification des tables',
        Bdd::DDL_INDEX . '.create'              => 'Création des indexes',
        Bdd::DDL_PRIMARY_CONSTRAINT . '.create' => 'Création des clés primaires',
        Bdd::DDL_REF_CONSTRAINT . '.create'     => 'Création des clés étrangères',
        Bdd::DDL_UNIQUE_CONSTRAINT . '.create'  => 'Création des contraintes d\'unicité',
        Bdd::DDL_TRIGGER . '.create'            => 'Création des triggers',
        Bdd::DDL_TABLE . '.drop'                => 'Suppression des tables',
    ];

    /**
     * @var DdlInterface[]
     */
    private $ddlObjects = [];

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
     * @param DdlFilters|array|null $filters
     *
     * @return array
     * @throws Exception
     */
    public function getDdl($filters = []): array
    {
        $this->logBegin("Récupération de la DDL");
        $filters = DdlFilters::normalize($filters);
        $data    = [];
        foreach ($this->ddlTypes as $type) {
            if (!($filters->isExplicit() && $filters->get($type)->isEmpty())) {
                $this->logMsg('Traitement des objets de type ' . $type . ' ...', true);
                $data[$type] = $this->object($type)->get($filters[$type]);
            }
        }
        $this->logEnd();

        return $data;
    }



    /**
     * @param array                        $ddl
     * @param DdlFilters|array|string|null $filters
     *
     * @return array
     */
    public function ddlFilter(array $ddl, $filters): array
    {
        $filters = DdlFilters::normalize($filters);

        foreach ($ddl as $ddlType => $ddlConf) {
            foreach ($ddlConf as $name => $config) {
                if (!$filters[$ddlType]->match($name)) {
                    unset($ddl[$ddlType][$name]);
                }
            }
        }

        return $ddl;
    }



    public function queryExec(string $sql, string $description = null)
    {
        if ($this->queryCollect) {
            $this->queries[$sql] = $description;
        } else {
            $this->getBdd()->exec($sql);
        }
    }



    protected function alterDdlObject(DdlInterface $object, string $action, array $kold, array $knew): array
    {
        $this->queryCollect = true;
        $this->queries      = [];

        $renames = [];
        foreach ($kold as $koldIndex => $koldData) {
            if (isset($koldData['name'])) {
                $koldName = $koldData['name'];
                $koldData = $object->prepareRenameCompare($koldData);
                unset($koldData['name']);
                foreach ($knew as $knewIndex => $knewData) {
                    if (isset($knewData['name'])) {
                        $knewName = $knewData['name'];
                        $knewData = $object->prepareRenameCompare($knewData);
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
                    $object->rename($oldName, $name);
                break;
                case 'drop':
                    $object->drop($kold[$name]);
                break;
                case 'alter':
                    $object->alter($kold[$name], $knew[$name]);
                break;
                case 'create':
                    $object->create($knew[$name]);
                break;
            }
        }
        $this->queryCollect = false;

        return $this->queries;
    }



    /**
     * @param array|Schema $ddl
     */
    public function create($ddl)
    {
        $this->logBegin('Mise en place de la base de données');
        if ($ddl instanceof Bdd) {
            $ddl = $ddl->getSchema();
        }
        if ($ddl instanceof Schema) {
            $ddl = $this->getDdl();
        }

        foreach ($this->changements as $changement => $label) {
            [$ddlName, $action] = explode('.', $changement);
            if ($action == 'create') {
                $object = $this->object($ddlName);
                if (isset($ddl[$ddlName])) {
                    $queries = $this->alterDdlObject($object, $action, [], $ddl[$ddlName]);
                    if ($queries) {
                        $this->logBegin($label);
                        foreach ($queries as $query => $desc) {
                            $this->logMsg($desc);
                            try {
                                $this->getBdd()->exec($query);
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
     * @param Bdd|Schema|array $ddl
     * @param DdlFilters|array $filters
     */
    public function alter($ddl, $filters = [])
    {
        $this->logBegin('Application des changements sur la BDD');
        $filters = DdlFilters::normalize($filters);
        if ($ddl instanceof Bdd) {
            $ddl = $ddl->getSchema();
        }
        if ($ddl instanceof self) {
            if (!$ddl->getLogger() && $this->getLogger()) {
                $ddl->setLogger($this->getLogger());
            }
            $ddl = $ddl->getDdl($filters);
        } else {
            $ddl = $this->ddlFilter($ddl, $filters);
        }

        foreach ($this->changements as $changement => $label) {
            [$ddlName, $action] = explode('.', $changement);

            $object       = $this->object($ddlName);
            $objectFilter = $filters->get($ddlName);

            if (!($filters->isExplicit() && $objectFilter->isEmpty())) {
                $objectDdl = isset($ddl[$ddlName]) ? $ddl[$ddlName] : [];
                $this->logMsg("Préparation de l'action \"$label\" ...", true);
                $queries = $this->alterDdlObject($object, $action, $object->get($objectFilter), $objectDdl);
                if ($queries) {
                    $this->logBegin($label);
                    foreach ($queries as $query => $desc) {
                        $this->logMsg($desc);
                        try {
                            $this->getBdd()->exec($query);
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
                $object = $this->object($ddlName);
                $ddl    = $object->get($filters->get($ddlName));
                if (!empty($ddl)) {
                    $queries = $this->alterDdlObject($object, 'drop', $ddl, []);
                    if ($queries) {
                        $this->logBegin($label);
                        foreach ($queries as $query => $desc) {
                            $this->logMsg($desc);
                            try {
                                $this->getBdd()->exec($query);
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
     * @param Bdd|Schema|array $ddl
     * @param DdlFilters|array $filters
     * @param bool             $inverse
     *
     * @return array
     * @throws \Exception
     */
    public function diff($ddl, $filters = [], bool $inverse = false): array
    {
        $this->logBegin('Génération du différentiel de DDLs');
        if ($ddl instanceof Bdd) {
            $ddl = $ddl->getSchema();
        }
        if ($ddl instanceof self) {
            $ddl = $ddl->getDdl($filters);
        } else {
            $ddl = $this->ddlFilter($ddl, $filters);
        }

        $bdd = $this->getDdl($filters);

        if (!$inverse) {
            $old = $bdd;
            $new = $ddl;
        } else {
            $old = $ddl;
            $new = $bdd;
        }
        $res = [];
        $cc  = count($this->changements);
        $c   = 0;
        foreach ($this->changements as $changement => $label) {
            $c++;
            [$ddlName, $action] = explode('.', $changement);
            $this->logMsg($label . " (opération $c/$cc) ...", true);
            $object  = $this->object($ddlName);
            $queries = $this->alterDdlObject($object, $action, $old[$ddlName], $new[$ddlName]);
            if (!empty($queries)) {
                $res[$changement] = $queries;
            }
        }
        $this->logEnd();

        return $res;
    }



    /**
     * @param array $ddl
     */
    public function majSequences(array $ddl)
    {
        $this->logBegin("Mise à jour de toutes les séquences");
        foreach ($ddl[Bdd::DDL_TABLE] as $tdata) {
            try {
                $this->logMsg("Séquence " . $tdata['sequence'] . " ...", true);
                $this->table()->majSequence($tdata);
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

        $compileTypes = [Bdd::DDL_PACKAGE, Bdd::DDL_VIEW, Bdd::DDL_TRIGGER];
        foreach ($compileTypes as $compileType) {
            $object = $this->object($compileType);
            $list   = $object->getList();
            foreach ($list as $name) {
                try {
                    $this->logMsg("Compilation de $name ...", true);
                    $object->compiler($name);
                } catch (BddCompileException $e) {
                    $errors[$compileType][$name] = $e->getMessage();
                    $this->logError($compileType . ' ' . $name . ' : Erreur de compilation');
                }
            }
        }
        $this->logEnd("Fin de la compilation");

        return $errors;
    }



    private function arrayExport($var, $indent = "")
    {
        switch (gettype($var)) {
            case "array":
                $indexed   = array_keys($var) === range(0, count($var) - 1);
                $r         = [];
                $maxKeyLen = 0;
                foreach ($var as $key => $value) {
                    $key    = $this->arrayExport($key);
                    $keyLen = strlen($key);
                    if ($keyLen > $maxKeyLen) $maxKeyLen = $keyLen;
                }
                foreach ($var as $key => $value) {
                    $key = $this->arrayExport($key);
                    $r[] = "$indent    "
                        . ($indexed ? "" : str_pad($key, $maxKeyLen, ' ') . " => ")
                        . $this->arrayExport($value, "$indent    ");
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
        $ddlString = "<?php\n\n//@" . "formatter:off\n\nreturn " . $this->arrayExport($ddl) . ";\n\n//@" . "formatter:on\n";

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
                    $label = $this->changements[$key];
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