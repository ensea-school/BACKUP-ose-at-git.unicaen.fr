<?php

namespace OffreFormation\Service;

use Application\Entity\Db\NiveauFormation;
use Application\Service\AbstractEntityService;
use RuntimeException;

/**
 * Description of NiveauFormation
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class NiveauFormationService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return NiveauFormation::class;
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
     * @return NiveauFormation
     */
    public function getByCode( $code )
    {
        if (null == $code) return null;
        return $this->getRepo()->findOneBy(['code' => $code]);
    }
}