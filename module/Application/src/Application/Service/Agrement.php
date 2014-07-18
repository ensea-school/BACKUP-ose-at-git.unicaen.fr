<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeAgrement as TypeAgrementEntity;
use Application\Entity\Db\Agrement as AgrementEntity;

/**
 * Description of Agrement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Agrement extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Agrement';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'a';
    }

    /**
     * Retourne la liste des pièces jointes d'un type donné.
     *
     * @param TypeAgrementEntity $type
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByType(TypeAgrementEntity $type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.type = :type")->setParameter('type', $type);
        return $qb;
    }

    /**
     * Retourne la liste des étapes
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return AgrementEntity[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");
        return parent::getList($qb, $alias);
    }
    
    /**
     * Détermine si on peut saisir les pièces justificatives.
     *
     * @param \Application\Entity\Db\Intervenant $intervenant Intervenant concerné
     * @return boolean
     */
//    public function canAdd($intervenant, $runEx = false)
//    {
//        $role = $this->getContextProvider()->getSelectedIdentityRole();
//        
//        $rule = new \Application\Rule\Intervenant\PeutSaisirAgrementRule($intervenant);
//        if (!$rule->execute()) {
//            $message = "?";
//            if ($role instanceof \Application\Acl\IntervenantRole) {
//                $message = "Vous ne pouvez pas saisir de pièce justificative. ";
//            }
//            elseif ($role instanceof \Application\Acl\ComposanteDbRole) {
//                $message = "Vous ne pouvez pas saisir de pièce justificative pour $intervenant. ";
//            }
//            return $this->cannotDoThat($message . $rule->getMessage(), $runEx);
//        }
//        
//        return true;
//    }
}