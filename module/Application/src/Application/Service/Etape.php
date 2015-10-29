<?php

namespace Application\Service;

use Application\Entity\Db\Privilege;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Etape as EtapeEntity;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Etape extends AbstractEntityService
{
    use Traits\LocalContextAwareTrait;
    use Traits\SourceAwareTrait;
    use Traits\ElementModulateurAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Etape';
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'etp';
    }



    /**
     *
     * @param \Application\Entity\NiveauEtape $niveau
     * @param \Doctrine\ORM\QueryBuilder      $qb
     * @param string                          $alias
     *
     * @return QueryBuilder
     */
    public function finderByNiveau(\Application\Entity\NiveauEtape $niveau, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $typeFormationService = $this->getServiceLocator()->get('applicationTypeFormation');
        $typeFormationAlias   = $typeFormationService->getAlias();

        $groupeTypeFormationService = $this->getServiceLocator()->get('applicationGroupeTypeFormation');
        $groupeTypeFormationAlias   = $groupeTypeFormationService->getAlias();

        $qb
            ->innerJoin("$alias.typeFormation", $typeFormationAlias)
            ->innerJoin("$typeFormationAlias.groupe", $groupeTypeFormationAlias)
            ->andWhere("$alias.niveau = :niv AND $groupeTypeFormationAlias.libelleCourt = :lib")
            ->setParameter('niv', $niveau->getNiv())
            ->setParameter('lib', $niveau->getLib());

        return parent::getList($qb, $alias);
    }



    /**
     * Retourne le chercheur d'étapes orphelines (i.e. sans EP).
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByOrphelines(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
            ->join("$alias.source", "src")
            ->andWhere("NOT EXISTS (SELECT eptmp FROM Application\Entity\Db\ElementPedagogique eptmp WHERE eptmp.etape = $alias)")
            ->andWhere("NOT EXISTS (SELECT cptmp FROM Application\Entity\Db\CheminPedagogique  cptmp WHERE cptmp.etape = $alias)")
            ->andWhere("$alias.specifiqueEchanges = 0 OR src.code = :sourceOse")
            ->setParameter('sourceOse', \Application\Entity\Db\Source::CODE_SOURCE_OSE);

        return $qb;
    }



    /**
     * Retourne le chercheur d'étapes orphelines (i.e. sans EP).
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByNonOrphelines(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $or = $qb->expr()->orX();
        $or->add($qb->expr()->exists("SELECT eptmp FROM Application\Entity\Db\ElementPedagogique eptmp WHERE eptmp.etape = $alias AND eptmp.annee = :eptmpAnnee"));
        $or->add($qb->expr()->exists("SELECT cptmp FROM Application\Entity\Db\CheminPedagogique  cptmp JOIN cptmp.elementPedagogique eptmp2 WHERE cptmp.etape = $alias AND eptmp2.annee = :eptmpAnnee"));
        $qb->andWhere($or);
        $qb->setParameter('eptmpAnnee', $this->getServiceContext()->getAnnee());

        return $qb;
    }



    /**
     *
     * @param \Application\Entity\Db\Structure $structure
     * @param \Doctrine\ORM\QueryBuilder       $qb
     * @param string                           $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByStructure(\Application\Entity\Db\Structure $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $structureService = $this->getServiceLocator()->get('applicationStructure');
        $structureAlias   = $structureService->getAlias();

        $this->join($structureService, $qb, 'structure');

        $qb->andWhere("$structureAlias.structureNiv2 = :structureNiv2")->setParameter('structureNiv2', $structure->getParenteNiv2());

        return $qb;
    }



    /**
     * Filtre par historique, si l'entité est compatible avec les historiques
     *
     * @param QueryBuilder $qb
     * @param string       $alias
     *
     * @return QueryBuilder
     */
    public function finderByHistorique(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $dateObservation = $this->getServiceContext()->getDateObservation();
        if ($dateObservation) {
            $dqldobs = ', :fbh_dateObservation';
            $qb->setParameter('fbh_dateObservation', $dateObservation, \Doctrine\DBAL\Types\Type::DATETIME);
        } else {
            $dqldobs = '';
        }

        $qb->setParameter('fbh_annee', $this->getServiceContext()->getAnnee());

        $qb->andWhere("
            1 = compriseEntre($alias.histoCreation,$alias.histoDestruction$dqldobs)
            OR EXISTS(
              SELECT
                cp.id
              FROM
                Application\Entity\Db\CheminPedagogique cp
                JOIN Application\Entity\Db\ElementPedagogique ep WITH ep = cp.elementPedagogique
              WHERE
                1 = compriseEntre(cp.histoCreation,cp.histoDestruction$dqldobs)
                AND 1 = compriseEntre(ep.histoCreation,ep.histoDestruction$dqldobs)
                AND cp.etape = $alias
                AND ep.annee = :fbh_annee
          )
        ");

        return $qb;
    }



    /**
     * Retourne la liste des étapes
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null       $alias
     *
     * @return \Application\Entity\Db\Etape[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");

        return parent::getList($qb, $alias);
    }



    /**
     *
     * @param EtapeEntity $entity
     *
     * @return EtapeEntity
     */
    public function save($entity)
    {
        if (!$this->getAuthorize()->isAllowed($entity, Privilege::ODF_ETAPE_EDITION)) {
            throw new \UnAuthorizedException('Vous n\'êtes pas autorisé(e) à enregistrer cette formation.');
        }

        return parent::save($entity);
    }



    public function saveModulateurs(EtapeEntity $etape)
    {
        if (!$this->getAuthorize()->isAllowed($etape, Privilege::ODF_MODULATEURS_EDITION)) {
            throw new \UnAuthorizedException('Vous n\'êtes pas autorisé(e) à enregistrer cette formation.');
        }

        $serviceElementModulateur = $this->getServiceElementModulateur();
        $elements                 = $etape->getElementPedagogique()->toArray();
        foreach ($elements as $element) {
            if ($eemList = $element->getElementModulateur()) {
                foreach ($eemList as $elementModulateur) {
                    if ($elementModulateur->getRemove()) {
                        $serviceElementModulateur->delete($elementModulateur);
                    } else {
                        $serviceElementModulateur->save($elementModulateur);
                    }
                }
            }
        }
    }



    /**
     *
     * @param EtapeEntity $entity
     * @param boolean     $softDelete Simple historisation ou bien destruction pure et simple
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        if (!$this->getAuthorize()->isAllowed($entity, Privilege::ODF_ETAPE_EDITION)) {
            throw new \UnAuthorizedException('Vous n\'êtes pas autorisé(e) à supprimer cette formation.');
        }

        return parent::delete($entity, $softDelete);
    }



    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     *
     * @return EtapeEntity
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        // toutes les entités créées ont OSE pour source!!
        $entity->setSource($this->getServiceSource()->getOse());

        return $entity;
    }

}