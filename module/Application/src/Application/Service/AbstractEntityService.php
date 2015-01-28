<?php
namespace Application\Service;

use Doctrine\ORM\EntityRepository;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Doctrine\ORM\Query\Expr;
use \Application\Entity\Db\HistoriqueAwareInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
abstract class AbstractEntityService extends AbstractService
{
    /**
     * EntityRepository
     *
     * @var EntityRepository
     */
    private $repo;

    /**
     *
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     *
     * @var boolean
     */
    private $hasHistorique;

    /**
     * Liste des propriétés des entités
     *
     * @var string[]
     */
    private $properties;





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
    private function getReflectionClass(){
        if (! $this->reflectionClass){
            $this->reflectionClass = new \ReflectionClass( $this->getEntityClass() );
        }
        return $this->reflectionClass;
    }

    /**
     * Retourne le repos des entités et active le filtre historique si besoin
     *
     * @return EntityRepository
     */
    public function getRepo()
    {
        if( !$this->repo ){
            if ($this->hasHistorique()) $this->getEntityManager()->getFilters()->enable("historique");
            $this->repo = $this->getEntityManager()->getRepository($this->getEntityClass());
        }
        return $this->repo;
    }

    /**
     * Détermine si les entités gèrent les historiques ou non
     *
     * @return boolean
     */
    public function hasHistorique()
    {
        if (null === $this->hasHistorique){
            $this->hasHistorique = $this->getReflectionClass()->implementsInterface('Application\Entity\Db\HistoriqueAwareInterface');
        }
        return $this->hasHistorique;
    }

    /**
     * Retourne la liste des propriétés filtrables de l'entité
     *
     * @return string[]
     */
    public function getProperties()
    {
        if (null === $this->properties){
            $m = $this->getReflectionClass()->getMethods(\ReflectionMethod::IS_PUBLIC);
            $p = $this->getReflectionClass()->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED);

            $methods = array();
            foreach( $m as $method ){
                if (0 === strpos($method->name, 'get')) $methods[] = $method->name;
            }

            $this->properties = array();
            foreach( $p as $property ){
                if (in_array('get'.ucfirst($property->name),$methods)){
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
     * @param QueryBuilder|null $qb      Générateur de requêtes
     * @param string|null $alias         Alias d'entité
     * @return array
     */
    public function initQuery(QueryBuilder $qb=null, $alias=null)
    {
        if (null === $alias) $alias = $this->getAlias();
        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder($alias);
        return array($qb,$alias);
    }

    /**
     *
     * @param \Application\Service\AbstractEntityService|string $service
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string $relation
     * @param boolean $addSelect
     * @param string $leftAlias
     * @param string $rightAlias
     * @return self
     */
    public function join( $service, QueryBuilder $qb, $relation, $addSelect=false, $leftAlias=null, $rightAlias=null )
    {
        return $this->_join('join', $service, $qb, $relation, $addSelect, $leftAlias, $rightAlias);
    }

    /**
     *
     * @param \Application\Service\AbstractEntityService|string $service
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string $relation
     * @param boolean $addSelect
     * @param string $leftAlias
     * @param string $rightAlias
     * @return self
     */
    public function leftJoin( $service, QueryBuilder $qb, $relation, $addSelect=false, $leftAlias=null, $rightAlias=null )
    {
        return $this->_join('leftJoin', $service, $qb, $relation, $addSelect, $leftAlias, $rightAlias);
    }

    /**
     *
     * @param \Application\Service\AbstractEntityService|string $service
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string $relation
     * @param boolean|array|null $addSelect
     * @param string $leftAlias
     * @param string $rightAlias
     * @return self
     */
    private function _join( $method='join', $service, QueryBuilder $qb, $relation, $addSelect=false, $leftAlias=null, $rightAlias=null )
    {
        if (is_string($service)){
            $service = $this->getServiceLocator()->get($service);
            if (! $service instanceof AbstractEntityService){
                throw new \Common\Exception\LogicException('Le service transmis n\'est pas compatible.');
            }
        }

        if (null === $leftAlias) $leftAlias = $this->getAlias();
        if (null === $rightAlias) $rightAlias = $service->getAlias();

        if (in_array($rightAlias, $this->getQbFromAliases($qb))){
            return $this; // Prévention de conflits de jointures
        }

        $qb->$method( $leftAlias.'.'.$relation, $rightAlias );
        if (true === $addSelect){
            $qb->addSelect( $rightAlias );
        }elseif(is_array($addSelect)){
            //$qb->addSelect( $rightAlias );
            $qb->addSelect( 'partial '.$rightAlias.'.{'.implode( ', ', $addSelect).'}' );
        }
        return $this;
    }

    /**
     * 
     * @param QueryBuilder $qb
     * @return string[]
     */
    public function getQbFromAliases( QueryBuilder $qb )
    {
        $aliases = [];
        $from = $qb->getDQLPart('from');
        $join = $qb->getDQLPart('join');

        foreach( $from as $ef ){ /* @var $ef Expr\From */
            $aliases[] = $ef->getAlias();
        }

        $this->getJoinExprs($join, $aliases);

        return $aliases;
    }

    private function getJoinExprs( $joinPart, &$aliases ){
        if ($joinPart instanceof Expr\Join){ /* @var $joinPart Expr\Join */
            $aliases[] = $joinPart->getAlias();
        }elseif(is_array($joinPart)){
            foreach( $joinPart as $jp ){
                $this->getJoinExprs( $jp, $aliases );
            }
        }
    }

    /**
     * Retourne une liste d'entités en fonction du QueryBuilder donné
     *
     * La liste de présente sous la forme d'un tableau associatif, dont les clés sont les ID des entités et les valeurs les entités elles-mêmes
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return array
     */
    public function getList(QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $entities = $qb->getQuery()->execute();
        $result = array();
        $entityClass = $this->getEntityClass();
        foreach( $entities as $entity ){
            if ($entity instanceof $entityClass){
                $result[$entity->getId()] = $entity;
            }
        }
        return $result;
    }

    /**
     * Retourne le nombre d'entités trouvé
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return integer
     */
    public function count(QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $entities = $qb->getQuery()->execute();
        return count($entities);
    }

    /**
     * Retourne une entité à partir de son identifiant unique (ID)
     * Retourne null si l'identifiant est null ou bien s'il est égal à 0
     *
     * @param integer|integer[] $id
     * @return mixed|null
     */
    public function get($id)
    {
        if(is_array($id)){
            list($qb,$alias) = $this->initQuery();
            foreach( $id as $idi ) $qb->orWhere($alias.'.id = '.(int)$idi);
            return $this->getList( $qb );
        }elseif ((int)$id){
            return $this->getRepo()->find((int)$id);
        }else{
            return null;
        }
    }

    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param mixed $entity     Entité à détruire
     * @param bool $softDelete
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        $entityClass = get_class($entity);
        $serviceEntityClass = $this->getEntityClass();
        if ($serviceEntityClass != $entityClass && ! is_subclass_of($entity, $serviceEntityClass)){
            throw new \Common\Exception\RuntimeException('L\'entité transmise n\'est pas de la classe '.$serviceEntityClass.'.');
        }
        if ($softDelete && $entity instanceof HistoriqueAwareInterface ) {
            $entity->setHistoDestruction(new \DateTime);
        }else{
            $this->getEntityManager()->remove($entity);
        }
        $this->getEntityManager()->flush($entity);
        return $this;
    }

    /**
     * Sauvegarde une entité
     *
     * @param mixed $entity
     * @throws \Common\Exception\RuntimeException
     * @return mixed
     */
    public function save($entity)
    {
        $entityClass = get_class($entity);
        $serviceEntityClass = $this->getEntityClass();
        if ($serviceEntityClass != $entityClass && ! is_subclass_of($entity, $serviceEntityClass)){
            throw new \Common\Exception\RuntimeException('L\'entité transmise n\'est pas de la classe '.$serviceEntityClass.'.');
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
     * Si l'hydrateur n'est pas précisé et que l'objet implémente HydratorAwareInterface, alors l'hydrateur de l'objet est utilisé.
     * Si lhydrateur n'a toujours pas pu être déterminé, alors l'hydrateur ObjectProperty est utilisé.
     *
     * @param \StdClass $object               Objet contenant les filtres
     * @param HydratorInterface $hydrator     Hydrateur
     * @param QueryBuilder $qb                QueryBuilder à ne pas recréer. Permet de chaîner les filtres
     * @param string $alias                   Alias d'entité à utiliser par défaut. Utile en cas de jointure
     * @return QueryBuilder                   Retourne le QueryBuilder, pour chaîner les filtres au besoin
     */
    public function finderByFilterObject( $object, HydratorInterface $hydrator=null, QueryBuilder $qb=null, $alias=null, $exclude=[] )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        if (null === $object) return $qb;
        if (! $hydrator && $object instanceof \Zend\Stdlib\Hydrator\HydratorAwareInterface){
            $hydrator = $object->getHydrator();
        }
        if (! $hydrator){
            $hydrator = new ObjectProperty();
        }
        return $this->finderByFilterArray($hydrator->extract($object), $qb, $alias, $exclude);
    }

    /**
     * Filtre une liste d'entités à partir d'untableau associatif dont les clés sont les propriétés à filtrer et les valeurs les données à filtrer.
     * Le filtre ne prend pas en compte les valeurs nulles
     *
     * @param array $properties     Liste des propriétés à filtrer
     * @param QueryBuilder $qb      QueryBuilder à ne pas recréer. Permet de chaîner les filtres
     * @param string $alias         Alias d'entité à utiliser par défaut. Utile en cas de jointure
     * @return QueryBuilder         Retourne le QueryBuilder, pour chaîner les filtres au besoin
     */
    public function finderByFilterArray( array $properties, QueryBuilder $qb=null, $alias=null, $exclude=[] )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        foreach( $properties as $property => $value){
            if (null != $value && ! in_array($property, $exclude)){
                if(method_exists($this,'finderBy'.ucfirst($property))){
                    call_user_func(array($this,'finderBy'.ucfirst($property)), $value, $qb, $alias);
                }elseif (in_array($property, $this->getProperties())){ // ne traite que les propriétés reconnues, ignore les autres
                    $this->finderByProperty($property, $value, $qb);
                }
            }
        }
        return $qb;
    }

    /**
     *
     * @param string $property                  Nom de la propriété à filtrer
     * @param mixed $value                      Valeur du filtre
     * @param QueryBuilder $qb                  QueryBuilder à ne pas recréer. Permet de chaîner les filtres
     * @param string|null $alias                Alias d'entité à utiliser par défaut. Utile en cas de jointure
     * @return \Doctrine\ORM\QueryBuilder       Retourne le QueryBuilder, pour chaîner les filtres au besoin
     */
    public function finderByProperty( $property, $value, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.$property = :$property")->setParameter($property, $value);
        return $qb;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this,$name)){
            return call_user_func_array(array($this,$name), $arguments);
        }
        if (0 === strpos($name, 'finderBy')){
            $property = lcfirst(substr($name, 8));
            $value = isset($arguments[0]) ? $arguments[0] : null;
            $qb = isset($arguments[1]) ? $arguments[1] : null;
            $alias = isset($arguments[2]) ? $arguments[2] : null;
            return $this->finderByProperty($property, $value, $qb, $alias);
        }
        throw new RuntimeException('Méthode "'.$name.'" inconnue dans le service');
    }

}