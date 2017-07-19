<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant as IntervenantEntity;

/**
 * Description of DbEventService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class DbEventService extends AbstractService {

    /**
     * @return AbstractEntityService
     */
    public function stopManager()
    {
        return $this->execPlsql("OSE_EVENT.SET_ACTIF(FALSE);");
    }



    /**
     * @return AbstractEntityService
     */
    public function startManager()
    {
        return $this->execPlsql("OSE_EVENT.SET_ACTIF(TRUE);");
    }



    public function forcerCalculer( IntervenantEntity $intervenant )
    {
        $iid = (int)$intervenant->getId();

        return $this->execPlsql("OSE_EVENT.FORCE_CALCULER(".$iid.");");
    }



    /**
     * @param $plsql
     *
     * @return $this
     */
    private function execPlsql($plsql)
    {
        $sql = "BEGIN $plsql END;";
        $this->getEntityManager()->getConnection()->exec($sql);

        return $this;
    }

}