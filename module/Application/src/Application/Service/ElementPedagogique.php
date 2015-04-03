<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\ElementPedagogique as ElementPedagogiqueEntity;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogique extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\ElementPedagogique';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ep';
    }

    /**
     * Retourne le chercheur des structures distinctes.
     *
     * @param array $filters
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finder(array $filters = [], QueryBuilder $qb=null, $alias=null)
    {
        if (null === $qb) {
            $qb = $this->getEntityManager()->createQueryBuilder();
        }

        $qb
                ->select('ep, e, tf, gtf, p')
                ->from('Application\Entity\Db\ElementPedagogique', 'ep')
                ->leftJoin('ep.periode', 'p')
                ->innerJoin('ep.etape', 'e')
                ->innerJoin('e.typeFormation', 'tf')
                ->innerJoin('tf.groupe', 'gtf')
                ->innerJoin('ep.structure', 's')
                ->orderBy('gtf.ordre, e.niveau, e.sourceCode, ep.libelle');

        if (isset($filters['structure'])) {
            $qb->andWhere('s.structureNiv2 = :structure')->setParameter('structure', $filters['structure']);
        }

        if (isset($filters['niveau']) && $filters['niveau']) {
            if ($filters['niveau'] instanceof \Application\Entity\NiveauEtape) {
                $filters['niveau'] = $filters['niveau']->getId();
            }
            $qb->andWhere('CONCAT(gtf.libelleCourt, CONCAT( \'-\', e.niveau )) = :niveau')->setParameter('niveau', $filters['niveau']);
        }

        if (isset($filters['etape'])) {
            if (!$filters['etape'] instanceof \Application\Entity\Db\Etape) {
                throw new \Common\Exception\LogicException("La formation spécifiée dans le contexte n'est pas du type attendu.");
            }
            $qb->andWhere('ep.etape = :etape')->setParameter('etape', $filters['etape']);
        }

        return $qb;
    }

    /**
     * Retourne le chercheur des niveaux distincts.
     *
     * @param array $filters
     * @param \Doctrine\ORM\QueryBuilder $qbWithEp
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderDistinctNiveaux(array $filters = [], QueryBuilder $qb=null, $alias=null)
    {
        $qb = new \Doctrine\ORM\QueryBuilder($this->getEntityManager());
        $qb
                ->select('e, tf, gtf')
                ->distinct()
                ->from('Application\Entity\Db\Etape', 'e')
                ->innerJoin('e.typeFormation', 'tf')
                ->innerJoin('tf.groupe', 'gtf')
                ->innerJoin('e.elementPedagogique', 'ep')
//                ->innerJoin('ep.structure', 's')
                ->orderBy('gtf.ordre, e.niveau');

        if (isset($filters['structure']) && $filters['structure'] instanceof \Application\Entity\Db\Structure) {
            $qb
                    ->andWhere('ep.structure = :struct')
                    ->setParameter('struct', $filters['structure']);
        }

//        var_dump($qb->getQuery()->getSQL());
        return $qb;
    }

    /**
     * Retourne le chercheur d'étapes distinctes.
     *
     * @param array $filters
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderDistinctEtapes(array $filters = [], QueryBuilder $qb=null, $alias=null)
    {
        $qb = new \Doctrine\ORM\QueryBuilder($this->getEntityManager());
        $qb
                ->select('e, tf, gtf')
                ->distinct()
                ->from('Application\Entity\Db\Etape', 'e')
                ->innerJoin('e.elementPedagogique', 'ep')
                ->leftJoin('ep.periode', 'p')
                ->innerJoin('e.typeFormation', 'tf')
                ->innerJoin('tf.groupe', 'gtf')
                ->innerJoin('ep.structure', 's')
                ->orderBy('gtf.ordre, e.niveau, e.sourceCode');

//        if (isset($filters['structure']) && $filters['structure'] instanceof \Application\Entity\Db\Structure) {
//            $qb->andWhere('ep.structure = :struct')->setParameter('struct', $filters['structure']);
//        }
        if (isset($filters['structure']) && $filters['structure'] instanceof \Application\Entity\Db\Structure) {
            $qb->andWhere('s.structureNiv2 = :structure')->setParameter('structure', $filters['structure']);
        }
        if (isset($filters['niveau']) && $filters['niveau']) {
            if ($filters['niveau'] instanceof \Application\Entity\NiveauEtape) {
                $filters['niveau'] = $filters['niveau']->getId();
            }
            $qb->andWhere('CONCAT(gtf.libelleCourt, CONCAT( \'-\', e.niveau )) = :niveau')->setParameter('niveau', $filters['niveau']);
        }
        return $qb;
    }

    /**
     * Recherche textuelle d'element pédagogique.
     *
     * @param array $filters
     * <p>Paramètres possibles :</p>
     * <i>term</i>         : Texte recherché<br />
     * <i>limit</i>        : Nombre de résultats maxi<br />
     * <i>structure</i>    : Structure concernée sous forme d'une entité<br />
     * <i>niveau</i>       : Niveau, i.e. CONCAT(gtf.libelle_court, e.niveau), ex: L1, M2<br />
     * <i>etape</i>        : Etape concernée sous forme d'une entité<br />
     * <i>element</i>      : Élément concerné sous forme d'une entité<br />
     * @return array
     */
    public function getSearchResultByTerm(array $filters = [])
    {
        if (!isset($filters["limit"])) {
            $filters["limit"] = 100;
        }

        if ($filters['term']){
            $term      = preg_replace('#\s{2,}#', ' ', trim($filters['term']));
            $criterion = explode(' ', $term);

            $concat = "ep.source_code || ' ' || ep.libelle|| ' ' || e.source_code || ' ' || e.libelle || ' ' || gtf.LIBELLE_COURT || ' ' || e.NIVEAU || ' ' || tf.LIBELLE_COURT";
            $parts  = $params = [];
            for ($i = 0; $i < count($criterion); $i++) {
                $parts[] = "(UPPER(CONVERT($concat, 'US7ASCII')) LIKE UPPER(CONVERT(:criterionStr$i, 'US7ASCII'))) ";
                $params["criterionStr$i"] = '%' . $criterion[$i] . '%';
            }
            $whereTerm = implode(' AND ', $parts);
        }else{
            $whereTerm = '1=1';
        }

        $whereContext = [];
        if (isset($filters['structure']) && $filters['structure'] instanceof \Application\Entity\Db\Structure) {
            $whereContext[] = 's.structure_niv2_id = :structure';
            $params['structure'] = $filters['structure']->getId();
        }
        if (isset($filters['niveau']) && $filters['niveau']) {
            if ($filters['niveau'] instanceof \Application\Entity\NiveauEtape) {
                $filters['niveau'] = $filters['niveau']->getId();
            }
            $niveau = str_replace('-', '', $filters['niveau']);
            $whereContext[] = 'CONCAT(gtf.libelle_court, e.niveau) = :niveau';
            $params['niveau'] = $niveau;
        }
        if (isset($filters['etape']) && $filters['etape'] instanceof \Application\Entity\Db\Etape) {
            $whereContext[] = 'cp.etape_id = :etape';
            $params['etape'] = $filters['etape']->getId();
        }
        $whereContext = implode(PHP_EOL . 'AND ', array_filter($whereContext));
        $whereContext = $whereContext ? 'AND ' . $whereContext : null;

        if (isset($filters['element']) && $filters['element'] instanceof ElementPedagogiqueEntity){
            $orEp = " OR ep.id = ".((int)$filters['element']->getId());
            $orCp = " OR cp.element_pedagogique_id = ".((int)$filters['element']->getId());
        }else{
            $orEp = '';
            $orCp = '';
        }

        $sql = "
select * from (
  select ep.id,
    rank() over (partition by ep.id order by cp.ordre) rang,
    count(*) over (partition by ep.id)                 nb_ch,
    ep.source_code, ep.libelle,
    e.libelle libelle_etape, e.niveau,
    pe.libelle_long libelle_pe,
    gtf.libelle_court libelle_gtf,
    tf.libelle_long libelle_tf,
    ep.source_code || ' ' || ep.libelle|| ' ' || e.source_code || ' ' || e.libelle || ' ' || gtf.LIBELLE_COURT || ' ' || e.NIVEAU || ' ' || tf.LIBELLE_COURT etape_info
  from
    chemin_pedagogique cp
    JOIN element_pedagogique ep ON cp.element_pedagogique_id = ep.id  and 1 = ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction)".$orEp."
    JOIN etape e ON cp.etape_id = e.id
    JOIN TYPE_FORMATION tf on e.TYPE_FORMATION_ID = tf.ID
    JOIN GROUPE_TYPE_FORMATION gtf on tf.GROUPE_ID = gtf.ID
    JOIN structure s ON ep.structure_id = s.id
    LEFT JOIN periode pe ON ep.periode_id = pe.id
  where
    (1 = ose_divers.comprise_entre( cp.histo_creation, cp.histo_destruction )$orCp)
    and $whereTerm
    $whereContext
  order by
    gtf.ordre, e.niveau, ep.libelle
)
where rang = 1
";

        $params["limit"] = $filters["limit"];

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
            $result[] = ['id'    => null, 'label' => "<em><small>$remain résultats restant, affinez vos critères, svp.</small></em>"];
        }
        return $result;
    }

    /**
     * Détermine si on peut ajouter une étape ou non
     *
     * @return boolean
     */
    public function canAdd($runEx = false)
    {
        $localContext = $this->getServiceLocator()->get('applicationLocalContext');
        /* @var $localContext \Application\Service\LocalContext */

        $role         = $this->getServiceLocator()->get('ApplicationContextProvider')->getSelectedIdentityRole();

        if ($role instanceof \Application\Acl\AdministrateurRole) return true;

        if (!$localContext->getStructure()) {
            throw new \Common\Exception\LogicException("Le filtre structure est requis dans la méthode " . __METHOD__);
        }
        if ($localContext->getStructure()->getId() === $role->getStructure()->getId()
                || $localContext->getStructure()->estFilleDeLaStructureDeNiv2($role->getStructure())) {
            return true;
        }

        $this->cannotDoThat(
                "Votre structure de responsabilité ('{$role->getStructure()}') ne vous permet pas d'ajouter/modifier un enseignement"
                . "pour la structure '{$localContext->getStructure()}'", $runEx);

        $this->cannotDoThat(
                "Votre structure de responsabilité ('{$role->getStructure()}') ne vous permet pas d'ajouter/modifier un enseignement"
                . "pour la structure '{$localContext->getStructure()}'", $runEx);

        return $this->cannotDoThat('Vous n\'avez pas les droits nécessaires pour ajouter/modifier un enseignement', $runEx);
    }

    /**
     * Détermine si l'élément peut être modifié ou non
     *
     * @param int|\Application\Entity\Db\ElementPedagogique $element
     * @return boolean
     */
    public function canSave($element, $runEx = false)
    {
        if (! $this->canAdd($runEx)) {
            return false;
        }

        if (!$element instanceof ElementPedagogiqueEntity) {
            $element = $this->get($element);
        }

        if ($element->getSource()->getCode() !== \Application\Entity\Db\Source::CODE_SOURCE_OSE){
            $errStr = 'Cet enseignement n\'est pas modifiable dans OSE car elle provient du logiciel '.$element->getSource();
            $errStr .= '. Si vous souhaitez mettre à jour ces informations, nous vous invitons donc à les modifier directement dans '.$element->getSource().'.';

            return $this->cannotDoThat($errStr, $runEx);
        }

        return true;
    }

    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     * @return EtapeEntity
     */
    public function newEntity()
    {
        $this->canAdd(true);
        $entity = parent::newEntity();
        // toutes les entités créées ont OSE pour source!!
        $entity->setSource( $this->getServiceLocator()->get('ApplicationSource')->getOse() );
        return $entity;
    }

    /**
     * Sauvegarde une entité
     *
     * @param \Application\Entity\Db\ElementPedagogique $entity
     * @return ElementPedagogiqueEntity
     * @throws \Common\Exception\RuntimeException
     */
    public function save($entity)
    {
        // si absence de chemin pédagogique, création du chemin
        if (!$entity->getCheminPedagogique()->count()) {
            $cp = $this->getServiceCheminPedagogique()->newEntity(); /* @var $cp \Application\Entity\Db\CheminPedagogique */
            $cp
                    ->setEtape($entity->getEtape())
                    ->setElementPedagogique($entity);

            $entity->addCheminPedagogique($cp);

            $this->getEntityManager()->persist($cp);
        }

        $result = parent::save($entity);
        /* Sauvegarde automatique des éléments-modulateurs associés */
        $serviceElementModulateur = $this->getServiceLocator()->get('applicationElementModulateur');
        /* @var $serviceElementModulateur ElementModulateur */
        foreach( $entity->getElementModulateur() as $elementModulateur ){
            if ($elementModulateur->getRemove()){
                $serviceElementModulateur->delete($elementModulateur);
            }else{
                $serviceElementModulateur->save( $elementModulateur );
            }
        }
        return $result;
    }

    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param \Application\Entity\Db\ElementPedagogique $entity Entité à détruire
     * @param bool $softDelete
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        foreach ($entity->getCheminPedagogique() as $cp) { /* @var $cp \Application\Entity\Db\CheminPedagogique */
            $cp->getEtape()->removeCheminPedagogique($cp);
            $entity->removeCheminPedagogique($cp);
            $this->getServiceCheminPedagogique()->delete($cp);
        }

        return parent::delete($entity, $softDelete);
    }

    public function getList(QueryBuilder $qb=null, $alias=null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy($this->getAlias().'.libelle');
        return parent::getList($qb, $alias);
    }

    /**
     *
     * @return CheminPedagogique
     */
    protected function getServiceCheminPedagogique()
    {
        return $this->getServiceLocator()->get('ApplicationCheminPedagogique');
    }

}
