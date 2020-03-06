<?php

namespace BddAdmin;

use BddAdmin\Ddl\Ddl;
use BddAdmin\Ddl\DdlDiff;
use BddAdmin\Ddl\Diff;
use BddAdmin\Ddl\DdlFilters;
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

    private $changements = [
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
        Ddl::TABLE . '.alter'               => 'Modification des tables',
        Ddl::INDEX . '.create'              => 'Création des indexes',
        Ddl::PRIMARY_CONSTRAINT . '.create' => 'Création des clés primaires',
        Ddl::REF_CONSTRAINT . '.create'     => 'Création des clés étrangères',
        Ddl::UNIQUE_CONSTRAINT . '.create'  => 'Création des contraintes d\'unicité',
        Ddl::TRIGGER . '.create'            => 'Création des triggers',
        Ddl::TABLE . '.drop'                => 'Suppression des tables',
    ];

    /**
     * @var ManagerInterface[]
     */
    private $managers = [];

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



    protected function manager(string $name): ManagerInterface
    {
        $ddlClass = $this->getBdd()->getDdlClass($name);

        if (!is_subclass_of($ddlClass, ManagerInterface::class)) {
            throw new \Exception($ddlClass . ' n\'est pas un objet DDL valide!!');
        }

        if (!isset($this->managers[$ddlClass])) {
            $this->managers[$ddlClass] = new $ddlClass($this->getBdd());
        }

        return $this->managers[$ddlClass];
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



    public function queryExec(string $sql, string $description = null)
    {
        if ($this->queryCollect) {
            $this->queries[$sql] = $description;
        } else {
            $this->getBdd()->exec($sql);
        }
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
     * @param Bdd|Schema|Ddl|array| $ddl
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
        $ddl = Ddl::normalize($ddl);

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
     * @param Bdd|Schema|Ddl|array $ddl
     * @param DdlFilters|array     $filters
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
                $manager = $this->manager($ddlName);
                $ddl     = $manager->get($filters->get($ddlName));
                if (!empty($ddl)) {
                    $queries = $this->alterDdlObject($manager, 'drop', $ddl, []);
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
    public function diff($ddl, $filters = [], bool $inverse = false): DdlDiff
    {
        $this->logBegin('Génération du différentiel de DDLs');
        if ($ddl instanceof Bdd) {
            $ddl = $ddl->getSchema();
        }
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
            $queries = $this->alterDdlObject($object, $action, $old[$ddlName], $new[$ddlName]);
            if (!empty($queries)) {
                $diff->set($changement, $queries);
            }
        }
        $this->logEnd();

        return $diff;
    }



    /**
     * @param Bdd|Schema|Ddl|array $ddl
     */
    public function majSequences($ddl)
    {
        $ddl = Ddl::normalize($ddl);

        $this->logBegin("Mise à jour de toutes les séquences");
        foreach ($ddl[Ddl::TABLE] as $tdata) {
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
}