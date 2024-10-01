<?php

namespace Paiement\Service;


use Application\Service\AbstractService;
use Lieu\Entity\Db\Structure;

/**
 * Description of BudgetService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class BudgetService extends AbstractService
{


    /**
     * Retourne le volume d'heures prévisionnelles faites pour une structure donnée, en année universitaire (par défaut)
     * ou bien par année civile en appliquant la règle des 4/10 / 6/10.
     *
     * @param Structure $structure
     *
     * @return float
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getTotalPrevisionnelValide(Structure $structure = null): float
    {
        if (!$structure) return $this->getTotalPrevisionnelValideWS(); // on ByPasse!!!

        $params = [
            'structure' => (integer)$structure->getId(),
            'annee'     => (integer)$this->getServiceContext()->getAnnee()->getId(),
        ];

        $sql = 'SELECT HEURES FROM V_HETD_PREV_VAL_STRUCT WHERE STRUCTURE_ID = :structure AND ANNEE_ID = :annee';
        $sr  = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();

        if (isset($sr['HEURES'])) {
            return (float)$sr['HEURES'];
        } else {
            return (float)0;
        }
    }




    private function getTotalPrevisionnelValideWS()
    {
        $params = [
            'annee' => (integer)$this->getServiceContext()->getAnnee()->getId(),
        ];

        $sql  = 'SELECT STRUCTURE_ID, HEURES FROM V_HETD_PREV_VAL_STRUCT WHERE ANNEE_ID = :annee';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);

        $res = ['total' => 0];
        while ($d = $stmt->fetch()) {
            $structureId = (int)$d['STRUCTURE_ID'];
            $heures      = (float)$d['HEURES'];

            $res[$structureId] = $heures;
            $res['total']      += $heures;
            $res['total']      += $heures;
        }

        return $res;
    }
}