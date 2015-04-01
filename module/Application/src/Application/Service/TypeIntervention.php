<?php

namespace Application\Service;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeIntervention as Entity;

/**
 * Description of TypeIntervention
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeIntervention extends AbstractEntityService
{

    /**
     * Liste des types d'intervention
     *
     * @var Entity[]
     */
    protected $typesIntervention;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\TypeIntervention';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'ti';
    }

    /**
     * Retourne la liste des motifs de non paiement
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Application\Entity\Db\Periode[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");
        return parent::getList($qb, $alias);
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
     * Liste des types d'intervention
     *
     * @return Entity[]
     */
    public function getTypesIntervention()
    {
        if (! $this->typesIntervention){
            $this->typesIntervention = $this->getList( $this->finderByVisible(true) );
        }
        return $this->typesIntervention;
    }
}