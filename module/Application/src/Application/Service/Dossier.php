<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Dossier as DossierEntity;

/**
 * Description of Dossier
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Dossier extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Dossier';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'd';
    }
    
    /**
     * Détermine si on peut ajouter une entité ou non
     *
     * @return boolean
     */
    public function canAdd($runEx = false)
    {
        $localContext = $this->getContextProvider()->getLocalContext();
        $role         = $this->getServiceLocator()->get('ApplicationContextProvider')->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\IntervenantExterieurRole) { 
            return true;
        }

        return $this->cannotDoThat('Vous n\'avez pas les droits nécessaires pour saisir un dossier.', $runEx);
    }

    /**
     * Détermine si l'entité peut être éditée ou non
     * 
     * @param int|\Application\Entity\Db\Dossier $entity
     * @return boolean
     */
    public function canSave(DossierEntity $entity, $runEx = false)
    {
        return $this->canAdd($runEx);
    }

    /**
     * Détermine si l'entité peut être supprimée ou non
     *
     * @param \Application\Entity\Db\Dossier $entity
     * @param boolean $runEx
     * @return boolean
     */
    public function canDelete(DossierEntity $entity, $runEx=false)
    {
        return $this->canSave($entity, $runEx);
    }

    /**
     * Retourne la liste des étapes
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return \Application\Entity\Db\Dossier[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return parent::getList($qb, $alias);
    }

    public function save($entity)
    {
        $this->canSave($entity,true);
        parent::save($entity);
    }

    public function delete($entity, $softDelete = true)
    {
        $this->canDelete($entity,true);
        return parent::delete($entity, $softDelete);
    }

    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     * @return DossierEntity
     */
    public function newEntity()
    {
        $this->canAdd(true);
        $entity = parent::newEntity(); /* @var $entity DossierEntity */
        return $entity;
    }

}