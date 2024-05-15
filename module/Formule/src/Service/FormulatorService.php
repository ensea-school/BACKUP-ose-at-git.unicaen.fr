<?php

namespace Formule\Service;


use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\Db\Formule;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleTableur;
use Formule\Model\AbstractFormuleCalcul;
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

    private array $formulesCalculCache = [];



    public function cacheDir(): string
    {
        return getcwd() . '/cache/formules/';
    }



    public function update(string $filename): Formule
    {
        $em = $this->getServiceContext()->getEntityManager();

        $tableur = $this->charger($filename);
        $formule = $tableur->formule();
        $formule->setPhpClass($this->traduire($tableur));
        $em->persist($formule);
        $em->flush($formule);

        $this->makeWithCacheFile($formule, false);

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
        foreach ($cells as $cell) {
            if (!$first) {
                $php .= "\n\n\n";
            }
            $php .= $this->getServiceTraducteur()->indent($this->getServiceTraducteur()->traduire($tableur, $cell), 2);
            $first = false;
        }

        $template = file_get_contents(getcwd() . '/module/Formule/src/Model/FormuleCalculTemplate.php');
        $template = str_replace('FormuleCalculTemplate', 'FormuleCalculateur', $template);
        $php = str_replace("/* TRAITEMENT */\n\n", $php, $template);

        return $php;
    }



    private function getFormuleCalcul(Formule $formule): AbstractFormuleCalcul
    {
        if (!isset($this->formulesCalculCache[$formule->getId()])) {
            $this->formulesCalculCache[$formule->getId()] = $this->makeWithCacheFile($formule);
        }
        return $this->formulesCalculCache[$formule->getId()];
    }



    private function makeWithCacheFile(Formule $formule, $instanciate = true): ?AbstractFormuleCalcul
    {
        $dir = $this->cacheDir();
        $filename = $dir . $formule->getCode() . '.php';

        if (!file_exists($filename)) {
            if (!file_exists($dir)) {
                mkdir($dir, 0777);
            }
            file_put_contents($filename, $formule->getPhpClass());
        }

        if ($instanciate) {
            require $filename;
            return new \FormuleCalculateur();
        } else {
            return null;
        }
    }



    public function calculer(FormuleIntervenant $intervenant, Formule $formule): array
    {
        $fc = $this->getFormuleCalcul($formule);
        return $fc->calculer($intervenant, $formule);
    }

}