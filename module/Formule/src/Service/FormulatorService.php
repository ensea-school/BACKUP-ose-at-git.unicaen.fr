<?php

namespace Formule\Service;


use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\Db\Formule;
use Formule\Entity\Db\FormuleTestIntervenant;
use Formule\Entity\Db\FormuleTestVolumeHoraire;
use Formule\Entity\FormuleIntervenant;
use Formule\Model\AbstractFormuleCalcul;
use Formule\Model\Arrondisseur\Arrondisseur;
use Formule\Model\FormuleTableur;
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
    use FormuleServiceAwareTrait;

    private array        $formulesCalculCache = [];
    private ?string      $lastTestError       = null;
    private Arrondisseur $arrondisseur;



    public function __construct()
    {
        $this->arrondisseur = new Arrondisseur();
    }



    public function cacheDir(): string
    {
        return getcwd() . '/var/cache/formules/';
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



    public function implanter(string $filename): Formule
    {
        $tableur = $this->charger($filename);
        $formule = $tableur->formule();
        $formule->setPhpClass($this->traduire($tableur));
        $this->getServiceFormule()->save($formule);

        $this->makeWithCacheFile($formule, false);

        $this->test($tableur);
        if ($error = $this->getLastTestError()) {
            throw new \Exception($error);
        }

        return $formule;
    }



    public function calculer(FormuleIntervenant $intervenant, ?Formule $formule = null): void
    {
        if (empty($formule) && $intervenant instanceof FormuleTestIntervenant) {
            $formule = $intervenant->getFormule();
        }
        if (empty($formule)) {
            throw new \Exception('La formule de calcul n\'est pas spécifiée');
        }
        $fc = $this->getFormuleCalcul($formule);
        $fc->calculer($intervenant, $formule);
        $this->arrondisseur->arrondir($intervenant);
    }



    public function test(string|FormuleTableur $tableur): FormuleTestIntervenant
    {
        if (is_string($tableur)) {
            $tableur = $this->charger($tableur);
        }

        $test = $tableur->formuleIntervenant();
        try {
            $this->calculer($test, $tableur->formule(), false);
            $this->lastTestError = $this->checkFormuleResErreurs($test, $tableur);
        } catch (\Throwable $e) {
            $this->lastTestError = $e->getMessage() . ' ligne ' . $e->getLine();
            $test                = new FormuleTestIntervenant();
        }

        return $test;
    }



    public function getLastTestError(): ?string
    {
        return $this->lastTestError;
    }



    private function checkFormuleResErreurs(FormuleTestIntervenant $fi, FormuleTableur $tableur): ?string
    {
        $msg = null;
        $ths = [
            'ServiceFi'             => 'les heures de service en FI',
            'ServiceFa'             => 'les heures de service en FA',
            'ServiceFc'             => 'les heures de service en FC',
            'ServiceReferentiel'    => 'les heures de service référentiel',
            'ComplFi'               => 'les heures complémentaires en FI',
            'ComplFa'               => 'les heures complémentaires en FA',
            'ComplFc'               => 'les heures complémentaires en FC',
            'ComplReferentiel'      => 'les heures complémentaires de référentiel',
            'NonPayableFi'          => 'les heures non payables en Fi',
            'NonPayableFa'          => 'les heures non payables en Fa',
            'NonPayableFc'          => 'les heures non payables en Fc',
            'NonPayableReferentiel' => 'les heures non payables de référentiel',
            'Primes'                => 'les heures relatives aux primes',
        ];

        $trace = $fi->getDebugTrace();

        /** @var FormuleTestVolumeHoraire[] $vhs */
        $vhs = $fi->getVolumesHoraires();
        foreach ($vhs as $i => $vh) {
            foreach ($ths as $th => $merr) {
                $methodHeures  = 'getHeures' . $th;
                $methodAttendu = 'getHeuresAttendues' . $th;
                $attendu       = $vh->$methodAttendu();
                $calcule       = $vh->$methodHeures();

                if ($this->diffFloat($calcule, $attendu)) {
                    $msg = 'Diff OSE/Tableur ligne ' . ($tableur->mainLine() + $i) . ' : erreur sur ' . $merr . ' : ' . $calcule . ' calculées pour ' . $attendu . ' attendues';

                    $input = [
                        'structureCode'           => $vh->getStructureCode(),
                        'structureIsAffectation'  => $vh->isStructureAffectation(),
                        'structureIsUniv'         => $vh->isStructureUniv(),
                        'structureIsExterieur'    => $vh->isStructureExterieur(),
                        'serviceStatutaire'       => $vh->isServiceStatutaire(),
                        'tauxFi'                  => $vh->getTauxFi(),
                        'tauxFa'                  => $vh->getTauxFa(),
                        'tauxFc'                  => $vh->getTauxFc(),
                        'typeInterventionCode'    => $vh->getTypeInterventionCode(),
                        'tauxServiceDu'           => $vh->getTauxServiceDu(),
                        'tauxServiceCompl'        => $vh->getTauxServiceCompl(),
                        'ponderationServiceDu'    => $vh->getPonderationServiceDu(),
                        'ponderationServiceCompl' => $vh->getPonderationServiceCompl(),
                        'heures'                  => $vh->getHeures(),
                        'param1'                  => $vh->getParam1(),
                        'param2'                  => $vh->getParam2(),
                        'param3'                  => $vh->getParam3(),
                        'param4'                  => $vh->getParam4(),
                        'param5'                  => $vh->getParam5(),
                    ];
                    foreach ($input as $p => $v) {
                        if (is_bool($v)) {
                            $v = $v ? 'true' : 'false';
                        }
                        $msg .= "\n$p = $v";
                    }

                    if (isset($trace['vh'][$i])) {
                        $msg .= "\n\nValeurs en ligne calculées :";
                        foreach ($trace['vh'][$i] as $cell => $val) {
                            $tableurVal = $tableur->getCellFloatVal($cell . (string)$tableur->mainLine() + $i);
                            $msg        .= "\n$cell = $val";
                            if ($this->diffFloat($val, $tableurVal)) {
                                $msg .= ' calculé (' . $tableurVal . ' dans le tableur)';
                            }
                        }

                        if (isset($trace['global'])) {
                            $msg .= "\n\nValeurs globales :";
                            foreach ($trace['global'] as $cell => $val) {
                                $tableurVal = $tableur->getCellFloatVal($cell);
                                $msg        .= "\n$cell = $val";
                                if ($this->diffFloat($val, $tableurVal)) {
                                    $msg .= ' calculé (' . $tableurVal . ' dans le tableur)';
                                }
                            }
                        }
                    }
                }
            }
        }
        return $msg;
    }



    private function diffFloat(float $f1, float $f2)
    {
        $if1 = (int)round($f1 * 100);
        $if2 = (int)round($f2 * 100);

        return $if1 !== $if2;
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
            $traduction = $this->getServiceTraducteur()->traduire($tableur, $cell);

            if ($traduction) {
                if (!$first) {
                    $php .= "\n\n\n";
                }
                $php .= $this->getServiceTraducteur()->indent($traduction, 1);
            }
            $first = false;
        }

        $template = "<?php

use Formule\Model\AbstractFormuleCalcul;

class FormuleCalculTemplate extends AbstractFormuleCalcul
{

    protected int \$mainLine = 20/* MAIN_LINE*/;



/* TRAITEMENT */

}";
        $template = str_replace('FormuleCalculTemplate', $this->formuleClassName($tableur->formule()), $template);
        $php      = str_replace("/* TRAITEMENT */\n\n", $php, $template);
        $php      = str_replace('20/* MAIN_LINE*/', $tableur->mainLine(), $php);

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
        $dir      = $this->cacheDir();
        $filename = $dir . $formule->getCode() . '.php';

        if (!file_exists($dir)) {
            $oldumask = umask(0);
            mkdir($dir, 0777); // or even 01777 so you get the sticky bit set
            umask($oldumask);
        }
        if (file_exists($filename)) {
            unlink($filename);
        }
        file_put_contents($filename, $formule->getPhpClass());
        chmod($filename, 0666);

        if ($instanciate) {
            $classname = $this->formuleClassName($formule);
            require $filename;
            return new $classname;
        } else {
            return null;
        }
    }

}