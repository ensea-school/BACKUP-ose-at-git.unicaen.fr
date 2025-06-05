<?php

namespace Paiement\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Application\Util;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\Structure;
use Paiement\Entity\Db\Dotation;
use Paiement\Entity\Db\TypeRessource;

/**
 * Description of DotationService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class DotationService extends AbstractEntityService
{
    use TypeRessourceServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Dotation::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'dota';
    }



    /**
     * Retourne, dans un tableau, le nombre d'heures abondées budgétairement par structure et par type de ressource
     */
    public function getTableauBord(array $structures = []): array
    {
        $sql = "
        SELECT
            structure_id,
            type_ressource_id,
            SUM(heures) heures
        FROM
          dotation d
        WHERE
          d.histo_destruction IS NULL
          AND d.annee_id = :annee
          ".Util::sqlAndIn('structure_id', $structures)."
        GROUP BY
          structure_id, type_ressource_id
        ";

        $res = ['total' => 0];
        $params = ['annee' => $this->getServiceContext()->getAnnee()->getId()];
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
        while( $d = $stmt->fetchAssociative()){
            $structureId = (int)$d['STRUCTURE_ID'];
            $typeRessourceId = (int)$d['TYPE_RESSOURCE_ID'];
            $heures = (float)$d['HEURES'];

            $res[$structureId][$typeRessourceId] = $heures;
            if (!isset($res[$structureId]['total'])) $res[$structureId]['total'] = 0;
            $res[$structureId]['total'] += $heures;
            $res['total'] += $heures;
        }

        if ($res['total'] == 0) {
            return [];
        }

        return $res;
    }



    public function getDotations(Structure $structure)
    {
        $annee = $this->getServiceContext()->getAnnee();
        $ac1    = $annee->getId();
        $ac2    = $annee->getId() + 1;

        $typesRessources = $this->getServiceTypeRessource()->getList();

        $qb = $this->initQuery()[0];
        /* @var $qb QueryBuilder */
        $qb->andWhere($qb->expr()->in($this->getAlias() . ".anneeCivile", [$ac1, $ac2]));
        $this->finderByStructure($structure, $qb);
        $ds = $this->getList($qb);

        $res = [
            'typesRessources' => [],
            'total'           => $this->initDotationArray(),
        ];

        foreach ($typesRessources as $typeRessource) {
            /* @var $typeRessource TypeRessource */
            $res['typesRessources'][$typeRessource->getId()] = [
                'entity'      => $typeRessource,
                'abondements' => [],
                'total'       => $this->initDotationArray(),
            ];
        }

        foreach ($ds as $d) {
            /* @var $d Dotation */
            $trKey = $d->getTypeRessource()->getId();
            $abKey = $d->getLibelle();
            if ($d->getAnneeCivile() == $ac1){
                $acKey = 'anneeCivile1';
                $auKey = ($d->getAnnee() == $annee) ? 's2' : 's1';
            }else{
                $acKey = 'anneeCivile2';
                $auKey = ($d->getAnnee() == $annee) ? 's1' : 's2';
            }

            if (!isset($res['typesRessources'][$trKey]['abondements'][$abKey])) {
                $res['typesRessources'][$trKey]['abondements'][$abKey] = $this->initDotationArray( $d->getLibelle() );
            }

            $res['typesRessources'][$trKey]['abondements'][$abKey][$acKey][$auKey] += $d->getHeures();
            $res['typesRessources'][$trKey]['abondements'][$abKey][$acKey][$auKey.'Entity'] = $d;
            $res['typesRessources'][$trKey]['abondements'][$abKey][$acKey]['heures'] += $d->getHeures();

            $res['typesRessources'][$trKey]['total'][$acKey][$auKey] += $d->getHeures();
            $res['typesRessources'][$trKey]['total'][$acKey]['heures'] += $d->getHeures();

            $res['total'][$acKey][$auKey] += $d->getHeures();
            $res['total'][$acKey]['heures'] += $d->getHeures();

            if (substr($acKey, -1) != substr($auKey,-1)) { // si 1 <> 2
                $res['typesRessources'][$trKey]['abondements'][$abKey]['heures'] += $d->getHeures();
                $res['typesRessources'][$trKey]['total']['heures'] += $d->getHeures();
                $res['total']['heures'] += $d->getHeures();
            }
        }

        return $res;
    }



    protected function initDotationArray($libelle = null)
    {
        $annee = $this->getServiceContext()->getAnnee();

        return [
            'anneeCivile1' => [
                'annee'  => $annee->getId(),
                's1'     => 0,
                's2'     => 0,
                'heures' => 0,
            ],
            'anneeCivile2' => [
                'annee'  => $annee->getId() + 1,
                's1'     => 0,
                's2'     => 0,
                'heures' => 0,
            ],
            'libelle'      => $libelle,
            'annee'        => $annee,
            'heures'       => 0,
        ];
    }

}