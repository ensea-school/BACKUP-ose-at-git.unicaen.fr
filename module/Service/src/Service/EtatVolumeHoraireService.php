<?php

namespace Service\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Service\Entity\Db\EtatVolumeHoraire;

/**
 * Description of EtatVolumeHoraire
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtatVolumeHoraireService extends AbstractEntityService
{
    /**
     * @var EtatVolumeHoraire[]
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
        return EtatVolumeHoraire::class;
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
     * @return EtatVolumeHoraire
     */
    public function getSaisi()
    {
        return $this->getByCode(EtatVolumeHoraire::CODE_SAISI);
    }



    /**
     * Retourne l'état "Validé"
     *
     * @return EtatVolumeHoraire
     */
    public function getValide()
    {
        return $this->getByCode(EtatVolumeHoraire::CODE_VALIDE);
    }



    /**
     * Retourne l'état "Contrat édité"
     *
     * @return EtatVolumeHoraire
     */
    public function getContratEdite()
    {
        return $this->getByCode(EtatVolumeHoraire::CODE_CONTRAT_EDITE);
    }



    /**
     * Retourne l'état "Contrat signé"
     *
     * @return EtatVolumeHoraire
     */
    public function getContratSigne()
    {
        return $this->getByCode(EtatVolumeHoraire::CODE_CONTRAT_SIGNE);
    }



    /**
     * Retourne la liste des états de volumes horaires
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return \Service\Entity\Db\EtatVolumeHoraire[]
     */
    public function getList(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");

        return parent::getList($qb, $alias);
    }

}