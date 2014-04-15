<?php

namespace Application\Service;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Annee;
use Application\Entity\Db\ServiceReferentiel as ServiceEntity;


/**
 * Description of ServiceReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentiel extends AbstractService
{
    /**
     * @var EntityRepository
     */
    protected $repo;

    /**
     * Retourne la liste des services selon le contexte donné
     *
     * @param Context $context
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByContext(Context $context, QueryBuilder $qb = null)
    {
        if (empty($qb)) {
            $qb = $this->getRepo()->createQueryBuilder('sr');
        }

        if (($intervenant = $context->getIntervenant())) {
            $qb->andWhere('sr.intervenant = :intervenant')->setParameter('intervenant', $intervenant);
        }
        if (($annee = $context->getAnnee())) {
            $qb->andWhere('sr.annee = :annee')->setParameter('annee', $annee);
        }
        
        return $qb;
    }

    /**
     *
     * @return EntityRepository
     */
    public function getRepo()
    {
        if( empty($this->repo) ){
            $this->getEntityManager()->getFilters()->enable("historique");
            $this->repo = $this->getEntityManager()->getRepository('Application\Entity\Db\ServiceReferentiel');
        }
        return $this->repo;
    }

    /**
     * Retourne, par ID du type d'intervention, la liste des heures saisies pour le service donné
     *
     * @param integer|ServiceEntity|null $service
     * @return array
     */
    public function getTotalHeures($service)
    {
        if ($service instanceof ServiceEntity) {
            $service = $service->getId();
        }

//        $sql = 'SELECT * FROM V_SERVICE_HEURES';
//        if ($service) $sql .= ' WHERE service_id = '.(int)$service;
//
//        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
//
//        $result = array();
//        while($r = $stmt->fetch()){
//            $result[(int)$r['SERVICE_ID']][(int)$r['TYPE_INTERVENTION_ID']] = (float)$r['HEURES'];
//        }
//
//        if ($service){
//            if (array_key_exists( $service, $result)){
//                return $result[$service];
//            }else{
//                return array();
//            }
//        }
//        return $result;
        return array();
    }
}