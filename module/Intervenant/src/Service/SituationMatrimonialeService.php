<?php

namespace Intervenant\Service;

use Application\Service\AbstractEntityService;
use Intervenant\Entity\Db\SituationMatrimoniale;
use RuntimeException;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of SituationMatrimoniale
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 *
 * @method SituationMatrimoniale get($id)
 * @method SituationMatrimoniale[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method SituationMatrimoniale newEntity()
 */
class SituationMatrimonialeService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return SituationMatrimoniale::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'sm';
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $qb->orderBy($alias . '.libelle', 'ASC');

        return $qb;
    }



    public function getSituationMatrimonialeByCode(string $code): ?SituationMatrimoniale
    {
        if (!empty($code)) {
            return $this->getEntityManager()->getRepository($this->getEntityClass())->findOneBy(['code' => $code]);
        }

        return null;
    }
}