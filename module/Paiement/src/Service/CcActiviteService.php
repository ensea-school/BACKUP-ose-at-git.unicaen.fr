<?php

namespace Paiement\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Doctrine\ORM\QueryBuilder;
use Paiement\Entity\Db\CcActivite;

/**
 * Description of FonctionReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CcActiviteService extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return CcActivite::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ca';
    }

    public function getList(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery();

        $qb->addOrderBy("$alias.libelle");

        return parent::getList($qb, $alias);
    }
}