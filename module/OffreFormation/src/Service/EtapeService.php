<?php

namespace OffreFormation\Service;

use Application\Entity\Db\Annee;
use Application\Provider\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\Traits;
use Application\Service\Traits\ContextServiceAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Service\Traits\CheminPedagogiqueServiceAwareTrait;
use OffreFormation\Service\Traits\ElementModulateurServiceAwareTrait;
use OffreFormation\Service\Traits\GroupeTypeFormationServiceAwareTrait;
use OffreFormation\Service\Traits\TypeFormationServiceAwareTrait;
use RuntimeException;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Etape get($id)
 * @method Etape[] getList(?QueryBuilder $qb = null, $alias = null)
 */
class EtapeService extends AbstractEntityService
{
    use Traits\LocalContextServiceAwareTrait;
    use Traits\SourceServiceAwareTrait;
    use ElementModulateurServiceAwareTrait;
    use TypeFormationServiceAwareTrait;
    use GroupeTypeFormationServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ContextServiceAwareTrait;
    use CheminPedagogiqueServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return Etape::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'etp';
    }



    /**
     * Retour uniquement les Etapes ayant été reconduites pour l'année universitaire suivante
     * et possédant des centres de coût à reconduire
     *
     * @return string
     */

    public function getEtapeCentreCoutReconductible(Structure $structure)
    {
        $annee = $this->getServiceContext()->getAnnee()->getId();


        $sql = '
        SELECT 
          count(*) as nb_centre_cout,
          etape_id,
          etape_code,
          etape_libelle
        FROM 
          V_RECONDUCTION_CENTRE_COUT 
        WHERE
          annee_id = :annee
          AND structure_ids LIKE :structure
        GROUP BY
          etape_id,
          etape_code,
          etape_libelle
        ORDER BY 
          etape_id ASC
        ';

        $params = [
            'annee' => $annee,
            'structure' => $structure->idsFilter(),
        ];

        $result = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);
        $etapes = [];
        foreach ($result as $etape) {
            $etapes[$etape['ETAPE_CODE']] = $etape;
        }

        return $etapes;
    }



    /**
     * Retour uniquement les Etapes ayant été reconduites pour l'année universitaire suivante
     * et possédant des modulateurs à reconduire
     *
     * @return string
     */

    public function getEtapeModulateurReconductible($structure)
    {
        $annee = $this->getServiceContext()->getAnnee()->getId();


        $sql = '
        SELECT 
          count(*) as nb_modulateur,
          etape_id,
          etape_code,
          etape_libelle
        FROM 
          V_RECONDUCTION_MODULATEUR 
        WHERE
          annee_id = :annee
          AND structure_ids LIKE :structure
        GROUP BY
          etape_id,
          etape_code,
          etape_libelle
        ORDER BY 
          etape_id ASC
        ';

        $params = [
            'annee' => $annee,
            'structure' => $structure->idsFilter(),
        ];

        $result = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);
        $etapes = [];
        foreach ($result as $etape) {
            $etapes[$etape['ETAPE_CODE']] = $etape;
        }

        return $etapes;
    }



    /**
     *
     * @param \OffreFormation\Entity\NiveauEtape $niveau
     * @param \Doctrine\ORM\QueryBuilder      $qb
     * @param string                          $alias
     *
     * @return QueryBuilder
     */
    public function finderByNiveau(\OffreFormation\Entity\NiveauEtape $niveau, ?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

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
     * @param \Lieu\Entity\Db\Structure $structure
     * @param \Doctrine\ORM\QueryBuilder       $qb
     * @param string                           $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByStructure(?Structure $structure, ?QueryBuilder $qb = null, $alias = null): QueryBuilder
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

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
    public function finderByContext(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $this->finderByAnnee($this->getServiceContext()->getAnnee(), $qb, $alias);
        if ($cStructure = $this->getServiceContext()->getStructure()) {
            $this->finderByStructure($cStructure, $qb, $alias);
        }
        //On filtre les étapes hsitorisées
        $this->finderByHistorique($qb);

        return $qb;
    }



    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.libelle");

        return $qb;
    }



    /**
     *
     * @param string $code
     * @param Annee  $annee
     *
     * @return Etape
     */
    public function getByCode($code, ?Annee $annee = null)
    {
        if (null == $code) return null;

        if (!$annee) {
            $annee = $this->getServiceContext()->getAnnee();
        }

        return $this->getRepo()->findOneBy(['code' => $code, 'annee' => $annee->getId()]);
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

        foreach ($entity->getCheminPedagogique() as $cp) {
            if ($cp->estNonHistorise()) {
                /* @var $cp \OffreFormation\Entity\Db\CheminPedagogique */
                $cp->getElementPedagogique()->removeCheminPedagogique($cp);
                $entity->removeCheminPedagogique($cp);
                $this->getServiceCheminPedagogique()->delete($cp);
            }
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