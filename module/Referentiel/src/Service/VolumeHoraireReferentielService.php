<?php

namespace Referentiel\Service;

use Application\Service\AbstractEntityService;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use Application\Provider\Privilege\Privileges;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Service\Entity\Db\EtatVolumeHoraire;
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
        return VolumeHoraireReferentiel::class;
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
     * @return VolumeHoraireReferentiel
     */
    public function newEntity()
    {
        // type de volume horaire par défaut
        $entity = parent::newEntity();
        $entity->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());

        $entity->setSource($this->getServiceSource()->getOse());
        $entity->setSourceCode(uniqid('ose-'));

        return $entity;
    }



    public function populateAutoValidation(VolumeHoraireReferentiel $entity)
    {
        $typeVolumeHoraire = $entity->getTypeVolumeHoraire();
        $canAutoValidate   = $this->getAuthorize()->isAllowed($entity, $typeVolumeHoraire->getPrivilegeReferentielAutoValidation());

        if ($canAutoValidate) $entity->setAutoValidation(true);
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
        $this->populateAutoValidation($entity);

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
    public function finderByEtatVolumeHoraire(?EtatVolumeHoraire $etatVolumeHoraire = null, ?QueryBuilder $qb = null, ?string $alias = null)
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
    public function finderByStrictEtatVolumeHoraire(?EtatVolumeHoraire $etatVolumeHoraire = null, ?QueryBuilder $qb = null, ?string $alias = null)
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