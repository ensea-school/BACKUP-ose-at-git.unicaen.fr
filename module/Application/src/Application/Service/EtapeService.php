<?php

namespace Application\Service;

use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\GroupeTypeFormationServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\TypeFormationServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Etape;

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

        $typeFormationAlias   = $this->getServiceTypeFormation()->getAlias();
        $groupeTypeFormationAlias   = $this->getServiceGroupeTypeFormation()->getAlias();

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
     * @param \Doctrine\ORM\QueryBuilder       $qb
     * @param string                           $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $this->finderByAnnee( $this->getServiceContext()->getAnnee(), $qb, $alias );
        if ($cStructure = $this->getServiceContext()->getStructure()){
            $this->finderByStructure( $cStructure, $qb, $alias );
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
     * @param Etape $entity
     *
     * @return Etape
     */
    public function save($entity)
    {
        if (!$entity->getAnnee()){
            $entity->setAnnee($this->getServiceContext()->getAnnee());
        }

        if (!$this->getAuthorize()->isAllowed($entity, Privileges::ODF_ETAPE_EDITION)) {
            throw new \UnAuthorizedException('Vous n\'êtes pas autorisé(e) à enregistrer cette formation.');
        }

        return parent::save($entity);
    }



    public function saveModulateurs(Etape $etape)
    {
        if (!$this->getAuthorize()->isAllowed($etape, Privileges::ODF_MODULATEURS_EDITION)) {
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
     * @param Etape $entity
     * @param boolean     $softDelete Simple historisation ou bien destruction pure et simple
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        if (!$this->getAuthorize()->isAllowed($entity, Privileges::ODF_ETAPE_EDITION)) {
            throw new \UnAuthorizedException('Vous n\'êtes pas autorisé(e) à supprimer cette formation.');
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