<?php

namespace Mission\Service;


use Application\Service\AbstractEntityService;
use Mission\Entity\Db\CentreCoutTypeMission;
use Mission\Entity\Db\TypeMission;
use Paiement\Entity\Db\CentreCout;

/**
 * Description of MissionTypeService
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTypeService extends AbstractEntityService
{

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

    public function getCentreCouts(): array
    {
        $dql   = "SELECT cm FROM " . CentreCout::class." cm WHERE cm.histoDestruction IS NULL";
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }

    public function saveCentreCoutTypeLinker($centreCoutTypeLinker){
        $this->getEntityManager()->persist($centreCoutTypeLinker);
    }



    public function removeCentreCoutLinker(CentreCoutTypeMission $centreCoutTypeMission, $softDelete = true)
    {
        if($softDelete){
            $centreCoutTypeMission->historiser($this->getServiceContext()->getUtilisateur());
            $this->getEntityManager()->persist($centreCoutTypeMission);
        }else{
            $this->getEntityManager()->remove($centreCoutTypeMission);

        }
        $this->getEntityManager()->flush($centreCoutTypeMission);

    }

}





?>