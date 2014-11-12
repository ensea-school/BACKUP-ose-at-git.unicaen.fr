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
     * Détermine si on peut saisir un dossier
     *
     * @param \Application\Entity\Db\Intervenant $intervenant Eventuel intervenant concerné
     * @return boolean
     */
    public function canAdd($intervenant = null, $runEx = false)
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        if (!$intervenant) {
            return $this->cannotDoThat("Anomalie : aucun intervenant spécifié.", $runEx);
        }
        
        $rule = $this->getServiceLocator()->get('PeutSaisirDossierRule')->setIntervenant($intervenant);
        if (!$rule->execute()) {
            $message = "?";
            if ($role instanceof \Application\Acl\IntervenantRole) {
                $message = "Vous ne pouvez pas saisir de données personnelles. ";
            }
            elseif ($role instanceof \Application\Acl\ComposanteRole) {
                $message = "Vous ne pouvez pas saisir de données personnelles pour $intervenant. ";
            }
            return $this->cannotDoThat($message . $rule->getMessage(), $runEx);
        }
        
        return true;
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

    public function delete($entity, $softDelete = true)
    {
        $this->canDelete($entity,true);
        return parent::delete($entity, $softDelete);
    }
}