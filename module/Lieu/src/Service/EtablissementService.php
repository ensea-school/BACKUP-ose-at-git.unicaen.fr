<?php

namespace Lieu\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\Etablissement;


/**
 * Description of EtablissementService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtablissementService extends AbstractEntityService
{

    public function getEntityClass()
    {
        return Etablissement::class;
    }



    public function getAlias(){
        return 'etab';
    }



    /**
     * Recherche par libellé
     *
     * @param string $term
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByLibelle($term, ?QueryBuilder $qb = null, $alias=null)
    {
        $terms = explode( ' ', $term );

        [$qb, $alias] = $this->initQuery($qb, $alias);

        $concatFields = [
            "$alias.libelle",
            "$alias.departement",
            "$alias.localisation",
        ];

        foreach ($concatFields as $field) {
            if (!isset($searchIn)) {
                $searchIn = $qb->expr()->concat($qb->expr()->literal(''), $field);
                continue;
            }

            $searchIn = $qb->expr()->concat(
                $searchIn,
                $qb->expr()->concat($qb->expr()->literal(' '), $field)
            );
        }

        $haystack   = new Func( 'CONVERT', [ $searchIn, '?1' ] );
        $parameters = [
            1 => 'US7ASCII'
        ];

        $index = 2;
        foreach($terms as $term ){
            $parameters[$index] = "%$term%";
            $qb->andWhere($qb->expr()->like($qb->expr()->upper($haystack), $qb->expr()->upper("CONVERT(?$index, ?1)")));
            $index++;
        }
        $qb->orderBy("$alias.libelle");
        $qb->setParameters( $parameters );

        return $qb;
    }
}