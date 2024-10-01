<?php

namespace Contrat\Service;


use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Contrat\Entity\Db\TblContrat;
use Intervenant\Entity\Db\Intervenant;


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

        $dql = "SELECT tblc FROM " . TblContrat::class . " tblc
        WHERE tblc.intervenant = :intervenant
        AND tblc.actif = 1";

        if ($structure != null) {
            $dql .= " AND tblc.structure = :structure OR tblc.structure IS NULL";
        }

        $query = $em->createQuery($dql)
            ->setParameter('intervenant', $intervenant);
        if ($structure != null) {
            $query->setParameter('structure', $structure);
        }
        return $query->getResult();
    }



    public function getVolumeTotalCreationContratByUuid(string $uuid): ?array
    {
        $em = $this->getEntityManager();

        $dql = "SELECT SUM(tblc.hetd) AS hetdTotal, tblc.uuid, tblc.intervenant, s.id AS structureId, tblc.dateDebut, tblc.dateFin, cp.id AS contratParentId, tc.id AS typeContratId
        FROM " . TblContrat::class . " tblc
        LEFT JOIN tblc.structure s
        LEFT JOIN tblc.contratParent cp
        JOIN tblc.typeContrat tc
        WHERE tblc.uuid = :uuid
        AND tblc.actif = 1
        GROUP BY tblc.uuid, tblc.intervenant, s.id, tblc.dateDebut, tblc.dateFin, cp.id, tc.id";

        $query = $em->createQuery($dql)
            ->setParameter('uuid', $uuid);

        return $query->getOneOrNullResult();
    }
}