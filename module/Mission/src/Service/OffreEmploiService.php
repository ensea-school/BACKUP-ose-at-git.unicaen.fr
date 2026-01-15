<?php

namespace Mission\Service;

use Application\Provider\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
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



    public function data(array $parameters)
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

        if (!$this->getServiceContext()->getAffectation()) {
            $dql .= " AND oe.validation IS NOT NULL";
            $dql .= " AND oe.dateLimite >= CURRENT_DATE()";
        }


        $dql .= dqlAndWhere([
                                'offreEmploi' => 'oe',
                                'annee'       => 'tm.annee',
                                'structure'   => 'str.ids',

                            ], $parameters);

        $dql .= " ORDER BY
          oe . dateDebut DESC
        ";

        $query = $this->getEntityManager()->createQuery($dql)->setParameters($parameters);

        $triggers = $this->getOffreEmploiPrivileges(true);

        $properties = [
            'id',
            ['typeMission',
             ['libelle']],
            'dateDebut',
            'dateFin',
            'dateLimite',
            ['structure',
             ['libelleLong',
              'libelleCourt',
              'code',
              'id']],
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
            ['candidatures',
             ['id',
              'motif',
              ['intervenant',
               ['id',
                'nomUsuel',
                'prenom',
                'emailPro',
                'code',
                ['structure',
                 ['libelleLong',
                  'libelleCourt',
                  'code',
                  'id']],
                ['statut',
                 ['libelle',
                  'code']]]],
              'histoCreation',
              'validation']],
        ];


        return new AxiosModel($query, $properties, $triggers);
    }



    public function getOffreEmploiPrivileges(bool $public = false): array
    {

        if ($public) {
            return [
                '/' => function (OffreEmploi $offre, array $extracted) {


                    $extracted['canModifier']   = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_MODIFIER);
                    $extracted['canValider']    = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_VALIDER);
                    $extracted['canPostuler']   = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_POSTULER);
                    $extracted['canVisualiser'] = true;
                    $extracted['canSupprimer']  = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION);
                    $extracted['decretText']    = ($this->getServiceContext()->getIntervenant()) ? $this->getServiceContext()->getIntervenant()->getStatut()->getMissionDecret() : '';

                    return $extracted;
                },

            ];
        }


        return [
            '/' => function (OffreEmploi $offre, array $extracted) {

                $extracted['canModifier']   = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_MODIFIER);
                $extracted['canValider']    = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_VALIDER);
                $extracted['canPostuler']   = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_POSTULER);
                $extracted['canVisualiser'] = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_POSTULER);
                $extracted['canSupprimer']  = $this->getAuthorize()->isAllowed($offre, Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION);
                $extracted['decretText']    = ($this->getServiceContext()->getIntervenant()) ? $this->getServiceContext()->getIntervenant()->getStatut()->getMissionDecret() : '';

                return $extracted;
            },

        ];
    }



    public function dataPublic(array $parameters)
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
        AND oe.dateLimite >= CURRENT_DATE()-1
       ";

        $dql .= dqlAndWhere([
                                'offreEmploi' => 'oe',
                                'annee'       => 'tm.annee',
                            ], $parameters);

        $dql .= " ORDER BY
          oe . dateDebut DESC
        ";


        $query = $this->getEntityManager()->createQuery($dql)->setParameters($parameters);


        $triggers = $this->getOffreEmploiPrivileges();


        $properties = [
            'id',
            ['typeMission',
             ['libelle']],
            'dateDebut',
            'dateFin',
            'dateLimite',
            ['structure',
             ['libelleLong',
              'libelleCourt',
              'code',
              'id']],
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
    public function save($entity)
    {
        parent::save($entity);

        return $entity;
    }

}