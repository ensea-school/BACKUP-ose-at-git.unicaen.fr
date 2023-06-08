<?php

namespace Mission\Service;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Provider\Privilege\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\OffreEmploi;
use UnicaenVue\View\Model\AxiosModel;

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
    public function getEntityClass (): string
    {
        return OffreEmploi::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias (): string
    {
        return 'oe';
    }



    public function data (array $parameters, ?Role $role = null)
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

        $query = $this->getEntityManager()->createQuery($dql)->setParameters($parameters);

        $triggers = $this->getOffreEmploiPrivileges();

        $properties = [
            'id',
            ['typeMission', ['libelle']],
            'dateDebut',
            'dateFin',
            ['structure', ['libelleLong', 'libelleCourt', 'code', 'id']],
            'titre',
            'description',
            'nombreHeures',
            'nombrePostes',
            'histoCreation',
            'histoCreateur',
            'validation',
            'candidats',
            'candidaturesValides',
            'valide',
            ['candidatures', ['id', 'motif', ['intervenant', ['id', 'nomUsuel', 'prenom', 'emailPro', 'code', ['structure', ['libelleLong', 'libelleCourt', 'code', 'id']], ['statut', ['libelle', 'code']]]], 'histoCreation', 'validation']],
        ];


        return new AxiosModel($query, $properties, $triggers);
    }



    public function getOffreEmploiPrivileges (): array
    {


        return [
            '/' => function (OffreEmploi $offre, array $extracted) {

                $extracted['canModifier']   = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_MODIFIER);
                $extracted['canValider']    = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_VALIDER);
                $extracted['canPostuler']   = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_POSTULER);
                $extracted['canVisualiser'] = true;
                $extracted['canSupprimer']  = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION);

                return $extracted;
            },

        ];
    }



    public function dataPublic (array $parameters, ?Role $role = null)
    {


        $dql = "
        SELECT 
          oe, tm, str, c
        FROM 
          " . OffreEmploi::class . " oe
          JOIN oe.typeMission tm
          JOIN oe.structure str
          JOIN oe.validation v
          LEFT JOIN oe.candidatures c  
        WHERE 
          oe . histoDestruction IS null
        AND v.histoDestruction IS NULL
       ";

        $dql .= dqlAndWhere([
            'offreEmploi' => 'oe',
        ], $parameters);

        $dql .= " ORDER BY
          oe . dateDebut
        ";

        $query    = $this->getEntityManager()->createQuery($dql)->setParameters($parameters);
        $triggers = $this->getOffreEmploiPrivileges();


        $properties = [
            'id',
            ['typeMission', ['libelle']],
            'dateDebut',
            'dateFin',
            ['structure', ['libelleLong', 'libelleCourt', 'code', 'id']],
            'titre',
            'description',
            'nombreHeures',
            'nombrePostes',
            'histoCreation',
            'histoCreateur',
            'validation',
            'valide',
            'candidaturesValides',
        ];


        return new AxiosModel($query, $properties, $triggers);
    }



    /**
     * @param OffreEmploi $entity
     *
     * @return OffreEmploi
     */
    public function save ($entity)
    {
        parent::save($entity);

        return $entity;
    }

}