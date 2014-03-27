<?php

namespace Application\Entity\Db\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ElementPedagogiqueRepository
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see \Application\Entity\Db\Structure
 */
class ElementPedagogiqueRepository extends EntityRepository
{
    /**
     * Retourne le chercheur des structures distinctes.
     * 
     * @param int $niveau
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function distinctStructuresFinder($niveau = null)
    {
        $qb = $this->createQueryBuilder('ep')
                ->select('s.id, s.libelleCourt')
//                ->distinct()
                ->from('Application\Entity\Db\Structure', 's')
                ->innerJoin('s.elementPedagogique', 'ep')
                ->orderBy('s.libelleCourt');
        
        if (null !== $niveau) {
            $qb->where('s.niveau = ?', $niveau);
        }
        
        // provisoire
        $qb->where('s.parente = :ucbn')->setParameter('ucbn', $this->getEntityManager()->find('Application\Entity\Db\Structure', 8464));
        
        return $qb;
    }
    
    /**
     * Retourne le chercheur des niveaux distincts.
     * 
     * @param \Application\Entity\Db\Structure $structure
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function distinctNiveauxFinder(\Application\Entity\Db\Structure $structure = null)
    {
        $qb = $this->createQueryBuilder('ep')
                ->select('tf.libelleCourt, t.niveau')
                ->distinct()
                ->from('Application\Entity\Db\Etape', 'e')
                ->innerJoin('e.typeFormation', 'tf')
                ->innerJoin('tf.groupeTypeFormation', 'gtf')
                ->orderBy('gtf.ordre');
        
        if (null !== $structure) {
            $qb->andWhere('e.structure = ?', $structure);
        }
        
        return $qb;
    }
}