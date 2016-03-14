<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;

/**
 * Description of TypeVolumeHoraire
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeVolumeHoraire extends AbstractEntityService
{

    /**
     * @var TypeVolumeHoraireEntity[]
     */
    private $cache;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeVolumeHoraireEntity::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'typevh';
    }



    /**
     * Retourne le type de volume horaire "Prévu"
     *
     * @return TypeVolumeHoraireEntity
     */
    public function getPrevu()
    {
        return $this->getByCode(TypeVolumeHoraireEntity::CODE_PREVU);
    }



    /**
     * Retourne le type de volume horaire "Réalisé"
     *
     * @return TypeVolumeHoraireEntity
     */
    public function getRealise()
    {
        return $this->getByCode(TypeVolumeHoraireEntity::CODE_REALISE);
    }



    /**
     *
     * @param string $code
     *
     * @return TypeVolumeHoraireEntity
     */
    public function getByCode($code)
    {
        if (!isset($this->cache[$code])) {
            $this->cache[$code] = $this->getRepo()->findOneBy(['code' => $code]);
        }

        return $this->cache[$code];
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");

        return parent::getList($qb, $alias);
    }

}