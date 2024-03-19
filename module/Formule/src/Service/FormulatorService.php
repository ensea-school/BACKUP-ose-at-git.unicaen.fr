<?php

namespace Formule\Service;


use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\FormuleTableur;
use Unicaen\OpenDocument\Calc;
use Unicaen\OpenDocument\Document;

/**
 * Description of FormulatorService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FormulatorService
{
    use ContextServiceAwareTrait;
    use TraducteurServiceAwareTrait;




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



    public function traduire(FormuleTableur $tableur): string
    {
        $php = '';

        $cells = $tableur->formuleCells();

        $first = true;
        foreach( $cells as $cell ){
            if (!$first){
                echo "\n\n\n";
            }
            $php .= $this->getServiceTraducteur()->indent($this->getServiceTraducteur()->traduire($tableur, $cell),2);
            $first = false;
        }

        $template = file_get_contents(getcwd() . '/module/Formule/src/Model/FormuleCalculTemplate.php');
        $template = str_replace('FormuleCalculTemplate', 'FormuleCalculateur', $template);
        $php = str_replace("/* TRAITEMENT */\n\n", $php, $template);

        return $php;
    }



}