<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\EtatVolumeHoraire as EtatVolumeHoraireEntity;

/**
 * Description of EtatVolumeHoraire
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtatVolumeHoraire extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return EtatVolumeHoraireEntity::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'etatvh';
    }

    /**
     * Retourne l'état "Saisi"
     *
     * @return EtatVolumeHoraireEntity
     */
    public function getSaisi()
    {
        return $this->getRepo()->findOneBy(['code' => EtatVolumeHoraireEntity::CODE_SAISI]);
    }

    /**
     * Retourne l'état "Validé"
     *
     * @return EtatVolumeHoraireEntity
     */
    public function getValide()
    {
        return $this->getRepo()->findOneBy(['code' => EtatVolumeHoraireEntity::CODE_VALIDE]);
    }

    /**
     * Retourne l'état "Contrat édité"
     *
     * @return EtatVolumeHoraireEntity
     */
    public function getContratEdite()
    {
        return $this->getRepo()->findOneBy(['code' => EtatVolumeHoraireEntity::CODE_CONTRAT_EDITE]);
    }

    /**
     * Retourne l'état "Contrat signé"
     *
     * @return EtatVolumeHoraireEntity
     */
    public function getContratSigne()
    {
        return $this->getRepo()->findOneBy(['code' => EtatVolumeHoraireEntity::CODE_CONTRAT_SIGNE]);
    }

    /**
     * Retourne la liste des états de volumes horaires
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Application\Entity\Db\EtatVolumeHoraire[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");
        return parent::getList($qb, $alias);
    }

}