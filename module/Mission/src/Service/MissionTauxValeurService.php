<?php

namespace Mission\Service;


use Application\Service\AbstractEntityService;
use Mission\Entity\Db\MissionTauxRemuValeur;
use UnicaenApp\Traits\SessionContainerTrait;

/**
 * Description of MissionTauxService
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTauxValeurService extends AbstractEntityService
{
    use SessionContainerTrait;

    /**
     * retourne la classe des entités
     *
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return MissionTauxRemuValeur::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'MissionTaux';
    }

}





?>