<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Etape;
use Application\Entity\Db\Service as ServiceEntity;

/**
 * Description of Service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Service extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Service';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 's';
    }

    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param Etape $etape
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByEtape( Etape $etape, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->join('Application\Entity\Db\ElementPedagogique', 'ep', \Doctrine\ORM\Query\Expr\Join::WITH, 'ep.id = s.elementPedagogique');
        $qb->andWhere('ep.etape = :etape')->setParameter('etape', $etape);
        return $qb;
    }

    /**
     * Retourne le contexte global des services
     */
    public function getGlobalContext()
    {
        $currentUser = $this->getServiceLocator()->get('authUserContext')->getDbUser();
        $parametres = $this->getServiceLocator()->get('ApplicationParametres');
        return array(
            'intervenant'   => $currentUser->getIntervenant(),
            'personnel'     => $currentUser->getPersonnel(),
            'annee'         => $this->getEntityManager()->getRepository('Application\Entity\Db\Annee')->find($parametres->annee),
            'etablissement' => $this->getEntityManager()->getRepository('Application\Entity\Db\Etablissement')->find($parametres->etablissement)
        );
    }

    /**
     * Retourne, par ID du type d'intervention, la liste des heures saisies pour le service donné
     *
     * @param integer|ServiceEntity|null $service
     * @return array
     */
    public function getTotalHeures($service)
    {
        if ($service instanceof ServiceEntity) $service = $service->getId();

        $sql = 'SELECT * FROM V_SERVICE_HEURES';
        if ($service) $sql .= ' WHERE service_id = '.(int)$service;

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);

        $result = array();
        while($r = $stmt->fetch()){
            $result[(int)$r['SERVICE_ID']][(int)$r['TYPE_INTERVENTION_ID']] = (float)$r['HEURES'];
        }

        if ($service){
            if (array_key_exists( $service, $result)){
                return $result[$service];
            }else{
                return array();
            }
        }
        return $result;
    }
}