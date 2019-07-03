<?php

namespace Application\Controller;

use RuntimeException;


/**
 * Description of StructureController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureController extends AbstractController
{

    public function voirAction()
    {
        $structure = $this->getEvent()->getParam('structure');

        if (!$structure) {
            throw new RuntimeException("Structure non spécifiée ou introuvable.");
        }

        $title = (string)$structure;
        return compact('structure', 'title');
    }

}