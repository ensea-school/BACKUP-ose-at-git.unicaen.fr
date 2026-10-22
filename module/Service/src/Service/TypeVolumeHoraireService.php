<?php

namespace Service\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Service\Entity\Db\TypeVolumeHoraire;

/**
 * Description of TypeVolumeHoraire
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method TypeVolumeHoraire get($id)
 * @method TypeVolumeHoraire[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method TypeVolumeHoraire newEntity()
 *
 */
class TypeVolumeHoraireService extends AbstractEntityService
{

    /**
     * @var TypeVolumeHoraire[]
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
        return TypeVolumeHoraire::class;
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
     * @return TypeVolumeHoraire
     */
    public function getPrevu()
    {
        return $this->getByCode(TypeVolumeHoraire::CODE_PREVU);
    }



    /**
     * Retourne le type de volume horaire "Réalisé"
     *
     * @return TypeVolumeHoraire
     */
    public function getRealise()
    {
        return $this->getByCode(TypeVolumeHoraire::CODE_REALISE);
    }



    /**
     *
     * @param string $code
     *
     * @return TypeVolumeHoraire
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
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");

        return $qb;
    }

}