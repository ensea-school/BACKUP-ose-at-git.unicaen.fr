<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeContrat as TypeContratEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;

/**
 * Description of Contrat
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Contrat extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Contrat';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'c';
    }

    /**
     * Retourne une nouvelle entité de la classe donnée
     * 
     * @param TypeContratEntity|string $type
     * @return \Application\Entity\Db\Contrat
     */
    public function newEntity($type = null)
    {
        $entity = parent::newEntity();
        $entity->setTypeContrat($this->normalizeTypeContrat($type));
        
        return $entity;
    }

    /**
     * Recherche par type 
     *
     * @param TypeContratEntity|string $type
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByType($type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.typeContrat", 'tc')
                ->andWhere("tc = :tc")
                ->setParameter('tc', $this->normalizeTypeContrat($type));

        return $qb;
    }
    
    /**
     * Recherche par intervenant concerné. 
     *
     * @param IntervenantEntity $intervenant
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByIntervenant(IntervenantEntity $intervenant, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.intervenant", 'i')
                ->andWhere("i = :intervenant")
                ->setParameter('intervenant', $intervenant);

        return $qb;
    }
    
    /**
     * 
     * @param TypeContrat|string $type
     * @return TypeContratEntity
     * @throws RuntimeException
     */
    private function normalizeTypeContrat($type)
    {
        if (null === $type) {
            return null;
        }
        if ($type instanceof TypeContratEntity) {
            return $type;
        }
        
        $qb = $this->getServiceLocator()->get('ApplicationTypeContrat')->finderByCode($code = $type);
        $type = $qb->getQuery()->getOneOrNullResult();
        if (!$type) {
            throw new RuntimeException("Aucun type de contrat trouvé avec le code '$code'.");
        }
        
        return $type;
    }
}