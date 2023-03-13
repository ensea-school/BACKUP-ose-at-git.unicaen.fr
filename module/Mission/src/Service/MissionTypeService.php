<?php

namespace Mission\Service;


use Application\Service\AbstractEntityService;
use Mission\Entity\Db\TypeMission;
use UnicaenApp\Traits\SessionContainerTrait;

/**
 * Description of MissionTypeService
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTypeService extends AbstractEntityService
{
    use SessionContainerTrait;

    /**
     * retourne la classe des entités
     *
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return TypeMission::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'MissionType';
    }



    /**
     * @return TypeMission[]
     */
    public function getTypes(): array
    {
        $dql   = "SELECT tm FROM " . TypeMission::class." tm";
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }
}





?>