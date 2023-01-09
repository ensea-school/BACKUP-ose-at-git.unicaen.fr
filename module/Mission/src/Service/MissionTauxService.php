<?php

namespace Mission\Service;


use Application\Service\AbstractEntityService;
use Mission\Entity\Db\MissionTauxRemu;
use UnicaenApp\Traits\SessionContainerTrait;

/**
 * Description of MissionTauxService
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTauxService extends AbstractEntityService
{
    use SessionContainerTrait;

    /**
     * retourne la classe des entités
     *
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return MissionTauxRemu::class;
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

    /**
     * @return MissionTauxRemu[]
     */
    public function getTauxRemus(): array
    {
        $dql          = "SELECT msr FROM " . MissionTauxRemu::class . " msr";
        $query        = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }
}





?>