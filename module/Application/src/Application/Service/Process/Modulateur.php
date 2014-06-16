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
        if (1 == $element->getTauxFoad()){
            $codes[] = TypeModulateur::FOAD;
        }
        if ($element->getFc()){
            $codes[] = TypeModulateur::FC;
        }
        return $codes;
    }

}