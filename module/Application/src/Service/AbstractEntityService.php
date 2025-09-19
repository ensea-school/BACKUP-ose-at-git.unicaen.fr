<?php

namespace Application\Service;

use Intervenant\Entity\Db\Statut;
use Doctrine\ORM\EntityRepository;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Hydrator\ObjectPropertyHydrator;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\Structure;
use UnicaenApp\Exception\RuntimeException;
use Doctrine\ORM\Query\Expr;
use UnicaenApp\Entity\HistoriqueAwareInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
abstract class AbstractEntityService extends AbstractService
{
    private ?EntityRepository $repo            = null;

    private ?\ReflectionClass $reflectionClass = null;

    private ?bool             $hasHistorique   = null;

    /**
     * Liste des propriétés des entités
     *
     * @var string[]
     */
    private array $properties = [];



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    abstract public function getEntityClass();



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    abstract public function getAlias();



    /**
     * @return \ReflectionClass
     */
    private function getReflectionClass()
    {
        if (!$this->reflectionClass) {
            $this->reflectionClass = new \ReflectionClass($this->getEntityClass());
        }

        return $this->reflectionClass;
    }



    public function getRepo(): EntityRepository
    {
        if (!$this->repo) {
            $this->repo = $this->getEntityManager()->getRepository($this->getEntityClass());
        }

        return $this->repo;
    }



    /**
     * Détermine si les entités gèrent les historiques ou non
     */
    public function hasHistorique(): bool
    {
        if (null === $this->hasHistorique) {
            $this->hasHistorique = $this->getReflectionClass()->implementsInterface('UnicaenApp\Entity\HistoriqueAwareInterface');
        }

        return $this->hasHistorique;
    }



    /**
     * Retourne la liste des propriétés filtrables de l'entité
     *
     * @return string[]
     */
    public function getProperties(): array
    {
        if (empty($this->properties)) {
            $m = $this->getReflectionClass()->getMethods(\ReflectionMethod::IS_PUBLIC);
            $p = $this->getReflectionClass()->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED);

            $methods = [];
            foreach ($m as $method) {
                if (0 === strpos($method->name, 'get')) $methods[] = $method->name;
            }

            $this->properties = [];
            foreach ($p as $property) {
                if (in_array('get' . ucfirst($property->name), $methods)) {
                    $this->properties[] = $property->name;
                }
            }
        }

        return $this->properties;
    }



    /**
     * Initialise une requête
     * Permet de retourner des valeurs par défaut ou de les forcer en cas de besoin
     * Format de sortie : array( $qb, $alias ).
     *
     * @param QueryBuilder|null $qb     Générateur de requêtes
     * @param string|null       $alias  Alias d'entité
     * @param array             $fields liste des champs à retourner
     *
     * @return array
     */
    public function initQuery(?QueryBuilder $qb = null, $alias = null, array $fields = []): array
    {
        if (null === $alias) $alias = $this->getAlias();
        if (empty($qb)) {
            $qb = $this->getEntityManager()->createQueryBuilder();

            if (empty($fields)) {
                $qb->select($alias);
            } else {
                $qb->select('partial ' . $alias . '.{' . implode(', ', $fields) . '}');
            }

            $qb->from($this->getEntityClass(), $alias);
        }


        return [$qb, $alias];
    }



    public function select(array $fields = []): QueryBuilder
    {
        return $this->initQuery(null, null, $fields)[0];
    }



    /**
     *
     * @param \Application\Service\AbstractEntityService|string $service
     * @param \Doctrine\ORM\QueryBuilder                        $qb
     * @param string                                            $relation
     * @param boolean                                           $addSelect
     * @param string                                            $leftAlias
     * @param string                                            $rightAlias
     *
     * @return self
     */
    public function join($service, QueryBuilder $qb, $relation, $addSelect = false, $leftAlias = null, $rightAlias = null): self
    {
        return $this->_join('join', $service, $qb, $relation, $addSelect, $leftAlias, $rightAlias);
    }



    /**
     *
     * @param \Application\Service\AbstractEntityService|string $service
     * @param \Doctrine\ORM\QueryBuilder                        $qb
     * @param string                                            $relation
     * @param boolean                                           $addSelect
     * @param string                                            $leftAlias
     * @param string                                            $rightAlias
     *
     * @return self
     */
    public function leftJoin($service, QueryBuilder $qb, $relation, $addSelect = false, $leftAlias = null, $rightAlias = null): self
    {
        return $this->_join('leftJoin', $service, $qb, $relation, $addSelect, $leftAlias, $rightAlias);
    }



    /**
     *
     * @param \Application\Service\AbstractEntityService|string $service
     * @param \Doctrine\ORM\QueryBuilder                        $qb
     * @param string                                            $relation
     * @param boolean|array|null                                $addSelect
     * @param string                                            $leftAlias
     * @param string                                            $rightAlias
     *
     * @return self
     */
    private function _join(string $method, $service, QueryBuilder $qb, $relation, $addSelect = false, $leftAlias = null, $rightAlias = null): self
    {
        if (is_string($service)) {
            $service = \Framework\Application\Application::getInstance()->container()->get($service);
            if (!$service instanceof AbstractEntityService) {
                throw new \LogicException('Le service transmis n\'est pas compatible.');
            }
        }

        if (null === $leftAlias) $leftAlias = $this->getAlias();
        if (null === $rightAlias) $rightAlias = $service->getAlias();

        if (in_array($rightAlias, $this->getQbFromAliases($qb))) {
            return $this; // Prévention de conflits de jointures
        }

        $qb->$method($leftAlias . '.' . $relation, $rightAlias);
        if (true === $addSelect) {
            $qb->addSelect($rightAlias);
        } elseif (is_array($addSelect)) {
            //$qb->addSelect( $rightAlias );
            $qb->addSelect('partial ' . $rightAlias . '.{' . implode(', ', $addSelect) . '}');
        }

        return $this;
    }



    /**
     *
     * @param QueryBuilder $qb
     *
     * @return string[]
     */
    public function getQbFromAliases(QueryBuilder $qb): array
    {
        $aliases = [];
        $from    = $qb->getDQLPart('from');
        $join    = $qb->getDQLPart('join');

        foreach ($from as $ef) {
            /* @var $ef Expr\From */
            $aliases[] = $ef->getAlias();
        }

        $this->getJoinExprs($join, $aliases);

        return $aliases;
    }



    private function getJoinExprs($joinPart, &$aliases)
    {
        if ($joinPart instanceof Expr\Join) {
            /* @var $joinPart Expr\Join */
            $aliases[] = $joinPart->getAlias();
        } elseif (is_array($joinPart)) {
            foreach ($joinPart as $jp) {
                $this->getJoinExprs($jp, $aliases);
            }
        }
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        /* ne fait rien ici!! */

        return $qb;
    }



    /**
     * Retourne une liste d'entités en fonction du QueryBuilder donné
     *
     * La liste de présente sous la forme d'un tableau associatif, dont les clés sont les ID des entités et les valeurs les
     * entités elles-mêmes
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return Statut[]|array
     */
    public function getList(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $this->orderBy($qb);
        $entities    = $qb->getQuery()->execute();
        $result      = [];
        $entityClass = $this->getEntityClass();
        foreach ($entities as $entity) {
            if ($entity instanceof $entityClass) {
                $result[$entity->getId()] = $entity;
            }
        }

        return $result;
    }



    /**
     * Retourne le nombre d'entités trouvé
     */
    public function count(?QueryBuilder $qb = null, ?string $alias = null): int
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $entities = $qb->getQuery()->execute();

        return count($entities);
    }



    /**
     * Retourne une entité à partir de son identifiant unique (ID)
     * Retourne null si l'identifiant est null ou bien s'il est égal à 0
     *
     * @param integer|integer[] $id
     *
     * @return mixed|null
     */
    public function get($id, $autoClear = false)
    {
        if ($autoClear) {
            $this->getRepo()->clear();
        }
        if (is_array($id)) {
            [$qb, $alias] = $this->initQuery();
            foreach ($id as $idi) {
                $qb->orWhere($alias . '.id = ' . (int)$idi);
            }

            return $this->getList($qb);
        } elseif ((int)$id && (int)$id != 0) {
            return $this->getRepo()->find((int)$id);
        } else {
            return null;
        }
    }



    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param mixed $entity Entité à détruire
     * @param bool  $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        $entityClass        = get_class($entity);
        $serviceEntityClass = $this->getEntityClass();
        if ($serviceEntityClass != $entityClass && !is_subclass_of($entity, $serviceEntityClass)) {
            throw new \RuntimeException('L\'entité transmise n\'est pas de la classe ' . $serviceEntityClass . '.');
        }
        if ($softDelete && $entity instanceof HistoriqueAwareInterface) {
            $entity->historiser($this->getServiceContext()->getUtilisateur());
        } else {
            $this->getEntityManager()->remove($entity);
        }
        $this->getEntityManager()->flush($entity);

        return $this;
    }



    /**
     * Sauvegarde une entité
     *
     * @param mixed $entity
     *
     * @return mixed
     * @throws \RuntimeException
     */
    public function save($entity)
    {
        $entityClass        = get_class($entity);
        $serviceEntityClass = $this->getEntityClass();
        if ($serviceEntityClass != $entityClass && !is_subclass_of($entity, $serviceEntityClass)) {
            throw new \RuntimeException('L\'entité transmise n\'est pas de la classe ' . $serviceEntityClass . '.');
        }

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);

        return $entity;
    }



    /**
     * Retourne une nouvelle entité de la classe donnée
     *
     * @return mixed
     */
    public function newEntity()
    {
        $class = $this->getEntityClass();

        return new $class;
    }



    /**
     * Filtre une liste d'entités à partir d'un objet chargé de lui préciser les filtres à appliquer
     * Si l'hydrateur n'est pas précisé et que l'objet implémente HydratorAwareInterface, alors l'hydrateur de l'objet est
     * utilisé. Si lhydrateur n'a toujours pas pu être déterminé, alors l'hydrateur ObjectPropertyHydrator est utilisé.
     *
     * @param \StdClass         $object   Objet contenant les filtres
     * @param HydratorInterface $hydrator Hydrateur
     * @param QueryBuilder      $qb       QueryBuilder à ne pas recréer. Permet de chaîner les filtres
     * @param string            $alias    Alias d'entité à utiliser par défaut. Utile en cas de jointure
     *
     * @return QueryBuilder                   Retourne le QueryBuilder, pour chaîner les filtres au besoin
     */
    public function finderByFilterObject($object, ?HydratorInterface $hydrator = null, ?QueryBuilder $qb = null, ?string $alias = null, array $exclude = []): QueryBuilder
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        if (null === $object) return $qb;
        if (!$hydrator && $object instanceof \Laminas\Hydrator\HydratorAwareInterface) {
            $hydrator = $object->getHydrator();
        }
        if (!$hydrator) {
            $hydrator = new ObjectPropertyHydrator();
        }

        return $this->finderByFilterArray($hydrator->extract($object), $qb, $alias, $exclude);
    }



    /**
     * Filtre une liste d'entités à partir d'untableau associatif dont les clés sont les propriétés à filtrer et les valeurs
     * les données à filtrer. Le filtre ne prend pas en compte les valeurs nulles
     *
     * @param array        $properties Liste des propriétés à filtrer
     * @param QueryBuilder $qb         QueryBuilder à ne pas recréer. Permet de chaîner les filtres
     * @param string       $alias      Alias d'entité à utiliser par défaut. Utile en cas de jointure
     *
     * @return QueryBuilder         Retourne le QueryBuilder, pour chaîner les filtres au besoin
     */
    public function finderByFilterArray(array $properties, ?QueryBuilder $qb = null, ?string $alias = null, array $exclude = []): QueryBuilder
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        foreach ($properties as $property => $value) {
            if (null != $value && !in_array($property, $exclude)) {
                if (method_exists($this, 'finderBy' . ucfirst($property))) {
                    call_user_func([$this, 'finderBy' . ucfirst($property)], $value, $qb, $alias);
                } elseif (in_array($property, $this->getProperties())) { // ne traite que les propriétés reconnues, ignore les autres
                    $this->finderByProperty($property, $value, $qb);
                }
            }
        }

        return $qb;
    }



    /**
     * Filtre par historique, si l'entité est compatible avec les historiques
     *
     * @param QueryBuilder $qb
     * @param string       $alias
     *
     * @return QueryBuilder
     */
    public function finderByHistorique(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $hasHistorique = is_subclass_of($this->getEntityClass(), 'UnicaenApp\Entity\HistoriqueAwareInterface');
        if ($hasHistorique) {
            $qb->andWhere($alias . '.histoDestruction IS NULL');
        }

        return $qb;
    }



    /**
     * Hack pour gérer le finder de structure différent des autres compte tenu de la hiérarchisation des structures
     */
    public function finderByStructure(?Structure $structure, ?QueryBuilder $qb = null, ?string $alias = null): QueryBuilder
    {
        /** @var $qb QueryBuilder */
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $e = $qb->expr();

        if (!$structure){
            $qb->andWhere("$alias.structure IS NULL");
        }else{
            $qb->leftJoin("$alias.structure", $alias.'strids');
            $qb->andWhere($e->like($alias."strids.ids", $e->literal($structure->idsFilter())));
        }

        return $qb;
    }



    /**
     *
     * @param string       $property Nom de la propriété à filtrer
     * @param mixed        $value    Valeur du filtre
     * @param QueryBuilder $qb       QueryBuilder à ne pas recréer. Permet de chaîner les filtres
     * @param string|null  $alias    Alias d'entité à utiliser par défaut. Utile en cas de jointure
     *
     * @return \Doctrine\ORM\QueryBuilder       Retourne le QueryBuilder, pour chaîner les filtres au besoin
     */
    public function finderByProperty($property, $value, ?QueryBuilder $qb = null, ?string $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        if ($value === null) {
            $qb->andWhere("$alias.$property IS NULL");
        } elseif (is_array($value) or $value instanceof \Doctrine\Common\Collections\Collection) {
            if ($value instanceof \Doctrine\Common\Collections\Collection) {
                $value = $value->toArray();
            }
            foreach ($value as $key => $val) {
                if (is_object($val) && method_exists($val, 'getId')) {
                    $value[$key] = $val->getId();
                }
            }
            $qb->andWhere($qb->expr()->in("$alias.$property", $value));
        } else {
            $qb->andWhere("$alias.$property = :$property")->setParameter($property, $value);
        }

        return $qb;
    }



    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }
        if (0 === strpos($name, 'finderBy')) {
            $property = lcfirst(substr($name, 8));
            $value    = isset($arguments[0]) ? $arguments[0] : null;
            $qb       = isset($arguments[1]) ? $arguments[1] : null;
            $alias    = isset($arguments[2]) ? $arguments[2] : null;

            return $this->finderByProperty($property, $value, $qb, $alias);
        }
        throw new RuntimeException('Méthode "' . $name . '" inconnue dans le service');
    }
}