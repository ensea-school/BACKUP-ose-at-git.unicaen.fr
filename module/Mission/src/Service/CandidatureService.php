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
 * Description of CandidatureService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 *
 * @method Candidature get($id)
 * @method Candidature[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Candidature newEntity()
 *
 */
class CandidatureService extends AbstractEntityService
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
        return Candidature::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'ca';
    }



    public function postuler(Intervenant $intervenant, OffreEmploi $offre): Candidature
    {

        $candidature = $this->newEntity();
        $candidature->setIntervenant($intervenant);
        $candidature->setOffre($offre);

        return $this->save($candidature);
    }



    public function data(array $parameters, ?Role $role = null)
    {
        $dql = "
        SELECT 
         c, i, o, v, str
        FROM 
          " . Candidature::class . " c
          JOIN c.intervenant i
          JOIN c.offre o
          JOIN o.structure str
          LEFT JOIN c.validation v
        WHERE 
          c . histoDestruction IS null
          AND v.histoDestruction IS NULL
       ";


        $dql .= dqlAndWhere([
            'intervenant' => 'i',
        ], $parameters);


        $query  = $this->getEntityManager()->createQuery($dql)->setParameters($parameters);
        $result = $query->getResult();

        /*$triggers = $this->getOffreEmploiPrivileges();*/
        $triggers = [];

        $properties = [
            'id',
            'motif',
            'validation',
            ['offre', ['id', 'typeMission', 'titre', ['structure', ['libelleLong']]]],
            ['intervenant', ['id', 'nomUsuel', 'prenom', 'emailPro', 'code', ['structure', ['libelleLong', 'libelleCourt', 'code', 'id']], ['statut', ['libelle', 'code']]]],
        ];


        return new AxiosModel($query, $properties, $triggers);
    }



    /**
     * @param Candidature $entity
     *
     * @return Candidature
     */
    public function save($entity)
    {
        parent::save($entity);

        return $entity;
    }

}