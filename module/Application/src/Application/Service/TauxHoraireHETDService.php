<?php

namespace Application\Service;

use Application\Entity\Db\TauxHoraireHETD;

/**
 * Description of TauxHoraireHETDService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method TauxHoraireHETD get($id)
 * @method TauxHoraireHETD[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method TauxHoraireHETD newEntity()
 *
 */
class TauxHoraireHETDService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TauxHoraireHETD::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'thh';
    }



    /**
     * @param \DateTime|null $date
     *
     * @return TauxHoraireHETD
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByDate( \DateTime $date = null )
    {
        if (!$date) $date = new \DateTime();

        $dql = "
        SELECT
          thh
        FROM
          Application\Entity\Db\TauxHoraireHETD thh        
        WHERE
          1 = compriseEntre( thh.histoCreation, thh.histoDestruction, :date )
          AND thh.histoCreation <= :date
        ORDER BY
          thh.histoCreation DESC
        ";

        return $this->getEntityManager()->createQuery($dql)->setParameter('date', $date)->setMaxResults(1)->getOneOrNullResult();
    }

}