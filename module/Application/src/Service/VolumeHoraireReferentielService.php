<?php

namespace Application\Service;

use Application\Entity\Db\VolumeHoraireReferentiel;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Application\Entity\Db\EtatVolumeHoraire;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of VolumeHoraireReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireReferentielService extends AbstractEntityService
{
    use TypeVolumeHoraireServiceAwareTrait;
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
        return \Application\Entity\Db\VolumeHoraireReferentiel::class;
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

        $canAutoValidate = $this->getAuthorize()->isAllowed($entity, Privileges::REFERENTIEL_AUTOVALIDATION);
        if ($canAutoValidate) $entity->setAutoValidation(true);

        return $entity;
    }



    /**
     * Sauvegarde une entité
     *
     * @param VolumeHoraireReferentiel $entity
     *
     * @return mixed
     * @throws \RuntimeException
     */
    public function save($entity)
    {
        if (!$entity->getSource()) {
            $entity->setSource($this->getServiceSource()->getOse());
        }
        if (!$entity->getSourceCode()) {
            $entity->setSourceCode(uniqid('ose-'));
        }
        
        return parent::save($entity);
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
        [$qb, $alias] = $this->initQuery($qb, $alias);
        if ($etatVolumeHoraire) {
            $sEtatVolumeHoraire = $this->getServiceEtatVolumeHoraire();

            $this->join($sEtatVolumeHoraire, $qb, 'etatVolumeHoraireReferentiel');

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
        [$qb, $alias] = $this->initQuery($qb, $alias);
        if ($etatVolumeHoraire) {
            $sEtatVolumeHoraire = $this->getServiceEtatVolumeHoraire();

            $this->join($sEtatVolumeHoraire, $qb, 'etatVolumeHoraireReferentiel');

            $sEtatVolumeHoraire->finderById($etatVolumeHoraire->getId(), $qb);
        }

        return $qb;
    }

}