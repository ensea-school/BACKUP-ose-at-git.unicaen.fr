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

        $dql = 'SELECT SUM(tblc.hetd) AS hetdTotal, tblc.uuid, i.id AS intervenantId, s.id AS structureId, tblc.dateDebut, tblc.dateFin, cp.id AS contratParentId, tc.code AS typeContratCode
        FROM ' . TblContrat::class . ' tblc
        JOIN tblc.typeContrat tc
        JOIN tblc.intervenant i
        LEFT JOIN tblc.structure s
        LEFT JOIN tblc.contratParent cp
        WHERE tblc.uuid = :uuid
        AND tblc.actif = 1
        GROUP BY tblc.uuid, i.id, s.id, tblc.dateDebut, tblc.dateFin, cp.id, tc.code';


        $query = $em->createQuery($dql)
            ->setParameter('uuid', $uuid);

        return $query->getOneOrNullResult();
    }
}