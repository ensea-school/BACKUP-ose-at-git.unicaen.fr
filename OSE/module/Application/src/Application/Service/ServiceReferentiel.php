<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\ServiceReferentiel as ServiceEntity;
use Application\Entity\Db\Finder\FinderServiceReferentiel;
use Application\Entity\Db\Finder\FinderServiceReferentielSum;

/**
 * Description of ServiceReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentiel extends AbstractService
{
    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param ServiceEntity $entity
     * @param bool $softDelete 
     * @return self
     */
    public function delete(ServiceEntity $entity, $softDelete = true)
    {
        if ($softDelete) {
            $entity->setHistoDestruction(new \DateTime);
        }
        else {
            $this->getEntityManager()->remove($entity);
        }
        
        $this->getEntityManager()->flush($entity);
        
        return $this;
    } 
    
    /**
     * Retourne le requêteur des services référentiels contraint par les critères spécifiés.
     *
     * @param array $filter
     * @return FinderServiceReferentiel
     */
    public function getFinder(array $filter = array())
    {
        $qb = new FinderServiceReferentiel(
                $this->getEntityManager(), 
                $this->getContextProvider(),
                $filter);

        return $qb;
    } 
    
    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\ServiceReferentiel';
    }
    
    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'seref';
    }

    /**
     * Retourne la liste des services selon le contexte donné
     *
     * @param Context $context
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByContext(Context $context, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        if (($intervenant = $context->getIntervenant())) {
            $qb->andWhere("$alias.intervenant = :intervenant")->setParameter('intervenant', $intervenant);
        }
        if (($annee = $context->getAnnee())) {
            $qb->andWhere("$alias.annee = :annee")->setParameter('annee', $annee);
        }

        return $qb;
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