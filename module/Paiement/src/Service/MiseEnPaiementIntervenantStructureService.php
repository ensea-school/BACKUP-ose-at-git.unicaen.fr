<?php

namespace Paiement\Service;


use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;

/**
 * Description of MiseEnPaiementIntervenantStructure
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementIntervenantStructureService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Paiement\Entity\Db\MiseEnPaiementIntervenantStructure::class;
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