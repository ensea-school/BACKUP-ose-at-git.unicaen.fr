<?php

namespace Mission\Service;

use Application\Entity\Db\Intervenant;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use Doctrine\ORM\Query;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;

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
    use TypeVolumeHoraireServiceAwareTrait;
    use SourceServiceAwareTrait;

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



    public function query(array $parameters): Query
    {
        $dql = "
        SELECT 
          m, tm, str, tr, valid, vh, vvh, ctr, tvh
        FROM 
          " . Mission::class . " m
          JOIN m.typeMission tm
          JOIN m.structure str
          JOIN m.tauxRemu tr
          LEFT JOIN m.validations valid WITH valid.histoDestruction IS NULL
          LEFT JOIN m.volumesHoraires vh WITH vh.histoDestruction IS NULL
          LEFT JOIN vh.typeVolumeHoraire tvh
          LEFT JOIN vh.validations vvh WITH vvh.histoDestruction IS NULL
          LEFT JOIN vh.contrat ctr WITH ctr.histoDestruction IS NULL
        WHERE 
          m.histoDestruction IS NULL 
          " . dqlAndWhere([
                'intervenant' => 'm.intervenant',
                'mission'     => 'm',
            ], $parameters) . "
        ORDER BY
          m.dateDebut,
          vh.histoCreation
        ";

        return $this->getEntityManager()->createQuery($dql)->setParameters($parameters);
    }



    public function suivi(array $parameters): Query
    {
        $parameters['typeVolumeHoraireRealise'] = TypeVolumeHoraire::CODE_REALISE;

        $dql = "
        SELECT
            vhm, m
        FROM
            " . VolumeHoraireMission::class . " vhm
            JOIN vhm.typeVolumeHoraire tvh WITH tvh.code = :typeVolumeHoraireRealise
            JOIN vhm.mission m
        WHERE
            vhm.histoDestruction IS NULL
            " . dqlAndWhere([
                'intervenant' => 'm.intervenant',
                'volumeHoraireMission' => 'vhm',
            ], $parameters) . "
        ";

        return $this->getEntityManager()->createQuery($dql)->setParameters($parameters);
    }



    /**
     * @param Mission $entity
     *
     * @return Mission
     */
    public function save($entity)
    {
        foreach ($entity->getVolumesHorairesPrevus() as $vh) {
            $this->saveVolumeHoraire($vh);
        }

        parent::save($entity);

        return $entity;
    }



    public function saveVolumeHoraire(VolumeHoraireMission $vhm): self
    {
        if (!$vhm->getTypeVolumeHoraire()) {
            $vhm->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());
        }
        if (!$vhm->getSource()) {
            $vhm->setSource($this->getServiceSource()->getOse());
        }

        $this->getEntityManager()->persist($vhm);
        $this->getEntityManager()->flush($vhm);

        return $this;
    }



    public function deleteVolumeHoraire(VolumeHoraireMission $volumeHoraireMission): self
    {
        $volumeHoraireMission->historiser();
        $this->saveVolumeHoraire($volumeHoraireMission);

        return $this;
    }

}