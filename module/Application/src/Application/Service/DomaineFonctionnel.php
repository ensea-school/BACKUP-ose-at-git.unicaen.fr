<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Dossier as DossierEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;

/**
 * 
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DomaineFonctionnel extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\DomaineFonctionnel';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'df';
    }

    /**
     * Retourne la liste des étapes
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return \Application\Entity\Db\DomaineFonctionnel[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");

        return parent::getList($qb, $alias);
    }
}