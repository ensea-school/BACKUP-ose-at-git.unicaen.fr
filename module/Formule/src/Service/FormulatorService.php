<?php

namespace Formule\Service;


use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\Db\Formule;
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
    use TraducteurServiceAwareTrait;



    public function update(string $filename): Formule
    {
        $em = $this->getServiceContext()->getEntityManager();

        $tableur = $this->charger($filename);
        $formule = $tableur->formule();
        $formule->setPhpClass($this->traduire($tableur));
        $em->persist($formule);
        $em->flush($formule);

        return $formule;
    }



    public function charger(string $filename): FormuleTableur
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
                $php .= "\n\n\n";
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