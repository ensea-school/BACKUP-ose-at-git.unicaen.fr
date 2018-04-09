<?php

namespace Application\Controller;


/**
 * Description of FormuleController
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class FormuleController extends AbstractController
{

    public function calculerToutAction()
    {
        $this->em()->getConnection()->exec('BEGIN OSE_FORMULE.CALCULER_TOUT; END;');
    }

}