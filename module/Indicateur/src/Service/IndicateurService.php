<?php

namespace Indicateur\Service;

use Application\Cache\Traits\CacheContainerTrait;
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
    use CacheContainerTrait;


    protected function getViewDef(int $numero): string
    {
        $view    = 'V_INDICATEUR_' . $numero;
        $sql     = "SELECT TEXT FROM USER_VIEWS WHERE VIEW_NAME = '$view'";
        $viewDef = $this->getEntityManager()->getConnection()->fetchAssociative($sql, [])['TEXT'];

        return $viewDef;
    }



    protected function fetchData(Indicateur $indicateur, string $select): array
    {
        $numero    = $indicateur->getNumero();
        $structure = $this->getServiceContext()->getStructure();
        $annee     = $this->getServiceContext()->getAnnee();

        $viewDef = $this->getViewDef($numero);

        $params = [
            'annee' => $annee->getId(),
        ];
        $sql    = "SELECT
          $select
        FROM
          ($viewDef) indic
          JOIN intervenant i ON i.id = indic.intervenant_id AND i.histo_destruction IS NULL
          JOIN statut_intervenant si ON si.id = i.statut_id AND si.non_autorise = 0
          LEFT JOIN structure s ON s.id = indic.structure_id
        WHERE
          i.annee_id = :annee  
        ";
        if ($structure) {
            $params['structure'] = $structure->getId();
            $sql                 .= ' AND (indic.structure_id = :structure OR indic.structure_id IS NULL)';
        }

        return $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);
    }



    /**
     * @param Indicateur $indicateur Indicateur concerné
     * @param null       $structure
     *
     * @return QueryBuilder
     */
    private function getBaseQueryBuilder(Indicateur $indicateur)
    {
        $numero = $indicateur->getNumero();
        $sql    = "
          SELECT
            indic.*,
          FROM        
            (SELECT * FROM V_INDICATEUR_$numero) indic
            JOIN intervenant i ON i.id = indic.intervenant_id i.histo_destruction IS NULL
            JOIN structure s ON s.id = indic.structure_id

          WHERE
        ";


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

        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $structure = null;
        if ($role) {
            $structure = $role->getStructure();
        }
        if ($structure) {
            $sign = $indicateur->isNotStructure() ? '<>' : '=';
            $qb->andWhere('indicateur.structure ' . $sign . ' ' . $structure->getId());
        }

        return $qb;
    }



    /**
     * @param integer|Indicateur $indicateur Indicateur concerné
     */
    public function getCount(Indicateur $indicateur)
    {
        $select = "COUNT(DISTINCT i.id) NB";
        $data   = $this->fetchData($indicateur, $select);

        return (integer)$data[0]['NB'];
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