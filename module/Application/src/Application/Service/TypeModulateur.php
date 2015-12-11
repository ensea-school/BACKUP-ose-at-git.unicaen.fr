<?php

namespace Application\Service;

use Application\Entity\Db\TypeModulateur as TypeModulateurEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\ElementPedagogique as ElementPedagogiqueEntity;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of TypeModulateur
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeModulateur extends AbstractEntityService
{

    /**
     * Liste de tous les types de modulateurs
     *
     * @var TypeModulateurEntity
     */
    protected $all;

    /**
     * Cache du finder par structures
     *
     * @var array
     */
    protected $finderByStructureCache = [];


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeModulateurEntity::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'typmodu';
    }

    /**
     * Ne récupère que les types de modulateurs associés à une structure donnée
     *
     * @param StructureEntity $structure
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param type $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByStructure(StructureEntity $structure, QueryBuilder $qb=null, $alias=null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $pid = 'str'.uniqid();
        $qb->andWhere(":$pid MEMBER OF $alias.structure")->setParameter($pid, $structure);

        return $qb;
    }

    /**
     * Retourne une entité à partir de son code
     * Retourne null si le code est null
     *
     * @param string|string[] $code
     * @return mixed|null
     */
    public function getByCode($code)
    {
        if(is_array($code)){
            list($qb,$alias) = $this->initQuery();
            $qb->andWhere($alias.'.code IN (:'.$alias.'_code)')->setParameter($alias.'_code', $code);
            return $this->getList( $qb );
        }elseif ($code){
            return $this->getRepo()->findOneBy(['code' => $code]);
        }else{
            return null;
        }
    }

    /**
     * Ne récupère que les types de modulateurs associés à un élément pédagogique donné
     *
     * @param ElementPedagogiqueEntity $element
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param type $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByElementPedagogique(ElementPedagogiqueEntity $element, QueryBuilder $qb=null, $alias=null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $pid = 'ep'.uniqid();
        $qb->andWhere(":$pid MEMBER OF $alias.elementPedagogique")->setParameter($pid, $element);

        return $qb;
    }

    public function finderByEtape(\Application\Entity\Db\Etape $etape, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $pid = 'etp'.uniqid();
        $qb->andWhere(":$pid MEMBER OF $alias.etape")->setParameter($pid, $etape);

        return $qb;
    }

    /**
     * Retourne la liste des types de modulateurs
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return TypeModulateurEntity[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return parent::getList($qb, $alias);
    }

    /**
     * Retourne la liste de tous les types de modulateurs
     *
     * @return TypeModulateurEntity
     */
    public function getAll()
    {
        if (! $this->all){
            $this->all = $this->getList();
        }
        return $this->all;
    }
}