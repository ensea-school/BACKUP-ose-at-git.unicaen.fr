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
     * @var EtatVolumeHoraireEntity[]
     */
    private $cache;



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
    public function getAlias()
    {
        return 'etatvh';
    }



    public function getByCode($code)
    {
        if (!isset($this->cache[$code])) {
            $this->cache[$code] = $this->getRepo()->findOneBy(['code' => $code]);
        }

        return $this->cache[$code];
    }



    /**
     * Retourne l'état "Saisi"
     *
     * @return EtatVolumeHoraireEntity
     */
    public function getSaisi()
    {
        return $this->getByCode(EtatVolumeHoraireEntity::CODE_SAISI);
    }



    /**
     * Retourne l'état "Validé"
     *
     * @return EtatVolumeHoraireEntity
     */
    public function getValide()
    {
        return $this->getByCode(EtatVolumeHoraireEntity::CODE_VALIDE);
    }



    /**
     * Retourne l'état "Contrat édité"
     *
     * @return EtatVolumeHoraireEntity
     */
    public function getContratEdite()
    {
        return $this->getByCode(EtatVolumeHoraireEntity::CODE_CONTRAT_EDITE);
    }



    /**
     * Retourne l'état "Contrat signé"
     *
     * @return EtatVolumeHoraireEntity
     */
    public function getContratSigne()
    {
        return $this->getByCode(EtatVolumeHoraireEntity::CODE_CONTRAT_SIGNE);
    }



    /**
     * Retourne la liste des états de volumes horaires
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return \Application\Entity\Db\EtatVolumeHoraire[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");

        return parent::getList($qb, $alias);
    }

}