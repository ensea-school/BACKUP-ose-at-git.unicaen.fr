<?php

namespace Application\Service;

use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use Application\Entity\Db\EtatVolumeHoraire as EtatVolumeHoraireEntity;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of VolumeHoraireReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireReferentiel extends AbstractEntityService
{
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\VolumeHoraireReferentiel';
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'vhr';
    }



    /**
     *
     * @return \Application\Entity\Db\VolumeHoraireReferentiel
     */
    public function newEntity()
    {
        // type de volume horaire par défaut
        $entity = parent::newEntity();
        $entity->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());

        return $entity;
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
            $sEtatVolumeHoraire = $this->getServiceEtatVolumeHoraire();

            $this->join($sEtatVolumeHoraire, $qb, 'etatVolumeHoraireReferentiel');

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
            $sEtatVolumeHoraire = $this->getServiceEtatVolumeHoraire();

            $this->join($sEtatVolumeHoraire, $qb, 'etatVolumeHoraireReferentiel');

            $sEtatVolumeHoraire->finderById($etatVolumeHoraire->getId(), $qb);
        }

        return $qb;
    }

}