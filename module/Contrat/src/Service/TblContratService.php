<?php

namespace Contrat\Service;


use Application\Service\AbstractEntityService;
use Contrat\Entity\Db\TblContrat;
use Intervenant\Entity\Db\Intervenant;
use RuntimeException;


/**
 * Description of TblContratService
 *
 */
class TblContratService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Contrat\Entity\Db\TblContrat::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tbl_c';
    }



    public function getContratVolumeHoraireByIntervenant(Intervenant $intervenant, $structure = null)
    {
        $em = $this->getEntityManager();

        $dql = "SELECT tblc FROM " . TblContrat::class . " tblc ";

        if ($structure != null) {
            $dql .= "LEFT JOIN tblc.structure structure ";
        }

        $dql .= "WHERE tblc.intervenant = :intervenant
        AND tblc.actif = 1";


        if ($structure != null) {
            $dql .= " AND (structure.ids LIKE :structure OR tblc.structure IS NULL)";
        }

        $query = $em->createQuery($dql)
            ->setParameter('intervenant', $intervenant);
        if ($structure != null) {
            $query->setParameter('structure', $structure->idsFilter());
        }
        return $query->getResult();
    }



    public function getVolumeTotalCreationContratByUuid(string $uuid): ?array
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT SUM(tblc.hetd) AS hetdTotal, tblc.uuid, i.id AS intervenantId, s.id AS structureId, MIN(tblc.dateDebut) AS dateDebut, MAX(tblc.dateFin) AS dateFin, cp.id AS contratParentId, tc.code AS typeContratCode
        FROM ' . TblContrat::class . ' tblc
        JOIN tblc.typeContrat tc
        JOIN tblc.intervenant i
        LEFT JOIN tblc.structure s
        LEFT JOIN tblc.contratParent cp
        WHERE tblc.uuid = :uuid
        AND tblc.actif = 1
        GROUP BY tblc.uuid, i.id, s.id, cp.id, tc.code';


        $query = $em->createQuery($dql)
            ->setParameter('uuid', $uuid);

        return $query->getOneOrNullResult();
    }



    public function getTotalHetdIntervenant(string $uuid, ?string $contratParentId): ?array
    {
        $em = $this->getEntityManager();

        $sql = 'SELECT SUM(hetd) as hetdTotal
                FROM tbl_contrat tblc_all 
                WHERE (tblc_all.contrat_id = :contratParentId 
                OR tblc_all.contrat_parent_id = :contratParentId)
                AND (tblc_all.contrat_id IS NOT NULL OR tblc_all.uuid = :uuid)';


        $query = $em->createQuery($sql)
            ->setParameter('uuid', $uuid);

        $result = $query->getOneOrNullResult();
        return $result['hetdTotal'];

    }



    public function getStructureContractualise(Intervenant $intervenant)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT s.id FROM ' . TblContrat::class . ' tblc 
        JOIN tblc.structure s
        WHERE tblc.intervenant = :intervenant AND tblc.actif = 1 AND tblc.contrat IS NOT NULL';

        $query = $em->createQuery($dql)
            ->setParameter('intervenant', $intervenant);
        return $query->getResult();


    }
}