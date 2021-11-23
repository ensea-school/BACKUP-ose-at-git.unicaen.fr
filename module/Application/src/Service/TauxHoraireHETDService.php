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
    public function getAlias()
    {
        return 'thh';
    }



    /**
     * @param \DateTime|null $date
     *
     * @return TauxHoraireHETD
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByDate(\DateTime $date = null)
    {
        if (!$date) $date = new \DateTime();

        $date = $date->format('Y-m-d');

        $sql = "
        SELECT id
        FROM taux_horaire_hetd t 
        WHERE TO_DATE(:date,'YYYY-MM-DD') BETWEEN t.histo_creation AND COALESCE(t.histo_destruction,SYSDATE) AND rownum = 1
        ORDER BY histo_creation DESC
        ";

        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, ['date' => $date]);
        $id  = (int)$res[0]['ID'];

        return $this->getEntityManager()->getRepository(TauxHoraireHETD::class)->find($id);
    }

}