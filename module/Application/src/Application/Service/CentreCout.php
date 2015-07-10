<?php

namespace Application\Service;

use Application\Entity\Db\CentreCout as CentreCoutEntity;
use Application\Entity\Db\TypeHeures as TypeHeuresEntity;
use Application\Service\Traits\TypeHeuresAwareTrait;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of CentreCout
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CentreCout extends AbstractEntityService
{
    use TypeHeuresAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\CentreCout';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'cc';
    }

    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param TypeHeuresEntity  $typeHeures
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByTypeHeures(TypeHeuresEntity $typeHeures, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $this->join( $this->getServiceTypeHeures(), $qb, 'typeHeures', false, $alias );
        $qb->andWhere( $this->getServiceTypeHeures()->getAlias().' = :typeHeures');
        $qb->setParameter('typeHeures', $typeHeures);


        return $qb;
    }

    /**
     * Formatte une liste d'entités CentreCout (centres de coûts et éventuels EOTP fils)
     * en tableau attendu par l'aide de vue FormSelect.
     *
     * NB: la liste en entrée doit être triées par code parent (éventuel) PUIS par code.
     *
     * @param CentreCoutEntity[] $centresCouts
     */
    public function formatCentresCouts($centresCouts)
    {
        $result = [];

        foreach ($centresCouts as $cc) {
            $id = $cc->getId();
            $ccp = $cc->getParent() ?: null;
            $idParent = $ccp ? $ccp->getId() : null;

            if ($idParent) {
                $result[$idParent]['label'] = (string)$ccp;
                $result[$idParent]['options'][$id] = (string)$cc;
            } else {
                $result[$id]['label'] = (string)$cc;
                $result[$id]['options'][$id] = (string)$cc;
            }
        }

        // parcours pour supprimer le niveau 2 lorsque le centre de coûts n'a pas d'EOTP fils
        foreach ($result as $id => $data) {
            if (isset($data['options']) && count($data['options']) === 1) {
                $result[$id] = $data['label'];
            }
        }

        ksort($result);

        return $result;
    }
}