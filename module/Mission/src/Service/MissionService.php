<?php

namespace Mission\Service;

use Application\Controller\Plugin\Axios;
use Application\Entity\Db\Intervenant;
use Application\Hydrator\GenericHydrator;
use Application\Service\AbstractEntityService;
use Mission\Entity\Db\Mission;

/**
 * Description of MissionService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 *
 * @method Mission get($id)
 * @method Mission[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Mission newEntity()
 *
 */
class MissionService extends AbstractEntityService
{

    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getEntityClass(): string
    {
        return Mission::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'm';
    }



    /**
     * @param Intervenant $intervenant
     *
     * @return array|Mission[]
     */
    public function missionsByIntervenant(Intervenant $intervenant): array
    {
        $dql = "
        SELECT 
          m, tm, str, tr, valid, vh, ctr
        FROM 
          " . Mission::class . " m
          JOIN m.typeMission tm
          JOIN m.structure str
          JOIN m.missionTauxRemu tr
          LEFT JOIN m.validations valid
          LEFT JOIN m.volumesHoraires vh
          LEFT JOIN vh.contrat ctr
        WHERE 
            m.histoDestruction IS NULL 
            AND m.intervenant = :intervenant
        ";

        $missions = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameters(compact('intervenant'))
            ->getResult();

        return $missions;
    }



    public function missionWs(Mission $mission)
    {
        $json = Axios::extract($mission, [
            'typeMission',
            'dateDebut',
            'dateFin',
            'structure',
            'missionTauxRemu',
            'description',
            'histoCreation',
            ['histoCreateur', ['email', 'displayName']],
            'heures',
            'heuresValidees',
            'contrat',
            'valide',
            ['validation', ['histoCreation', 'histoCreateur']],
        ]);

        $json['canSaisie']    = !$mission->isValide();
        $json['canValider']   = !$mission->isValide();
        $json['canDevalider'] = $mission->isValide();
        $json['canSupprimer'] = !$mission->isValide();

        return $json;
    }

}