<?php

namespace Application\Service;

use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Entity\Db\EtatVolumeHoraire as EtatVolumeHoraireEntity;

/**
 * Description of VolumeHoraire
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraire extends AbstractEntityService
{
    use TypeVolumeHoraireAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\VolumeHoraire';
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'vh';
    }



    /**
     *
     * @return \Application\Entity\Db\VolumeHoraire
     */
    public function newEntity()
    {

        $entity = parent::newEntity();

        // type de volume horaire par défaut
        $entity->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());

        return $entity;
    }



    /**
     * Recherche par intervenant concerné.
     *
     * @param IntervenantEntity $intervenant
     * @param QueryBuilder|null $qb
     *
     * @return QueryBuilder
     */
    public function finderByIntervenant(IntervenantEntity $intervenant, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
            ->join("$alias.service", 'vhs2')
            ->join("vhs2.intervenant", 'i2')
            ->andWhere("i2 = :intervenant")
            ->setParameter('intervenant', $intervenant);

        return $qb;
    }



    /**
     * Recherche par structure d'intervention (i.e. structure où sont effectués les enseignements).
     *
     * @param StructureEntity   $structure
     * @param QueryBuilder|null $qb
     *
     * @return QueryBuilder
     */
    public function finderByStructureIntervention(StructureEntity $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $serviceService = $this->getServiceLocator()->get('applicationService');
        /* @var $serviceService Service */

        $serviceElement = $this->getServiceLocator()->get('applicationElementPedagogique');
        /* @var $element ElementPedagogique */

        $this->join($serviceService, $qb, 'service');
        $serviceService->leftJoin($serviceElement, $qb, 'elementPedagogique');
        $serviceElement->finderByStructure($structure, $qb);

        return $qb;
    }



    /**
     * Recherche par type de validation.
     *
     * @param TypeValidationEntity|string $type
     * @param QueryBuilder|null           $qb
     *
     * @return QueryBuilder
     */
    public function finderByTypeValidation($type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        if (!is_object($type)) {
            $type = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeValidation')->findOneByCode($type);
        }

        $qb->join("$alias.validation", "v")
            ->join("v.typeValidation", 'tv')
            ->andWhere("tv = :tv")->setParameter('tv', $type);

        return $qb;
    }



    /**
     *
     * @param EtatVolumeHoraireEntity $etatVolumeHoraire
     * @param QueryBuilder            $qb
     * @param string                  $alias
     *
     * @return QueryBuilder
     */
    public function finderByEtatVolumeHoraire(EtatVolumeHoraireEntity $etatVolumeHoraire = null, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        if ($etatVolumeHoraire) {
            $sEtatVolumeHoraire = $this->getServiceLocator()->get('applicationEtatVolumeHoraire');
            /* @var $sEtatVolumeHoraire EtatVolumeHoraire */

            $this->join($sEtatVolumeHoraire, $qb, 'etatVolumeHoraire');

            $qb->andWhere($sEtatVolumeHoraire->getAlias() . '.ordre >= ' . $etatVolumeHoraire->getOrdre());
        }

        return $qb;
    }



    /**
     *
     * @param EtatVolumeHoraireEntity $etatVolumeHoraire
     * @param QueryBuilder            $qb
     * @param string                  $alias
     *
     * @return QueryBuilder
     */
    public function finderByStrictEtatVolumeHoraire(EtatVolumeHoraireEntity $etatVolumeHoraire = null, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        if ($etatVolumeHoraire) {
            $sEtatVolumeHoraire = $this->getServiceLocator()->get('applicationEtatVolumeHoraire');
            /* @var $sEtatVolumeHoraire EtatVolumeHoraire */

            $this->join($sEtatVolumeHoraire, $qb, 'etatVolumeHoraire');

            $sEtatVolumeHoraire->finderById($etatVolumeHoraire->getId(), $qb);
        }

        return $qb;
    }



    /**
     * Retourne les volumes horaires qui ont fait ou non l'objet d'un contrat/avenant.
     *
     * @param boolean|\Application\Entity\Db\Contrat $contrat <code>true</code>, <code>false</code> ou
     *                                                        bien un Contrat précis
     * @param QueryBuilder|null                      $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByContrat($contrat, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        if ($contrat instanceof \Application\Entity\Db\Contrat) {
            $qb->addSelect("c")
                ->join("$alias.contrat", "c")
                ->andWhere("c = :contrat")->setParameter('contrat', $contrat);
        } else {
            $value = $contrat ? 'is not null' : 'is null';
            $qb->andWhere("$alias.contrat $value");
        }

        return $qb;
    }

    /**
     * Recherche les volumes horaires
     *
     * @param TypeValidationEntity $typeValidation
     * @param QueryBuilder|null    $qb
     *
     * @return QueryBuilder
     */
//    public function finderByNotHavingValidation(TypeValidationEntity $typeValidation, QueryBuilder $qb = null, $alias = null)
//    {
//        list($qb, $alias) = $this->initQuery($qb, $alias);
//
//        $qb
//                ->andWhere($qb->expr()->not($qb->expr()->exists(
//                        "SELECT valid FROM Application\Entity\Db\Validation valid WHERE valid.typeValidation = :typev AND $alias.validation = valid")))
//                ->setParameter('typev', $typeValidation);
//
//        return $qb;
//    }
}