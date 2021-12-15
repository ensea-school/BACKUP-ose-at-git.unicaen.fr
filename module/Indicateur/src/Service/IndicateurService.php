<?php

namespace Indicateur\Service;

use Application\Service\AbstractService;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Indicateur\Entity\Db\Indicateur;


/**
 * Description of IndicateurService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method Indicateur get($id)
 * @method Indicateur newEntity()
 *
 */
class IndicateurService extends AbstractService
{
    use IntervenantServiceAwareTrait;


    /**
     * @param Indicateur $indicateur Indicateur concerné
     * @param null       $structure
     *
     * @return QueryBuilder
     */
    private function getBaseQueryBuilder(Indicateur $indicateur)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->from(\Indicateur\Entity\Db\Indicateur\Indicateur::class . $indicateur->getNumero(), 'indicateur');
        $qb->join('indicateur.intervenant', 'intervenant');
        $qb->join('intervenant.statut', 'statut');
        $qb->andWhere('statut.nonAutorise = 0');

        /* Filtrage par intervenant */
        //$qb->join('indicateur.intervenant', 'intervenant');

        //$this->getServiceIntervenant()->finderByHistorique($qb, 'intervenant');
        //$this->getServiceIntervenant()->finderByAnnee($this->getServiceContext()->getAnnee(), $qb, 'intervenant');

        $qb->andWhere('indicateur.annee = :annee')->setParameter('annee', $this->getServiceContext()->getAnnee());

        /* Filtrage par structure, si nécessaire */

        $role = $this->getServiceContext()->getSelectedIdentityRole();
        if ($role) {
            $structure = $role->getStructure();
        }
        if ($structure) {
            $sign = $indicateur->isNotStructure() ? '<>' : '=';
            //$qb->andWhere('indicateur.structure IS NULL OR indicateur.structure ' . $sign . ' ' . $structure->getId());
            $qb->andWhere('indicateur.structure ' . $sign . ' ' . $structure->getId());
        }

        return $qb;
    }



    /**
     * @param integer|Indicateur $indicateur Indicateur concerné
     */
    public function getCount(Indicateur $indicateur)
    {
        $qb = $this->getBaseQueryBuilder($indicateur);
        $qb->addSelect('COUNT(' . ($indicateur->isDistinct() ? 'DISTINCT ' : '') . 'indicateur.intervenant) result');

        return (integer)$qb->getQuery()->getResult()[0]['result'];
    }



    /**
     * @param Indicateur $indicateur Indicateur concerné
     *
     * @return Indicateur\AbstractIndicateur[]
     */
    public function getResult(Indicateur $indicateur)
    {
        $qb = $this->getBaseQueryBuilder($indicateur);

        //$qb->join('indicateur.intervenant', 'intervenant');

        $qb->addSelect('indicateur');
        $qb->addSelect('partial intervenant.{id, nomUsuel, prenom, emailPerso, emailPro, code}');

        $qb->addSelect('partial structure.{id, libelleCourt, libelleLong}');
        $qb->leftJoin('indicateur.structure', 'structure');

        $indicateurClass = \Indicateur\Entity\Db\Indicateur\Indicateur::class . $indicateur->getNumero();
        $indicateurClass::appendQueryBuilder($qb);
        $qb->addOrderBy('structure.libelleCourt');
        $this->getServiceIntervenant()->orderBy($qb, 'intervenant');

        $entities = $qb->getQuery()->execute();
        /* @var $entities Indicateur\AbstractIndicateur[] */
        $result = [];
        foreach ($entities as $entity) {
            $result[$entity->getId()] = $entity;
        }

        return $result;
    }

}