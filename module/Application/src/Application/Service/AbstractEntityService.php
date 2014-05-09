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

    public function join( AbstractEntityService $service, QueryBuilder $qb, $leftProperty, $rightProperty=null, $leftAlias=null, $rightAlias=null )
    {
        if (null == $leftAlias) $leftAlias = $this->getAlias();
        if (null == $rightAlias) $rightAlias = $service->getAlias();

        if (null == $rightProperty){ // relation
            $qb->join( $leftAlias.'.'.$leftProperty, $rightAlias );
        }else{ // relation spéciale
            $qb->join( $service->getEntityClass(), $rightAlias, Expr\Join::WITH, $leftAlias.'.'.$leftProperty.'='.$rightAlias.'.'.$rightProperty );
        }
        return $qb;
    }

    public function leftJoin( AbstractEntityService $service, QueryBuilder $qb, $leftProperty, $rightProperty=null, $leftAlias=null, $rightAlias=null )
    {
        if (null == $leftAlias) $leftAlias = $this->getAlias();
        if (null == $rightAlias) $rightAlias = $service->getAlias();
        if (null == $rightProperty){ // relation
            $qb->leftJoin( $leftAlias.'.'.$leftProperty, $rightAlias );
        }else{ // relation spéciale
            $qb->leftJoin( $service->getEntityClass(), $rightAlias, Expr\Join::WITH, $leftAlias.'.'.$leftProperty.'='.$rightAlias.'.'.$rightProperty );
        }

        
        return $qb;
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
        foreach( $entities as $entity ){
            $result[$entity->getId()] = $entity;
        }
        return $result;
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
     * Suvegarde une entité
     *
     * @param mixed $entity
     * @throws \Common\Exception\RuntimeException
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
    public function finderByFilterObject( $object, HydratorInterface $hydrator=null, QueryBuilder $qb=null, $alias=null )
    {
        if (! $hydrator && $object instanceof \Zend\Stdlib\Hydrator\HydratorAwareInterface){
            $hydrator = $object->getHydrator();
        }
        if (! $hydrator){
            $hydrator = new ObjectProperty();
        }
        return $this->finderByFilterArray($hydrator->extract($object), $qb, $alias);
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
    public function finderByFilterArray( array $properties, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        foreach( $properties as $property => $value){
            if (null != $value){
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