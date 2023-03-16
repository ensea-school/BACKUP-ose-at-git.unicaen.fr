<?php

namespace Mission\Service;

use Application\Entity\Db\Intervenant;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Mission\Entity\MissionSuivi;
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



    public function query(array $parameters)
    {
        $dql = "
        SELECT 
          m, tm, str, tr, valid, vh, vvh, ctr
        FROM 
          " . Mission::class . " m
          JOIN m.typeMission tm
          JOIN m.structure str
          JOIN m.tauxRemu tr
          JOIN " . TypeVolumeHoraire::class . " tvh WITH tvh.code = :typeVolumeHorairePrevu
          LEFT JOIN m.validations valid WITH valid.histoDestruction IS NULL
          LEFT JOIN m.volumesHoraires vh WITH vh.histoDestruction IS NULL AND vh.typeVolumeHoraire = tvh
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

        $parameters['typeVolumeHorairePrevu'] = TypeVolumeHoraire::CODE_PREVU;

        return $this->getEntityManager()->createQuery($dql)->setParameters($parameters);
    }



    public function suivi(Intervenant $intervenant, ?string $guid=null): array|MissionSuivi|null
    {
        $parameters = [
            'typeVolumeHoraireRealise' => TypeVolumeHoraire::CODE_REALISE,
            'intervenant' => $intervenant,
        ];

        if ($guid){
            $date = VolumeHoraireMission::guidDate($guid);
            $from = new \DateTime($date->format("Y-m-d")." 00:00:00");
            $to   = new \DateTime($date->format("Y-m-d")." 23:59:59");

            $dateFilter = "AND vhm.horaireDebut BETWEEN :from AND :to";
            $dateFilter .= "\nAND vhm.horaireFin BETWEEN :from AND :to";

            $parameters['from'] = $from;
            $parameters['to'] = $to;
        }else{
            $dateFilter = '';
        }

        $dql = "
        SELECT
            vhm, m
        FROM
            ".VolumeHoraireMission::class." vhm
            JOIN vhm.typeVolumeHoraire tvh WITH tvh.code = :typeVolumeHoraireRealise
            JOIN vhm.mission m
        WHERE
            vhm.histoDestruction IS NULL
            AND m.intervenant = :intervenant
            $dateFilter
        ";

        /** @var VolumeHoraireMission[] $vhms */
        $vhms = $this->getEntityManager()->createQuery($dql)->setParameters($parameters)->execute();

        $suivis = [];
        foreach( $vhms as $vhm ){
            $vhmGuid = $vhm->guid();
            if (!array_key_exists($vhmGuid, $suivis)){
                $suivis[$vhmGuid] = new MissionSuivi();
            }
            $suivis[$vhmGuid]->addVolumeHoraire($vhm);
        }

        if ($guid){
            if (array_key_exists($guid, $suivis)){
                return $suivis[$guid];
            }else{
                return null;
            }
        }else{
            return $suivis;
        }
    }



    /**
     * @param Mission $entity
     *
     * @return Mission
     */
    public function save($entity)
    {
        foreach ($entity->getVolumesHoraires() as $vh) {
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



    public function saveSuivi(MissionSuivi $missionSuivi)
    {
        foreach( $missionSuivi->getVolumesHoraires() as $vhm ){
            $this->saveVolumeHoraire($vhm);
        }
    }



    public function deleteVolumeHoraire(VolumeHoraireMission $volumeHoraireMission): self
    {
        $volumeHoraireMission->historiser();
        $this->saveVolumeHoraire($volumeHoraireMission);

        return $this;
    }

}