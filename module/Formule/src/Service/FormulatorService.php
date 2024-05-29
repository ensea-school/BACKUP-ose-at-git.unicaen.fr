<?php

namespace Formule\Service;


use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\Db\Formule;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleTableur;
use Formule\Entity\FormuleVolumeHoraire;
use Formule\Model\AbstractFormuleCalcul;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
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
    use TypeIntervenantServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;

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

        try {
            $test = $this->createFormuleTest();
            $this->calculer($test, $formule);
        } catch (\Error $e) {
            throw new \Exception($e->getMessage());
            $test = null;
        }

        if ($test && !$this->formuleTestOK($test)){
            throw new \Exception('La formule ne calcule pas correctement les résultats : il y a une perte d\'heures lors de la convertion en HETD');
        }

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



    private function formuleClassName(Formule $formule): string
    {
        return 'FormuleClass_' . $formule->getCode();
    }



    private function traduire(FormuleTableur $tableur): string
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
        $template = str_replace('FormuleCalculTemplate', $this->formuleClassName($tableur->formule()), $template);
        $php = str_replace("/* TRAITEMENT */\n\n", $php, $template);

        return $php;
    }



    private function createFormuleTest(): FormuleIntervenant
    {
        $test = new FormuleIntervenant();
        $test->setAnnee($this->getServiceContext()->getAnnee());
        $test->setTypeIntervenant($this->getServiceTypeIntervenant()->getPermanent());
        $test->setStructureCode('UFR1');
        $test->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getRealise());
        $test->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
        $test->getServiceDu(192);

        $vh1 = new FormuleVolumeHoraire();
        $vh1->setService(1);
        $vh1->setVolumeHoraire(1);
        $vh1->setStructureCode('UFR1');
        $vh1->setTauxFi(1);
        $vh1->setTauxFa(0);
        $vh1->setTauxFc(0);
        $vh1->setTauxServiceDu(1.5);
        $vh1->setTauxServiceCompl(1.5);
        $vh1->setTypeInterventionCode('CM');
        $vh1->setHeures(80);
        $test->addVolumeHoraire($vh1);

        $vh2 = new FormuleVolumeHoraire();
        $vh2->setService(2);
        $vh2->setVolumeHoraire(2);
        $vh2->setStructureCode('UFR2');
        $vh1->setTauxFi(1);
        $vh1->setTauxFa(0);
        $vh1->setTauxFc(0);
        $vh2->setTypeInterventionCode('TD');
        $vh2->setHeures(70);
        $test->addVolumeHoraire($vh2);

        $vh3 = new FormuleVolumeHoraire();
        $vh3->setServiceReferentiel(3);
        $vh3->setVolumeHoraireReferentiel(3);
        $vh3->setStructureCode('UFR1');
        $vh3->setHeures(60);
        $test->addVolumeHoraire($vh3);

        return $test;
    }



    private function formuleTestOK(FormuleIntervenant $test): bool
    {
        $total = 0;
        foreach( $test->getVolumesHoraires() as $vh){
            $total += $vh->getHeuresServiceFi() + $vh->getHeuresComplFi() + $vh->getHeuresNonPayableFi();
            $total += $vh->getHeuresServiceFa() + $vh->getHeuresComplFa() + $vh->getHeuresNonPayableFa();
            $total += $vh->getHeuresServiceFc() + $vh->getHeuresComplFc() + $vh->getHeuresNonPayableFc();
            $total += $vh->getHeuresServiceReferentiel() + $vh->getHeuresComplReferentiel() + $vh->getHeuresNonPayableReferentiel();
            $total += $vh->getHeuresPrimes();
        }

        $attendu = 80*1.5 + 70 + 60;

        return $total == $attendu;
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
                $oldumask = umask(0);
                mkdir($dir, 0777); // or even 01777 so you get the sticky bit set
                umask($oldumask);
            }
            file_put_contents($filename, $formule->getPhpClass());
        }

        if ($instanciate) {
            $classname = $this->formuleClassName($formule);
            require $filename;
            return new $classname;
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