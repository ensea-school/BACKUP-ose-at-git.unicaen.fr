<?php

namespace Application\Service;

use Application\Entity\Db\ElementTauxRegimes;
use Application\Entity\Db\Etape;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\CheminPedagogiqueServiceAwareTrait;
use Application\Service\Traits\ElementModulateurServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Annee;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueService extends AbstractEntityService
{
    use CheminPedagogiqueServiceAwareTrait;
    use ElementModulateurServiceAwareTrait;
    use SourceServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return ElementPedagogique::class;
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
     *
     * @return array
     */
    public function getSearchResultByTerm(array $filters = [], $order = "gtf.ordre, e.niveau, ep.libelle")
    {
        $annee = $this->getServiceContext()->getAnnee();

        if ($annee) {
            $af = ' ep.annee_id = ' . $annee->getId() . ' AND';
        } else {
            $af = '';
        }


        if (!isset($filters["limit"])) {
            $filters["limit"] = 100;
        }

        if ($filters['term']) {
            $term      = preg_replace('#\s{2,}#', ' ', trim($filters['term']));
            $criterion = explode(' ', $term);

            $concat = "ep.source_code || ' ' || ep.libelle|| ' ' || e.source_code || ' ' || e.libelle || ' ' || gtf.LIBELLE_COURT || ' ' || e.NIVEAU || ' ' || tf.LIBELLE_COURT";
            $parts  = $params = [];
            for ($i = 0; $i < count($criterion); $i++) {
                $parts[]                  = "(UPPER(CONVERT($concat, 'US7ASCII')) LIKE UPPER(CONVERT(:criterionStr$i, 'US7ASCII'))) ";
                $params["criterionStr$i"] = '%' . $criterion[$i] . '%';
            }
            $whereTerm = implode(' AND ', $parts);
        } else {
            $whereTerm = '1=1';
        }

        $whereContext = [];
        if (isset($filters['structure']) && $filters['structure'] instanceof \Application\Entity\Db\Structure) {
            $whereContext[]      = 's.id = :structure';
            $params['structure'] = $filters['structure']->getId();
        }
        if (isset($filters['niveau']) && $filters['niveau']) {
            if ($filters['niveau'] instanceof \Application\Entity\NiveauEtape) {
                $filters['niveau'] = $filters['niveau']->getId();
            }
            $niveau           = str_replace('-', '', $filters['niveau']);
            $whereContext[]   = 'CONCAT(gtf.libelle_court, e.niveau) = :niveau';
            $params['niveau'] = $niveau;
        }
        if (isset($filters['etape']) && $filters['etape'] instanceof \Application\Entity\Db\Etape) {
            $whereContext[]  = 'cp.etape_id = :etape';
            $params['etape'] = $filters['etape']->getId();
        }
        $whereContext = implode(PHP_EOL . 'AND ', array_filter($whereContext));
        $whereContext = $whereContext ? 'AND ' . $whereContext : null;

        if (isset($filters['element']) && $filters['element'] instanceof ElementPedagogique) {
            $orEp = " OR ep.id = " . ((int)$filters['element']->getId());
            $orCp = " OR cp.element_pedagogique_id = " . ((int)$filters['element']->getId());
        } else {
            $orEp = '';
            $orCp = '';
        }

        $sql = "
select * from (
  select ep.id,
    rank() over (partition by ep.id order by cp.ordre) rang,
    count(*) over (partition by ep.id)                 nb_ch,
    ep.code, ep.source_code, TRIM(ep.libelle) libelle,
    e.libelle libelle_etape, e.niveau,
    pe.libelle_long libelle_pe,
    gtf.libelle_court libelle_gtf,
    tf.libelle_long libelle_tf,
    ep.source_code || ' ' || ep.libelle|| ' ' || e.source_code || ' ' || e.libelle || ' ' || gtf.LIBELLE_COURT || ' ' || e.NIVEAU || ' ' || tf.LIBELLE_COURT etape_info
  from
    chemin_pedagogique cp
    JOIN element_pedagogique ep ON$af cp.element_pedagogique_id = ep.id  AND ep.histo_destruction IS NULL$orEp
    JOIN etape e ON cp.etape_id = e.id
    JOIN TYPE_FORMATION tf on e.TYPE_FORMATION_ID = tf.ID
    JOIN GROUPE_TYPE_FORMATION gtf on tf.GROUPE_ID = gtf.ID
    JOIN structure s ON s.id = e.structure_id OR s.id = ep.structure_id
    LEFT JOIN periode pe ON ep.periode_id = pe.id
  where
    (cp.histo_destruction IS NULL$orCp)
    and $whereTerm
    $whereContext
  order by
    $order
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
     * @param string $code
     * @param Annee  $annee
     *
     * @return ElementPedagogique
     */
    public function getByCode($code, Annee $annee = null)
    {
        if (null == $code) return null;

        if (!$annee) {
            $annee = $this->getServiceContext()->getAnnee();
        }

        return $this->getRepo()->findOneBy(['code' => $code, 'annee' => $annee->getId()]);
    }



    /**
     *
     * @param Etape $etape
     *
     * @return int $n nombre d'élément pédagogique avec un centre de coût.
     */
    public function countEpWithCc(Etape $etape)
    {
        $n                    = 0;
        $elementsPedagogiques = $etape->getElementPedagogique();
        if (!empty($elementsPedagogiques)) {
            foreach ($elementsPedagogiques as $ep) {
                $cc = $ep->getCentreCoutEp()->toArray();
                if (!empty($cc)) {
                    $n += 1;
                }
            }
        }

        return $n;
    }



    /**
     *
     * @param Etape $etape
     *
     * @return int $n nombre d'élément pédagogique avec un modulateur
     */
    public function countEpWithModulateur(Etape $etape)
    {
        $n                    = 0;
        $elementsPedagogiques = $etape->getElementPedagogique();
        if (!empty($elementsPedagogiques)) {
            foreach ($elementsPedagogiques as $ep) {
                $cc = $ep->getElementModulateur()->toArray();
                if (!empty($cc)) {
                    $n += 1;
                }
            }
        }

        return $n;
    }



    /**
     * Filtre la liste des éléments selon le contexte courant
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $this->finderByAnnee($this->getServiceContext()->getAnnee(), $qb);

        return $qb;
    }



    /**
     *
     * @param array   $result
     * @param integer $length
     *
     * @return array
     */
    protected function truncateResult($result, $length = 15)
    {
        if ($length && ($remain = count($result) - $length) > 0) {
            $result   = array_slice($result, 0, $length);
            $result[] = ['id' => null, 'label' => "<em><small>$remain résultats restant, affinez vos critères, svp.</small></em>"];
        }

        return $result;
    }



    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     *
     * @return \Application\Entity\Db\ElementPedagogique
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        // toutes les entités créées ont OSE pour source!!
        $entity->setSource($this->getServiceSource()->getOse());
        // on crée pour l'année courante
        $entity->setAnnee($this->getServiceContext()->getAnnee());

        return $entity;
    }



    /**
     * Sauvegarde une entité
     *
     * @param \Application\Entity\Db\ElementPedagogique $entity
     *
     * @return ElementPedagogique
     * @throws \RuntimeException
     */
    public function save($entity)
    {
        if (!$this->getAuthorize()->isAllowed($entity, Privileges::ODF_ELEMENT_EDITION)) {
            throw new UnAuthorizedException('Vous n\'êtes pas autorisé(e) à enregistrer cet enseignement.');
        }

        // si absence de chemin pédagogique, création du chemin
        if (!$entity->getCheminPedagogique()->count()) {
            $cp = $this->getServiceCheminPedagogique()->newEntity();
            /* @var $cp \Application\Entity\Db\CheminPedagogique */
            $cp
                ->setEtape($entity->getEtape())
                ->setElementPedagogique($entity);

            $entity->addCheminPedagogique($cp);

            $this->getEntityManager()->persist($cp);
        }

        return parent::save($entity);
    }



    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param \Application\Entity\Db\ElementPedagogique $entity Entité à détruire
     * @param bool                                      $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        if (!$this->getAuthorize()->isAllowed($entity, Privileges::ODF_ELEMENT_EDITION)) {
            throw new UnAuthorizedException('Vous n\'êtes pas autorisé(e) à supprimer cet enseignement.');
        }

        foreach ($entity->getCheminPedagogique() as $cp) {
            /* @var $cp \Application\Entity\Db\CheminPedagogique */
            $cp->getEtape()->removeCheminPedagogique($cp);
            $entity->removeCheminPedagogique($cp);
            $this->getServiceCheminPedagogique()->delete($cp);
        }

        return parent::delete($entity, $softDelete);
    }



    public function forcerTauxMixite(ElementPedagogique $elementPedagogique, $tauxFi, $tauxFc, $tauxFa)
    {
        /** @var ElementTauxRegimes $etr */
        $etr = $this->getEntityManager()->getRepository(ElementTauxRegimes::class)->findOneBy([
            'elementPedagogique' => $elementPedagogique,
            'histoDestruction'   => null,
        ]);

        $sourceOse = $this->getServiceSource()->getOse();
        $hasTaux   = ($tauxFi || $tauxFc || $tauxFa);

        if ($elementPedagogique->getSource() !== $sourceOse) {
            if ($hasTaux) {
                if ($etr) {
                    if ($etr->getSource() != $sourceOse) {
                        $etr->setSource($sourceOse);
                    }
                } else {
                    $etr = new ElementTauxRegimes();
                    $etr->setElementPedagogique($elementPedagogique);
                    $etr->setSource($sourceOse);
                    $etr->setSourceCode(uniqid('ose-etr-'));
                }

                $etr->setTauxFi($tauxFi);
                $etr->setTauxFc($tauxFc);
                $etr->setTauxFa($tauxFa);
            } else {
                if ($etr && $etr->getSource() == $sourceOse) {
                    $etr->setHistoDestruction(new \DateTime);
                    $etr->setHistoDestructeur($this->getServiceContext()->getUtilisateur());
                    $this->getEntityManager()->persist($etr);
                    $this->getEntityManager()->flush($etr);
                }
            }

            $this->getEntityManager()->persist($etr);
            $this->getEntityManager()->flush($etr);
        } else {
            if (!$hasTaux) {
                $tauxFi = $elementPedagogique->getFi();
                $tauxFc = $elementPedagogique->getFc();
                $tauxFa = $elementPedagogique->getFa();
            }
        }

        $usql = "
        UPDATE element_pedagogique SET
          taux_fi = ose_divers.calcul_taux_fi( :tauxFi, :tauxFc, :tauxFa, fi, fc, fa ),
          taux_fc = ose_divers.calcul_taux_fc( :tauxFi, :tauxFc, :tauxFa, fi, fc, fa ),
          taux_fa = ose_divers.calcul_taux_fa( :tauxFi, :tauxFc, :tauxFa, fi, fc, fa )
        WHERE
          id = " . $elementPedagogique->getId();

        $this->getEntityManager()->getConnection()->executeUpdate(
            $usql,
            compact('tauxFi', 'tauxFc', 'tauxFa')
        );

        $this->getEntityManager()->refresh($elementPedagogique);

        return $this;
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return QueryBuilder|null
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy($this->getAlias() . '.libelle');

        return $qb;
    }
}
