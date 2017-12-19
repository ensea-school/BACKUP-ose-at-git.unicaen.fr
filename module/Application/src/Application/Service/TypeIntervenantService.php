<?php

namespace Application\Service;

use Application\Entity\Db\TypeIntervenant;

/**
 * Description of TypeIntervenantService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeIntervenantService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeIntervenant::class;
    }

    /**
     * Retourne le type d'intervenant Permanent
     *
     * @return TypeIntervenant
     */
    public function getPermanent()
    {
        return $this->getRepo()->findOneBy(['code' => TypeIntervenant::CODE_PERMANENT]);
    }

    /**
     * Retourne le type d'intervenant Extérieur
     *
     * @return TypeIntervenant
     */
    public function getExterieur()
    {
        return $this->getRepo()->findOneBy(['code' => TypeIntervenant::CODE_EXTERIEUR]);
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'type_int';
    }

    /**
     *
     * @param string $code
     * @return TypeIntervenant
     */
    public function getByCode( $code )
    {
        if (null == $code) return null;
        return $this->getRepo()->findOneBy(['code' => $code]);
    }

}