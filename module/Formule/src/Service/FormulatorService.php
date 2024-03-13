<?php

namespace Formule\Service;



use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\FormuleTableur;
use Unicaen\OpenDocument\Document;

/**
 * Description of FormulatorService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FormulatorService
{
    use ContextServiceAwareTrait;

    public function charger($filename)
    {
        $document = new Document();
        $document->loadFromFile($filename);
        $calc = $document->getCalc();

        $tableur = new FormuleTableur($calc);
        $tableur->setServiceContext($this->getServiceContext());
        $tableur->lire();

        return $tableur;
    }

}