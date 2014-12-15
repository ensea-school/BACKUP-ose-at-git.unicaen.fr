<?php

namespace Application\Service;

/**
 * Description of FormuleService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleService extends AbstractService
{
    public function MajAllIdentified()
    {
        $sql = "BEGIN OSE_FORMULE.MAJ_ALL_IDT; END;";
        $this->getEntityManager()->getConnection()->executeQuery($sql);
    }
}