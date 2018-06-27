<?php

namespace Application\Service;

/**
 * Description of GroupeTypeFormation
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class GroupeTypeFormationService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\GroupeTypeFormation::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'gtf';
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
            list($qb,$alias) = $this->initQuery();
            $qb->andWhere($alias.'.id IN (:'.$alias.'_id)')->setParameter($alias.'_id', $id);
            return $this->getList( $qb );
        }elseif ($id){
            return $this->getRepo()->findOneBy(['id' => $id]);
        }else{
            return null;
        }
    }
}