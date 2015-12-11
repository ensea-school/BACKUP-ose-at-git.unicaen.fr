<?php

namespace Application\Service;


/**
 * Description of MiseEnPaiementIntervenantStructure
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementIntervenantStructure extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\MiseEnPaiementIntervenantStructure::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'mep_i_s';
    }
}