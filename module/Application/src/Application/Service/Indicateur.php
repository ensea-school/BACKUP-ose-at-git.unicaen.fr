<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Indicateur as IndicateurEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Service\Indicateur\AbstractIndicateurImpl;

/**
 * Description of Indicateur
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Indicateur extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Indicateur';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'i';
    }

    /**
     *
     * @param string $code
     * @return \Application\Entity\Db\Indicateur
     */
    public function getByCode($code)
    {
        if (null == $code) return null;
        return $this->getRepo()->findOneBy(['code' => $code]);
    }

    /**
     *
     * @param QueryBuilder $qb
     * @param string|null $alias
     * @return \Application\Entity\Db\Indicateur[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
            ->andWhere("$alias.enabled = 1")
            ->addOrderBy("$alias.type, $alias.ordre");

        return parent::getList($qb, $alias);
    }

    /**
     *
     * @param IndicateurEntity $indicateur
     * @param StructureEntity $structure
     * @return AbstractIndicateurImpl
     */
    public function getIndicateurImpl(IndicateurEntity $indicateur, StructureEntity $structure = null)
    {
        /** @var AbstractIndicateurImpl $impl */
        $impl = clone $this->getServiceLocator()->get($indicateur->getCode());
        $impl
            ->setIndicateurEntity($indicateur)
            ->setStructure($structure);

        return $impl;
    }

    /**
     *
     * @param IndicateurEntity[] $indicateurs
     * @param StructureEntity $structure
     * @return AbstractIndicateurImpl[]
     */
    public function getIndicateursImpl($indicateurs, StructureEntity $structure = null)
    {
        $impls = [];
        foreach ($indicateurs as $indicateur) {
            $impls[$indicateur->getId()] = $this->getIndicateurImpl($indicateur, $structure);
        }

        return $impls;
    }
}