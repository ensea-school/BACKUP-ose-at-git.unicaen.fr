<?php

namespace Intervenant\Service;


use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Entity\Db\TypeIntervenant;

/**
 * Description of TypeIntervenantService
 *
 * @method TypeIntervenant get($id)
 * @method TypeIntervenant[] list($id)
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeIntervenantService extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return TypeIntervenant::class;
    }



    /**
     * Retourne le type d'intervenant Permanent
     */
    public function getPermanent(): TypeIntervenant
    {
        return $this->getRepo()->findOneBy(['code' => TypeIntervenant::CODE_PERMANENT]);
    }



    /**
     * Retourne le type d'intervenant Extérieur
     */
    public function getExterieur(): TypeIntervenant
    {
        return $this->getRepo()->findOneBy(['code' => TypeIntervenant::CODE_EXTERIEUR]);
    }



    /**
     * Retourne le type d'intervenant Extérieur
     */
    public function getEtudiant(): TypeIntervenant
    {
        return $this->getRepo()->findOneBy(['code' => TypeIntervenant::CODE_ETUDIANT]);
    }



    public function getAlias(): string
    {
        return 'type_int';
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.code", 'DESC');

        return $qb;
    }



    public function getByCode(?string $code): ?TypeIntervenant
    {
        if (null == $code) return null;

        return $this->getRepo()->findOneBy(['code' => $code]);
    }
}