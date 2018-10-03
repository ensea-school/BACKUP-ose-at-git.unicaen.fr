<?php

namespace Application\Service;

use Application\Entity\Db\Contrat;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\VolumeHoraire;

/**
 * Description of VolumeHoraire
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireService extends AbstractEntityService
{
    use TypeVolumeHoraireServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use SourceServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return VolumeHoraire::class;
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
     * @return VolumeHoraire
     */
    public function newEntity()
    {

        $entity = parent::newEntity();

        // type de volume horaire par défaut
        $entity->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());

        return $entity;
    }



    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param VolumeHoraire $entity Entité à détruire
     * @param bool          $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        if ($softDelete) {
            return parent::delete($entity, $softDelete); // TODO: Change the autogenerated stub
        }

        $sql = "DELETE FROM VOLUME_HORAIRE WHERE ID = " . (int)$entity->getId();
        $this->getEntityManager()->getConnection()->executeQuery($sql);

        return $this;
    }



    /**
     * Sauvegarde une entité
     *
     * @param mixed $entity
     *
     * @throws \RuntimeException
     * @return mixed
     */
    public function save($entity)
    {
        if (!$entity->getSource()) {
            $entity->setSource($this->getServiceSource()->getOse());
        }
        if (!$entity->getSourceCode()) {
            $entity->setSourceCode(uniqid('ose-'));
        }

        return parent::save($entity); // TODO: Change the autogenerated stub
    }



    /**
     * Recherche par intervenant concerné.
     *
     * @param Intervenant       $intervenant
     * @param QueryBuilder|null $qb
     *
     * @return QueryBuilder
     */
    public function finderByIntervenant(Intervenant $intervenant, QueryBuilder $qb = null, $alias = null)
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
     *
     * @param EtatVolumeHoraire $etatVolumeHoraire
     * @param QueryBuilder      $qb
     * @param string            $alias
     *
     * @return QueryBuilder
     */
    public function finderByEtatVolumeHoraire(EtatVolumeHoraire $etatVolumeHoraire = null, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        if ($etatVolumeHoraire) {
            $sEtatVolumeHoraire = $this->getServiceEtatVolumeHoraire();

            $this->join($sEtatVolumeHoraire, $qb, 'etatVolumeHoraire');

            $qb->andWhere($sEtatVolumeHoraire->getAlias() . '.ordre >= ' . $etatVolumeHoraire->getOrdre());
        }

        return $qb;
    }



    /**
     *
     * @param EtatVolumeHoraire $etatVolumeHoraire
     * @param QueryBuilder      $qb
     * @param string            $alias
     *
     * @return QueryBuilder
     */
    public function finderByStrictEtatVolumeHoraire(EtatVolumeHoraire $etatVolumeHoraire = null, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        if ($etatVolumeHoraire) {
            $sEtatVolumeHoraire = $this->getServiceEtatVolumeHoraire();

            $this->join($sEtatVolumeHoraire, $qb, 'etatVolumeHoraire');

            $sEtatVolumeHoraire->finderById($etatVolumeHoraire->getId(), $qb);
        }

        return $qb;
    }



    /**
     * Retourne les volumes horaires qui ont fait ou non l'objet d'un contrat/avenant.
     *
     * @param boolean|Contrat   $contrat                      <code>true</code>, <code>false</code> ou
     *                                                        bien un Contrat précis
     * @param QueryBuilder|null $queryBuilder
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
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.horaireDebut");
        $qb->addOrderBy("$alias.horaireFin");

        return $qb;
    }
}