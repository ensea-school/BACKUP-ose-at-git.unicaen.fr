<?php

namespace Application\Service\Process;

use Application\Service\AbstractService;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\TypeModulateur;

/**
 * Processus de gestion des modulateurs
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Modulateur extends AbstractService
{
    /**
     *
     * @param ElementPedagogique $element
     * @return array
     */
    public function getTypeModulateurCodes( ElementPedagogique $element )
    {
        $codes = array();
        if ($element->getTauxFoad() > 0){
            $codes[] = 'IAE_FOAD';
        }
        if ($element->getFc()){
            $codes[] = 'IUTCAEN_FC';
            $codes[] = 'IAE_FC';
            $codes[] = 'DROIT_FC';
        }
        if ($element->getFc() && $element->getFi()){
            //$codes[] = TypeModulateur::FIFC;
        }
        return $codes;
    }

}