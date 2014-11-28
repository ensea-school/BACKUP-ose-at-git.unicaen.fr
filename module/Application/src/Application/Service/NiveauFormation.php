<?php

namespace Application\Service;

use Application\Entity\Db\NiveauFormation as NiveauFormationEntity;

/**
 * Description of NiveauFormation
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class NiveauFormation extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\NiveauFormation';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'nf';
    }

    /**
     *
     * @param string $code
     * @return NiveauFormationEntity
     */
    public function getByCode( $code )
    {
        if (null == $code) return null;
        return $this->getRepo()->findOneBy(['code' => $code]);
    }
}