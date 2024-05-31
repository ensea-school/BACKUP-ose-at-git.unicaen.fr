<?php

namespace Formule\Service;


use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\Db\Formule;
use Formule\Entity\Db\FormuleTestIntervenant;
use Formule\Entity\Db\FormuleTestVolumeHoraire;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleTableur;
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

        $test = $tableur->formuleIntervenant();
        $trace = $this->calculer($test, $formule);
        $this->checkFormuleResErreurs($test, $tableur, $trace);

        return $formule;
    }



    private function checkFormuleResErreurs(FormuleTestIntervenant $fi, FormuleTableur $tableur, array $trace): void
    {
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

        /** @var FormuleTestVolumeHoraire[] $vhs */
        $vhs = $fi->getVolumesHoraires();
        foreach ($vhs as $i => $vh) {
            foreach ($ths as $th => $merr) {
                $methodHeures = 'getHeures' . $th;
                $methodAttendu = 'getHeuresAttendues' . $th;
                $attendu = $vh->$methodAttendu();
                $calcule = $vh->$methodHeures();

                if ($calcule !== $attendu) {
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
                            $msg .= "\n$cell = $val";
                            if ($val !== $tableurVal) {
                                $msg .= ' calculé (' . $tableurVal . ' dans le tableur)';
                            }
                        }

                        if (isset($trace['global'])) {
                            $msg .= "\n\nValeurs globales :";
                            foreach ($trace['global'] as $cell => $val) {
                                $tableurVal = $tableur->getCellFloatVal($cell);
                                $msg .= "\n$cell = $val";
                                if ($val !== $tableurVal) {
                                    $msg .= ' calculé (' . $tableurVal . ' dans le tableur)';
                                }
                            }
                        }
                    }
                    throw new \Exception($msg);
                }
            }
        }
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