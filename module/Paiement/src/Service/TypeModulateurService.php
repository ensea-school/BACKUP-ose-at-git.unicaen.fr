<?php

namespace Paiement\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Application\Service\type;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\ElementPedagogique;
use Paiement\Entity\Db\TypeModulateur;


/**
 * Description of TypeModulateur
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeModulateurService extends AbstractEntityService
{

    /**
     * Liste de tous les types de modulateurs
     *
     * @var TypeModulateur
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
        return TypeModulateur::class;
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
     * @param Structure $structure
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param type $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByStructure(?Structure $structure, ?QueryBuilder $qb = null, $alias = null): QueryBuilder
    {
        [$qb,$alias] = $this->initQuery($qb, $alias);

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
            [$qb,$alias] = $this->initQuery();
            $qb->andWhere($alias.'.code IN (:'.$alias.'_code)')->setParameter($alias.'_code', $code);
            return $this->getList( $qb );
        }elseif ($code){
            return $this->getRepo()->findOneBy(['code' => $code]);
        }else{
            return null;
        }
    }

    /**
     * Retourne une entité à partir de son code
     * Retourne null si le code est null
     *
     * @param string|string[] $code
     * @return mixed|null
     */
    public function getById($id)
    {
        if(is_array($id)){
            [$qb,$alias] = $this->initQuery();
            $qb->andWhere($alias.'.id IN (:'.$alias.'_id)')->setParameter($alias.'_id', $id);
            return $this->getList( $qb );
        }elseif ($id){
            return $this->getRepo()->findOneBy(['id' => $id]);
        }else{
            return null;
        }
    }
    /**
     * Ne récupère que les types de modulateurs associés à un élément pédagogique donné
     *
     * @param ElementPedagogique $element
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param type $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByElementPedagogique(ElementPedagogique $element, ?QueryBuilder $qb=null, $alias=null)
    {
        [$qb,$alias] = $this->initQuery($qb, $alias);

        $pid = 'ep'.uniqid();
        $qb->andWhere(":$pid MEMBER OF $alias.elementPedagogique")->setParameter($pid, $element);

        return $qb;
    }

    public function finderByEtape(\OffreFormation\Entity\Db\Etape $etape, ?QueryBuilder $qb = null, $alias=null )
    {
        [$qb,$alias] = $this->initQuery($qb, $alias);

        $pid = 'etp'.uniqid();
        $qb->andWhere(":$pid MEMBER OF $alias.etape")->setParameter($pid, $etape);

        return $qb;
    }

    /**
     * Retourne la liste des types de modulateurs
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return TypeModulateur[]
     */
    public function getList( ?QueryBuilder $qb = null, $alias=null )
    {
        [$qb,$alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return parent::getList($qb, $alias);
    }

    /**
     * Retourne la liste de tous les types de modulateurs
     *
     * @return TypeModulateur
     */
    public function getAll()
    {
        if (! $this->all){
            $this->all = $this->getList();
        }
        return $this->all;
    }

}