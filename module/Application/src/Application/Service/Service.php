<?php

namespace Application\Service;

use Application\Service\AbstractService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Annee;


/**
 * Description of Service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Service extends AbstractService
{

    /**
     * Repository
     *
     * @var Repository
     */
    protected $repo;





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
     * Retourne la liste des services selon le contexte donné
     *
     * @param array $context
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByContext( array $context, QueryBuilder $qb=null )
    {
        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder('s');

        if (! empty($context['intervenant']) && $context['intervenant'] instanceof Intervenant){
            $qb->andWhere('s.intervenant = :intervenant')->setParameter('intervenant', $context['intervenant']);
        }
        if (! empty($context['annee']) && $context['annee'] instanceof Annee){
            $qb->andWhere('s.annee = :annee')->setParameter('annee', $context['annee']);
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
            $this->repo = $this->getEntityManager()->getRepository('Application\Entity\Db\Service');
        }
        return $this->repo;
    }
}