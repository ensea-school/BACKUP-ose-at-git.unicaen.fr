<?php

namespace Application\Service;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;

/**
 * Description of TypeIntervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeIntervenant extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\TypeIntervenant';
    }
    
    /**
     * Retourne le type d'intervenant Permanent
     * 
     * @return TypeIntervenantEntity
     */
    public function getPermanent()
    {
        return $this->getRepo()->findOneBy(array('code' => TypeIntervenantEntity::CODE_PERMANENT));
    }

    /**
     * Retourne le type d'intervenant Extérieur
     *
     * @return TypeIntervenantEntity
     */
    public function getExterieur()
    {
        return $this->getRepo()->findOneBy(array('code' => TypeIntervenantEntity::CODE_EXTERIEUR));
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'type_int';
    }

}