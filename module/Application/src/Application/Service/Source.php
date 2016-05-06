<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Source as SourceEntity;

/**
 * Description of Source
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Source extends AbstractEntityService
{
    const CODE_SOURCE_OSE     = 'OSE';
    const CODE_SOURCE_TEST    = 'Test';
    
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \UnicaenImport\Entity\Db\Source::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'src';
    }

    /**
     * Retourne l'entité source OSE
     *
     * @return SourceEntity
     */
    public function getOse()
    {
        return $this->getRepo()->findOneBy(['code' => self::CODE_SOURCE_OSE]);
    }

    /**
     * Retourne l'entité de test OSE
     *
     * @return SourceEntity
     */
    public function getTest()
    {
        return $this->getRepo()->findOneBy(['code' => self::CODE_SOURCE_TEST]);
    }

    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     */
    public function orderBy( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return $qb;
    }
}