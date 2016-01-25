<?php

namespace Application\Service;
use Application\Entity\Db\Structure as StructureEntity;

/**
 * Description of FormuleResultat
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleResultat extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\FormuleResultat::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'form_r';
    }



    /**
     * Retourne le volume d'heures prévisionnelles faites pour une structure donnée, en année universitaire (par défaut)
     * ou bien par année civile en appliquant la règle des 4/10 / 6/10.
     *
     * @param StructureEntity $structure
     *
     * @return float
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getTotalPrevisionnelValide(StructureEntity $structure = null)
    {
        if (!$structure) return $this->getTotalPrevisionnelValideWS(); // on ByPasse!!!

        $params = [
             'structure' => (integer)$structure->getId(),
             'annee' => (integer)$this->getServiceContext()->getAnnee()->getId(),
        ];

        $sql         = 'SELECT heures FROM V_HETD_PREV_VAL_STRUCT WHERE structure_id = :structure AND annee_id = :annee';
        $sr = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();

        if (isset($sr['HEURES'])){
            return (float)$sr['HEURES'];
        }else{
            return (float)0;
        }
    }



    private function getTotalPrevisionnelValideWS()
    {
        $params = [
            'annee' => (integer)$this->getServiceContext()->getAnnee()->getId(),
        ];

        $sql         = 'SELECT structure_id, heures FROM V_HETD_PREV_VAL_STRUCT WHERE annee_id = :annee';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);

        $res = ['total' => 0];
        while( $d = $stmt->fetch()){
            $structureId = (int)$d['STRUCTURE_ID'];
            $heures = (float)$d['HEURES'];

            $res[$structureId] = $heures;
            $res['total'] += $heures;
            $res['total'] += $heures;
        }

        return $res;
    }
}