<?php

namespace Mission\Service;

use Application\Acl\Role;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\OffreEmploi;
use Mission\Entity\Db\VolumeHoraireMission;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;

/**
 * Description of OffreEmploiService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 *
 * @method OffreEmploi get($id)
 * @method OffreEmploi[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method OffreEmploi newEntity()
 *
 */
class OffreEmploiService extends AbstractEntityService
{
    use SourceServiceAwareTrait;

    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getEntityClass(): string
    {
        return OffreEmploi::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'oe';
    }



    public function query(array $parameters, ?Role $role = null)
    {


        $dql = "
        SELECT 
          oe, tm, str
        FROM 
          " . OffreEmploi::class . " oe
          JOIN oe.typeMission tm
          JOIN oe.structure str
         WHERE 
          oe.histoDestruction IS NULL 
       " . dqlAndWhere([
                'offreEmploi' => 'oe',
            ], $parameters);

        $dql .= "ORDER BY
          oe.dateDebut
        ";

        return $this->getEntityManager()->createQuery($dql)->setParameters($parameters);
    }



    /**
     * @param OffreEmploi $entity
     *
     * @return OffreEmploi
     */
    public function save($entity)
    {
        parent::save($entity);

        return $entity;
    }

}