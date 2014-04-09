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
     * @param array $context
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finder(array $context = array(), \Doctrine\ORM\QueryBuilder $qb = null)
    {
        if (!isset($context['structure'])) {
            throw new \Common\Exception\LogicException("Aucune structure spécifiée dans le contexte.");
        }
        if (!$context['structure'] instanceof \Application\Entity\Db\Structure) {
            throw new \Common\Exception\LogicException("La structure spécifiée dans le contexte n'est pas du type attendu.");
        }
        
        if (null === $qb) {
            $qb = $this->createQueryBuilder('ep');
        }
        
        $qb
                ->select('ep, e, tf, gtf, p')
                ->innerJoin('ep.periode', 'p')
                ->innerJoin('ep.etape', 'e')
                ->innerJoin('e.typeFormation', 'tf')
                ->innerJoin('tf.groupe', 'gtf')
                ->innerJoin('ep.structure', 's')
                ->where('s.structureNiv2 = :structure')->setParameter('structure', $context['structure'])
                ->orderBy('gtf.ordre, e.niveau, e.sourceCode, ep.libelle');
        
        if (isset($context['niveau'])) {
            $qb->andWhere('CONCAT(gtf.libelleCourt, e.niveau) = :niveau')->setParameter('niveau', $context['niveau']);
        }
        
        if (isset($context['etape'])) {
            if (!$context['etape'] instanceof \Application\Entity\Db\Etape) {
                throw new \Common\Exception\LogicException("L'étape spécifiée dans le contexte n'est pas du type attendu.");
            }
            $qb->andWhere('ep.etape = :etape')->setParameter('etape', $context['etape']);
        }
        
        return $qb;
    }
    
    /**
     * Recherche textuelle d'element pédagogique.
     * 
     * @param array $context
     * <p>Paramètres possibles :</p>
     * <i>term</i>         : Texte recherché <b>obligatoire</b><br />
     * <i>limit</i>        : Nombre de résultats maxi<br />
     * <i>structure</i>    : Structure concernée sous forme d'une entité<br />
     * <i>niveau</i>       : Niveau, i.e. CONCAT(gtf.libelle_court, e.niveau), ex: L1, M2<br />
     * <i>etape</i>        : Etape concernée sous forme d'une entité<br />
     * @return array
     */
    public function finderByTermOld(array $context = array())
    {
        if (!isset($context['term'])) {
            return array();
        }
        if (!isset($context["limit"])) {
            $context["limit"] = 100;
        }
        
        $term      = preg_replace('#\s{2,}#', ' ', trim($context['term']));
        $criterion = explode(' ', $term);

        $parts = $params = array();
        for ($i = 0; $i < count($criterion); $i++) {
            $parts[] = "(UPPER(CONVERT(v.etape_info, 'US7ASCII')) LIKE UPPER(CONVERT(:criterionStr$i, 'US7ASCII'))) ";
            $params["criterionStr$i"] = '%' . $criterion[$i] . '%';
        }
        $whereTerm = implode(' AND ', $parts);
        
        $whereContext = array();
        if (isset($context['structure']) && $context['structure'] instanceof \Application\Entity\Db\Structure) {
            $whereContext[] = 's.structure_niv2_id = :structure';
            $params['structure'] = $context['structure']->getId();
        }
        if (isset($context['niveau']) && $context['niveau']) {
            $whereContext[] = 'CONCAT(gtf.libelle_court, e.niveau) = :niveau';
            $params['niveau'] = $context['niveau'];
        }
        if (isset($context['etape']) && $context['etape'] instanceof \Application\Entity\Db\Etape) {
            $whereContext[] = 'ep.etape_id = :etape';
            $params['etape'] = $context['etape']->getId();
        }
        $whereContext = implode(PHP_EOL . 'AND ', array_filter($whereContext));
        $whereContext = $whereContext ? 'AND ' . $whereContext : null;

        $sql = <<<EOS
SELECT ep.id, ep.source_code, ep.libelle, e.libelle libelle_etape, e.niveau, pe.libelle libelle_pe, 
       gtf.libelle_court libelle_gtf, tf.libelle_long libelle_tf, v.etape_info
FROM element_pedagogique ep
JOIN periode_enseignement pe ON ep.periode_id = pe.id
JOIN etape e ON ep.etape_id = e.id
JOIN type_formation tf ON e.type_formation_id = tf.id
JOIN groupe_type_formation gtf ON tf.groupe_id = gtf.id
JOIN structure s ON ep.structure_id = s.id
JOIN V_CONCAT_ELEMENT_INFOS v ON ep.id = v.id
WHERE $whereTerm
$whereContext
AND ROWNUM <= :limit
ORDER BY gtf.ordre, e.niveau, ep.libelle
EOS;
        
        $sql = <<<EOS
select * from (
  select ep.id,
    rank() over (partition by ep.id order by cp.ordre) rang,
    ep.source_code, ep.libelle, e.libelle libelle_etape, e.niveau, pe.libelle libelle_pe, gtf.libelle_court libelle_gtf, tf.libelle_long libelle_tf, 
    ep.source_code || ' ' || ep.libelle|| ' ' || e.source_code || ' ' || e.libelle || ' ' || gtf.LIBELLE_COURT || ' ' || e.NIVEAU || ' ' || tf.LIBELLE_COURT etape_info
  from chemin_pedagogique cp
  JOIN element_pedagogique ep ON cp.element_pedagogique_id = ep.id
  JOIN periode_enseignement pe ON ep.periode_id = pe.id
  JOIN etape e ON cp.etape_id = e.id
  JOIN TYPE_FORMATION tf on e.TYPE_FORMATION_ID = tf.ID and tf.HISTO_DESTRUCTEUR_ID is null and sysdate between tf.VALIDITE_DEBUT and nvl(tf.VALIDITE_FIN, sysdate)
  JOIN GROUPE_TYPE_FORMATION gtf on tf.GROUPE_ID = gtf.ID and gtf.HISTO_DESTRUCTEUR_ID is null
  JOIN structure s ON ep.structure_id = s.id
  where e.HISTO_DESTRUCTEUR_ID is null and sysdate between e.VALIDITE_DEBUT and nvl(e.VALIDITE_FIN, sysdate)
  and lower(ep.source_code || ' ' || ep.libelle|| ' ' || e.source_code || ' ' || e.libelle || ' ' || gtf.LIBELLE_COURT || ' ' || e.NIVEAU || ' ' || tf.LIBELLE_COURT) like '%carri_res publiques%'
  order by gtf.ordre, e.niveau, ep.libelle
)
where rang = 1
;
EOS;
        
        $params["limit"] = $context["limit"];
        
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
//        var_dump($sql, $params);die;

        return $result->fetchAll();
    }
    public function finderByTerm(array $context = array())
    {
        if (!isset($context['term'])) {
            return array();
        }
        if (!isset($context["limit"])) {
            $context["limit"] = 100;
        }
        
        $term      = preg_replace('#\s{2,}#', ' ', trim($context['term']));
        $criterion = explode(' ', $term);

        $concat = "ep.source_code || ' ' || ep.libelle|| ' ' || e.source_code || ' ' || e.libelle || ' ' || gtf.LIBELLE_COURT || ' ' || e.NIVEAU || ' ' || tf.LIBELLE_COURT";
        $parts  = $params = array();
        for ($i = 0; $i < count($criterion); $i++) {
            $parts[] = "(UPPER(CONVERT($concat, 'US7ASCII')) LIKE UPPER(CONVERT(:criterionStr$i, 'US7ASCII'))) ";
            $params["criterionStr$i"] = '%' . $criterion[$i] . '%';
        }
        $whereTerm = implode(' AND ', $parts);
        
        $whereContext = array();
        if (isset($context['structure']) && $context['structure'] instanceof \Application\Entity\Db\Structure) {
            $whereContext[] = 's.structure_niv2_id = :structure';
            $params['structure'] = $context['structure']->getId();
        }
        if (isset($context['niveau']) && $context['niveau']) {
            $whereContext[] = 'CONCAT(gtf.libelle_court, e.niveau) = :niveau';
            $params['niveau'] = $context['niveau'];
        }
        if (isset($context['etape']) && $context['etape'] instanceof \Application\Entity\Db\Etape) {
            $whereContext[] = 'ep.etape_id = :etape';
            $params['etape'] = $context['etape']->getId();
        }
        $whereContext = implode(PHP_EOL . 'AND ', array_filter($whereContext));
        $whereContext = $whereContext ? 'AND ' . $whereContext : null;
        
        $sql = <<<EOS
select * from (
  select ep.id,
    rank() over (partition by ep.id order by cp.ordre) rang,
    ep.source_code, ep.libelle, e.libelle libelle_etape, e.niveau, pe.libelle libelle_pe, gtf.libelle_court libelle_gtf, tf.libelle_long libelle_tf, 
    ep.source_code || ' ' || ep.libelle|| ' ' || e.source_code || ' ' || e.libelle || ' ' || gtf.LIBELLE_COURT || ' ' || e.NIVEAU || ' ' || tf.LIBELLE_COURT etape_info
  from chemin_pedagogique cp
  JOIN element_pedagogique ep ON cp.element_pedagogique_id = ep.id
  JOIN periode_enseignement pe ON ep.periode_id = pe.id
  JOIN etape e ON cp.etape_id = e.id
  JOIN TYPE_FORMATION tf on e.TYPE_FORMATION_ID = tf.ID and tf.HISTO_DESTRUCTEUR_ID is null and sysdate between tf.VALIDITE_DEBUT and nvl(tf.VALIDITE_FIN, sysdate)
  JOIN GROUPE_TYPE_FORMATION gtf on tf.GROUPE_ID = gtf.ID and gtf.HISTO_DESTRUCTEUR_ID is null
  JOIN structure s ON ep.structure_id = s.id
  where e.HISTO_DESTRUCTEUR_ID is null and sysdate between e.VALIDITE_DEBUT and nvl(e.VALIDITE_FIN, sysdate)
  and $whereTerm
  $whereContext
  order by gtf.ordre, e.niveau, ep.libelle
)
where rang = 1
EOS;
        
        $params["limit"] = $context["limit"];
        
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
//        var_dump($sql, $params);die;

        return $result->fetchAll();
    }

    /**
     * 
     * @param array $result
     * @param integer $length
     * @return array
     */
    protected function truncateResult($result, $length = 15)
    {
        if ($length && ($remain = count($result) - $length) > 0) {
            $result   = array_slice($result, 0, $length);
            $result[] = array('id'    => null, 'label' => "<em><small>$remain résultats restant, affinez vos critères, svp.</small></em>");
        }
        return $result;
    }
    
    /**
     * Retourne le chercheur des structures distinctes.
     * 
     * @param array $context
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderDistinctStructures(array $context = array(), \Doctrine\ORM\QueryBuilder $qb = null)
    {
        if (null === $qb) {
            $qb = new \Doctrine\ORM\QueryBuilder($this->getEntityManager());
        }
        
        $qb
                ->select('s')
                ->distinct()
                ->from('Application\Entity\Db\Structure', 's')
                ->innerJoin('s.elementPedagogique', 'ep')
                ->orderBy('s.libelleCourt');
        
        if (isset($context['niveau']) && is_numeric($context['niveau'])) {
            $qb->where('s.niveau = :niv')->setParameter('niv', $context['niveau']);
        }
        
        return $qb;
    }
    
    /**
     * Retourne le chercheur des niveaux distincts.
     * 
     * @param array $context
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderDistinctNiveaux(array $context = array(), \Doctrine\ORM\QueryBuilder $qb = null)
    {
//        if (!isset($context['structure'])) {
//            throw new \Common\Exception\LogicException("Aucune structure spécifiée dans le contexte.");
//        }
//        if (!$context['structure'] instanceof \Application\Entity\Db\Structure) {
//            throw new \Common\Exception\LogicException("La structure spécifiée dans le contexte n'est pas du type attendu.");
//        }
        
        $qb = new \Doctrine\ORM\QueryBuilder($this->getEntityManager());
        $qb
                ->select('e.niveau, gtf.libelleCourt, gtf.ordre')
                ->distinct()
                ->from('Application\Entity\Db\Etape', 'e')
                ->innerJoin('e.elementPedagogique', 'ep')
                ->innerJoin('e.typeFormation', 'tf')
                ->innerJoin('tf.groupe', 'gtf')
                ->orderBy('gtf.ordre, e.niveau');
        
        if (isset($context['structure']) && $context['structure'] instanceof \Application\Entity\Db\Structure) {
            $qb->andWhere('ep.structure = :struct')->setParameter('struct', $context['structure']);
        }
        
        return $qb;
    }
    
    /**
     * Retourne le chercheur d'étapes distinctes.
     * 
     * @param array $context
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderDistinctEtapes(array $context = array(), \Doctrine\ORM\QueryBuilder $qb = null)
    {
//        if (!isset($context['structure'])) {
//            throw new \Common\Exception\LogicException("Aucune structure spécifiée dans le contexte.");
//        }
//        if (!$context['structure'] instanceof \Application\Entity\Db\Structure) {
//            throw new \Common\Exception\LogicException("La structure spécifiée dans le contexte n'est pas du type attendu.");
//        }
        
        $qb = new \Doctrine\ORM\QueryBuilder($this->getEntityManager());
        $qb
                ->select('e, tf, gtf')
                ->distinct()
                ->from('Application\Entity\Db\Etape', 'e')
                ->innerJoin('e.elementPedagogique', 'ep')
                ->innerJoin('e.typeFormation', 'tf')
                ->innerJoin('tf.groupe', 'gtf')
                ->orderBy('gtf.ordre, e.niveau, e.sourceCode');
        
        if (isset($context['structure']) && $context['structure'] instanceof \Application\Entity\Db\Structure) {
            $qb->andWhere('ep.structure = :struct')->setParameter('struct', $context['structure']);
        }
        if (isset($context['niveau']) && $context['niveau']) {
            $qb->andWhere('CONCAT(gtf.libelleCourt, e.niveau) = :niveau')->setParameter('niveau', $context['niveau']);
        }
        
        return $qb;
    }
}