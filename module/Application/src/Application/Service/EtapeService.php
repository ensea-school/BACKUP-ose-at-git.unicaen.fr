<?php

namespace Application\Service;

use Application\Entity\Db\Structure;
use Application\Entity\Db\Annee;
use Application\Entity\Db\ElementPedagogique;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\GroupeTypeFormationServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\TypeFormationServiceAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Etape;
use Zend\Form\Element;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Etape get($id)
 * @method Etape[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 */
class EtapeService extends AbstractEntityService
{
    use Traits\LocalContextServiceAwareTrait;
    use Traits\SourceServiceAwareTrait;
    use Traits\ElementModulateurServiceAwareTrait;
    use TypeFormationServiceAwareTrait;
    use GroupeTypeFormationServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ContextServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Etape::class;
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
     * Retour uniquement les Etapes ayant été reconduites pour l'année universitaire suivante
     *
     * @return string
     */

    public function getEtapeReconduit($structure)
    {
        $annee  = $this->getServiceContext()->getAnnee()->getId();


        $sql = '
                SELECT 
            etape_id,
            etape_libelle,
            etape_code,
            count(DISTINCT element_pedagogique_id) AS nb_ep,
            count(DISTINCT new_centre_cout_ep_id) AS nb_cc_ep,
            count(DISTINCT new_element_modulateur_id) AS nb_m_ep
        FROM 
            V_RECONDUCTION_CC_MODULATEUR 
        WHERE
            annee_id = :annee
        GROUP BY 
            etape_id,
            etape_libelle,
            etape_code';
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindParam(':annee', $annee);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $etapes = [];
        foreach($result as $etape)
        {
            $etapes[$etape['ETAPE_CODE']] = $etape;
        }

        return $etapes;
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

        $typeFormationAlias       = $this->getServiceTypeFormation()->getAlias();
        $groupeTypeFormationAlias = $this->getServiceGroupeTypeFormation()->getAlias();

        $qb
            ->innerJoin("$alias.typeFormation", $typeFormationAlias)
            ->innerJoin("$typeFormationAlias.groupe", $groupeTypeFormationAlias)
            ->andWhere("$alias.niveau = :niv AND $groupeTypeFormationAlias.libelleCourt = :lib")
            ->setParameter('niv', $niveau->getNiv())
            ->setParameter('lib', $niveau->getLib());

        return parent::getList($qb, $alias);
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

        $structureService = $this->getServiceStructure();
        $structureAlias   = $structureService->getAlias();

        $this->join($structureService, $qb, 'structure');

        $qb->andWhere("$structureAlias = :structure")->setParameter('structure', $structure);

        return $qb;
    }



    /**
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string                     $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $this->finderByAnnee($this->getServiceContext()->getAnnee(), $qb, $alias);
        if ($cStructure = $this->getServiceContext()->getStructure()) {
            $this->finderByStructure($cStructure, $qb, $alias);
        }

        return $qb;
    }



    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.libelle");

        return $qb;
    }



    /**
     *
     * @param string $sourceCode
     * @param Annee  $annee
     *
     * @return Etape
     */
    public function getBySourceCode($sourceCode, Annee $annee = null)
    {
        if (null == $sourceCode) return null;

        if (!$annee) {
            $annee = $this->getServiceContext()->getAnnee();
        }

        return $this->getRepo()->findOneBy(['sourceCode' => $sourceCode, 'annee' => $annee->getId()]);
    }



    /**
     *
     * @param Etape $entity
     *
     * @return Etape
     */
    public function save($entity)
    {
        if (!$entity->getAnnee()) {
            $entity->setAnnee($this->getServiceContext()->getAnnee());
        }

        if (!$this->getAuthorize()->isAllowed($entity, Privileges::ODF_ETAPE_EDITION)) {
            throw new UnAuthorizedException('Vous n\'êtes pas autorisé(e) à enregistrer cette formation.');
        }

        return parent::save($entity);
    }



    public function saveModulateurs(Etape $etape)
    {
        if (!$this->getAuthorize()->isAllowed($etape, Privileges::ODF_MODULATEURS_EDITION)) {
            throw new UnAuthorizedException('Vous n\'êtes pas autorisé(e) à enregistrer cette formation.');
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
     * @param Etape   $entity
     * @param boolean $softDelete Simple historisation ou bien destruction pure et simple
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        if (!$this->getAuthorize()->isAllowed($entity, Privileges::ODF_ETAPE_EDITION)) {
            throw new UnAuthorizedException('Vous n\'êtes pas autorisé(e) à supprimer cette formation.');
        }

        return parent::delete($entity, $softDelete);
    }



    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     *
     * @return Etape
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        // toutes les entités créées ont OSE pour source!!
        $entity->setSource($this->getServiceSource()->getOse());

        return $entity;
    }

}