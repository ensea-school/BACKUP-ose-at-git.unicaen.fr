<?php

namespace OffreFormation\Service;

use Application\Service\AbstractEntityService;
use Application\Service\Periode;
use Application\Service\RuntimeException;
use OffreFormation\Entity\Db\GroupeTypeFormation;

/**
 * Description of GroupeTypeFormation
 * @method GroupeTypeFormation get($id)
 * @method GroupeTypeFormation[] list($id)
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class GroupeTypeFormationService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \OffreFormation\Entity\Db\GroupeTypeFormation::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'gtf';
    }



    /**
     * Sauvegarde la periode
     *
     * @param Periode $entity
     */
    public function save($entity)
    {
        if (empty($entity->getOrdre())) {
            $ordre = (int)$this->getEntityManager()->getConnection()->fetchOne("SELECT MAX(ORDRE) M FROM GROUPE_TYPE_FORMATION gtf");
            $ordre++;
            $entity->setOrdre($ordre);
        }

        return parent::save($entity);
    }
}