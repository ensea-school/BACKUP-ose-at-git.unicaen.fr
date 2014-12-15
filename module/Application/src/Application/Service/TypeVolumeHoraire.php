<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;

/**
 * Description of TypeVolumeHoraire
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeVolumeHoraire extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\TypeVolumeHoraire';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'typevh';
    }

    /**
     * Retourne le type de volume horaire "Prévu"
     *
     * @return TypeVolumeHoraireEntity
     */
    public function getPrevu()
    {
        return $this->getRepo()->findOneBy(array('code' => TypeVolumeHoraireEntity::CODE_PREVU));
    }

    /**
     * Retourne le type de volume horaire "Réalisé"
     *
     * @return TypeVolumeHoraireEntity
     */
    public function getRealise()
    {
        return $this->getRepo()->findOneBy(array('code' => TypeVolumeHoraireEntity::CODE_REALISE));
    }

    /**
     * Retourne le type de volume horaire "Payé"
     *
     * @return TypeVolumeHoraireEntity
     */
    public function getPaye()
    {
        return $this->getRepo()->findOneBy(array('code' => TypeVolumeHoraireEntity::CODE_PAYE));
    }

    /**
     * Retourne la liste des types de volumes horaires
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Application\Entity\Db\TypeVolumeHoraire[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");
        return parent::getList($qb, $alias);
    }

}