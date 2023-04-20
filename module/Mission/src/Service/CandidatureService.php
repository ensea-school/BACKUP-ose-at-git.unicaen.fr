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



    /*public function query(array $parameters, ?Role $role = null)
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
       " . dqlAndWhere([
                'offreEmploi' => 'oe',
            ], $parameters);

        $dql .= " ORDER BY
          oe . dateDebut
        ";

        return $this->getEntityManager()->createQuery($dql)->setParameters($parameters);
    }*/


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