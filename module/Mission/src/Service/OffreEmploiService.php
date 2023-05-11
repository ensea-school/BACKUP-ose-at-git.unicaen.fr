<?php

namespace Mission\Service;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Provider\Privilege\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\OffreEmploi;

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
          oe, tm, str, c
        FROM 
          " . OffreEmploi::class . " oe
          JOIN oe.typeMission tm
          JOIN oe.structure str
          LEFT JOIN oe.candidatures c  
        WHERE 
          oe . histoDestruction IS null
       ";

        if (empty($role)) {
            $dql .= " AND oe.validation IS NOT NULL";
        }


        $dql .= dqlAndWhere([
            'offreEmploi' => 'oe',
        ], $parameters);

        $dql .= " ORDER BY
          oe . dateDebut
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



    public function getOffreEmploiPrivileges(): array
    {


        return [
            '/' => function (OffreEmploi $offre, array $extracted) {
                $extracted['canModifier']   = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_MODIFIER);
                $extracted['canValider']    = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_VALIDER);
                $extracted['canPostuler']   = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_POSTULER);
                $extracted['canVisualiser'] = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION);
                $extracted['canSupprimer']  = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION);


                /*$extracted['canValider']   = $this->isAllowed($original, Privileges::MISSION_VALIDATION);
                $extracted['canDevalider'] = $this->isAllowed($original, Privileges::MISSION_DEVALIDATION);
                $extracted['canSupprimer'] = $this->isAllowed($original, Privileges::MISSION_EDITION);*/

                return $extracted;
            },

        ];
    }

}