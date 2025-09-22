<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use UnicaenImport\Entity\Db\Source;

/**
 * Description of SourceService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SourceService extends AbstractEntityService
{
    const CODE_SOURCE_OSE  = 'OSE';
    const CODE_SOURCE_TEST = 'Test';



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Source::class;
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
     * @param $code
     *
     * @return null|Source
     */
    public function getByCode($code)
    {
        return $this->getRepo()->findOneBy(['code' => $code]);
    }



    /**
     * Retourne l'entité source OSE
     *
     * @return Source
     */
    public function getOse()
    {
        return $this->getByCode(self::CODE_SOURCE_OSE);
    }



    /**
     * Retourne l'entité de test OSE
     *
     * @return Source
     */
    public function getTest()
    {
        return $this->getByCode(self::CODE_SOURCE_TEST);
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");

        return $qb;
    }
}