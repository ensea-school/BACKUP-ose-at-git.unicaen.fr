<?php

namespace Application\Service;
use Referentiel\Entity\Db\FonctionReferentiel;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of FonctionReferentielService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method FonctionReferentiel get($id)
 * @method FonctionReferentiel[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method FonctionReferentiel newEntity()
 */
class FonctionReferentielService extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return FonctionReferentiel::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'fonc_ref';
    }



    /**
     * @param QueryBuilder|null $qb
     * @param null              $alias
     *
     * @return QueryBuilder|mixed|null
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.libelleLong");

        return $qb;
    }

}