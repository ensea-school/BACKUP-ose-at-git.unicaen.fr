<?php

namespace Plafond\Service;

use Application\Entity\Db\Annee;
use Application\Service\AbstractEntityService;
use Plafond\Entity\Db\PlafondApplication;
use Application\Service\Traits\AnneeServiceAwareTrait;

/**
 * Description of PlafondApplicationService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method PlafondApplication get($id)
 * @method PlafondApplication[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method PlafondApplication newEntity()
 *
 */
class PlafondApplicationService extends AbstractEntityService
{
    use AnneeServiceAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return PlafondApplication::class;
    }



    /**
     * @param PlafondApplication $plafondApplication
     *
     * @return Annee|null
     */
    public function derniereAnneeDebut(PlafondApplication $plafondApplication)
    {
        $params = [
            'id'                => $plafondApplication->getId() ?: 0,
            'plafond'           => $plafondApplication->getPlafond()->getId(),
            'typeVolumeHoraire' => $plafondApplication->getTypeVolumeHoraire()->getId(),
            'annee'             => $this->getServiceContext()->getAnnee()->getId(),
        ];

        $sql = "
        SELECT 
          MAX(annee_fin_id) annee_fin
        FROM 
          plafond_application papp
        WHERE
          papp.plafond_id = :plafond
          AND papp.type_volume_horaire_id = :typeVolumeHoraire
          AND papp.id <> :id
          AND papp.annee_fin_id IS NOT NULL
          AND papp.annee_fin_id < :annee
        ";
        $res = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();
        if ($res && (int)$res['ANNEE_FIN'] !== 0) {
            return $this->getServiceAnnee()->get((int)$res['ANNEE_FIN']);
        } else {
            return null;
        }
    }



    /**
     * @param PlafondApplication $plafondApplication
     *
     * @return Annee|null
     */
    public function premiereAnneeFin(PlafondApplication $plafondApplication)
    {
        $params = [
            'id'                => $plafondApplication->getId() ?: 0,
            'plafond'           => $plafondApplication->getPlafond()->getId(),
            'typeVolumeHoraire' => $plafondApplication->getTypeVolumeHoraire()->getId(),
            'annee'             => $this->getServiceContext()->getAnnee()->getId(),
        ];

        $sql = "
        SELECT 
          MAX(annee_debut_id) annee_debut
        FROM 
          plafond_application papp
        WHERE
          papp.plafond_id = :plafond
          AND papp.type_volume_horaire_id = :typeVolumeHoraire
          AND papp.id <> :id
          AND papp.annee_fin_id IS NOT NULL
          AND papp.annee_fin_id > :annee
        ";
        $res = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();
        if ($res && (int)$res['ANNEE_DEBUT'] !== 0) {
            return $this->getServiceAnnee()->get((int)$res['ANNEE_DEBUT']);
        } else {
            return null;
        }
    }



    /**
     * @param PlafondApplication $entity
     *
     * @return PlafondApplication
     */
    public function save($entity)
    {
        $sql = "
        SELECT 
          count(*) cc
        FROM 
          plafond_application papp
        WHERE
          papp.plafond_id = :plafond
          AND papp.type_volume_horaire_id = :typeVolumeHoraire
          AND papp.id <> :pappId
          AND (
               :ddeb BETWEEN COALESCE(papp.annee_debut_id,0) AND COALESCE(papp.annee_fin_id,99999)
            OR :dfin BETWEEN COALESCE(papp.annee_debut_id,0) AND COALESCE(papp.annee_fin_id,99999)
          )
        ";

        $ddeb = $entity->getAnneeDebut() ? $entity->getAnneeDebut()->getId() : 0;
        $dfin = $entity->getAnneeFin() ? $entity->getAnneeFin()->getId() : 99999;

        if ($dfin < $ddeb) {
            throw new \Exception('L\'année de fin ne peut être antérieure à l\'année de début');
        }

        $params = [
            'plafond'           => $entity->getPlafond()->getId(),
            'typeVolumeHoraire' => $entity->getTypeVolumeHoraire()->getId(),
            'ddeb'              => $ddeb,
            'dfin'              => $dfin,
            'pappId'            => (int)$entity->getId(),
        ];
        $res    = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();
        $no     = ($res['CC'] > 0);

        if ($no) {
            throw new \Exception('La règle de plafond ne peut pas être appliquée, car elle en chevauche une autre');
        }

        return parent::save($entity);
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'papp';
    }

}