<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Service\Entity\Db\EtatVolumeHoraireAwareTrait;
use Application\Entity\Db\Traits\FormuleAwareTrait;
use Intervenant\Entity\Db\TypeIntervenantAwareTrait;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use Application\Hydrator\FormuleTestIntervenantHydrator;

class FormuleTestIntervenant
{
    use FormuleAwareTrait;
    use AnneeAwareTrait;
    use TypeIntervenantAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;

    private ?int $id = null;

    private ?string $libelle = null;

    private ?string $structureCode = null;

    private float $heuresServiceStatutaire = 0;

    private float $heuresServiceModifie = 0;

    private bool $depassementServiceDuSansHC = false;

    private float $tauxCmServiceDu = 1.5;

    private float $tauxCmServiceCompl = 1.5;

    private float $tauxTpServiceDu = 1;

    private float $tauxTpServiceCompl = 2 / 3;

    private float $tauxAutre1ServiceDu = 1;

    private float $tauxAutre1ServiceCompl = 1;

    private ?string $tauxAutre1Code = null;

    private float $tauxAutre2ServiceDu = 1;

    private float $tauxAutre2ServiceCompl = 1;

    private ?string $tauxAutre2Code = null;

    private float $tauxAutre3ServiceDu = 1;

    private float $tauxAutre3ServiceCompl = 1;

    private ?string $tauxAutre3Code = null;

    private float $tauxAutre4ServiceDu = 1;

    private float $tauxAutre4ServiceCompl = 1;

    private ?string $tauxAutre4Code = null;

    private float $tauxAutre5ServiceDu = 1;

    private float $tauxAutre5ServiceCompl = 1;

    private ?string $tauxAutre5Code = null;

    private ?string $param1 = null;

    private ?string $param2 = null;

    private ?string $param3 = null;

    private ?string $param4 = null;

    private ?string $param5 = null;

    private float $aServiceDu = 0;

    private ?float $cServiceDu = null;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $volumeHoraireTest;

    private ?string $debugInfo = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    public function setLibelle(?string $libelle): FormuleTestIntervenant
    {
        $this->libelle = $libelle;

        return $this;
    }



    public function getStructureCode(): ?string
    {
        return $this->structureCode;
    }



    public function setStructureCode(?string $structureCode): FormuleTestIntervenant
    {
        $this->structureCode = $structureCode;

        return $this;
    }



    /**
     * @return array|string[]
     */
    public function getStructures(): array
    {
        $structures = [];
        if ($this->getStructureCode()) {
            $structures[$this->getStructureCode()] = $this->getStructureCode();
        }
        foreach ($this->getVolumeHoraireTest() as $vht) {
            if ($vht->getStructureCode() && $vht->getStructureCode() != '__EXTERIEUR__') {
                $structures[$vht->getStructureCode()] = $vht->getStructureCode();
            }
        }
        asort($structures);

        return $structures;
    }



    public function getHeuresServiceStatutaire(): float
    {
        return $this->heuresServiceStatutaire;
    }



    public function setHeuresServiceStatutaire(float $heuresServiceStatutaire): FormuleTestIntervenant
    {
        $this->heuresServiceStatutaire = $heuresServiceStatutaire;

        return $this;
    }



    public function getHeuresServiceModifie(): float
    {
        return $this->heuresServiceModifie;
    }



    public function setHeuresServiceModifie(float $heuresServiceModifie): FormuleTestIntervenant
    {
        $this->heuresServiceModifie = $heuresServiceModifie;

        return $this;
    }



    public function getDepassementServiceDuSansHC(): bool
    {
        return $this->depassementServiceDuSansHC;
    }



    public function setDepassementServiceDuSansHC(bool $depassementServiceDuSansHC): FormuleTestIntervenant
    {
        $this->depassementServiceDuSansHC = $depassementServiceDuSansHC;

        return $this;
    }



    public function getTauxCmServiceDu(): float
    {
        return $this->tauxCmServiceDu;
    }



    public function setTauxCmServiceDu(float $tauxCmServiceDu): FormuleTestIntervenant
    {
        $this->tauxCmServiceDu = $tauxCmServiceDu;

        return $this;
    }



    public function getTauxCmServiceCompl(): float
    {
        return $this->tauxCmServiceCompl;
    }



    public function setTauxCmServiceCompl(float $tauxCmServiceCompl): FormuleTestIntervenant
    {
        $this->tauxCmServiceCompl = $tauxCmServiceCompl;

        return $this;
    }



    public function getTauxTpServiceDu(): float
    {
        return $this->tauxTpServiceDu;
    }



    public function setTauxTpServiceDu(float $tauxTpServiceDu): FormuleTestIntervenant
    {
        $this->tauxTpServiceDu = $tauxTpServiceDu;

        return $this;
    }



    public function getTauxTpServiceCompl(): float
    {
        return $this->tauxTpServiceCompl;
    }



    public function setTauxTpServiceCompl(float $tauxTpServiceCompl): FormuleTestIntervenant
    {
        $this->tauxTpServiceCompl = $tauxTpServiceCompl;

        return $this;
    }



    public function getTauxAutre1ServiceDu(): float
    {
        return $this->tauxAutre1ServiceDu;
    }



    public function setTauxAutre1ServiceDu(float $tauxAutre1ServiceDu): FormuleTestIntervenant
    {
        $this->tauxAutre1ServiceDu = $tauxAutre1ServiceDu;

        return $this;
    }



    public function getTauxAutre1ServiceCompl(): float
    {
        return $this->tauxAutre1ServiceCompl;
    }



    public function setTauxAutre1ServiceCompl(float $tauxAutre1ServiceCompl): FormuleTestIntervenant
    {
        $this->tauxAutre1ServiceCompl = $tauxAutre1ServiceCompl;

        return $this;
    }



    public function getTauxAutre1Code(): ?string
    {
        return $this->tauxAutre1Code;
    }



    public function setTauxAutre1Code(?string $tauxAutre1Code): FormuleTestIntervenant
    {
        $this->tauxAutre1Code = $tauxAutre1Code;
        return $this;
    }



    public function getTauxAutre2ServiceDu(): float
    {
        return $this->tauxAutre2ServiceDu;
    }



    public function setTauxAutre2ServiceDu(float $tauxAutre2ServiceDu): FormuleTestIntervenant
    {
        $this->tauxAutre2ServiceDu = $tauxAutre2ServiceDu;

        return $this;
    }



    public function getTauxAutre2ServiceCompl(): float
    {
        return $this->tauxAutre2ServiceCompl;
    }



    public function setTauxAutre2ServiceCompl(float $tauxAutre2ServiceCompl): FormuleTestIntervenant
    {
        $this->tauxAutre2ServiceCompl = $tauxAutre2ServiceCompl;

        return $this;
    }



    public function getTauxAutre2Code(): ?string
    {
        return $this->tauxAutre2Code;
    }



    public function setTauxAutre2Code(?string $tauxAutre2Code): FormuleTestIntervenant
    {
        $this->tauxAutre2Code = $tauxAutre2Code;
        return $this;
    }



    public function getTauxAutre3ServiceDu(): float
    {
        return $this->tauxAutre3ServiceDu;
    }



    public function setTauxAutre3ServiceDu(float $tauxAutre3ServiceDu): FormuleTestIntervenant
    {
        $this->tauxAutre3ServiceDu = $tauxAutre3ServiceDu;

        return $this;
    }



    public function getTauxAutre3ServiceCompl(): float
    {
        return $this->tauxAutre3ServiceCompl;
    }



    public function setTauxAutre3ServiceCompl(float $tauxAutre3ServiceCompl): FormuleTestIntervenant
    {
        $this->tauxAutre3ServiceCompl = $tauxAutre3ServiceCompl;

        return $this;
    }



    public function getTauxAutre3Code(): ?string
    {
        return $this->tauxAutre3Code;
    }



    public function setTauxAutre3Code(?string $tauxAutre3Code): FormuleTestIntervenant
    {
        $this->tauxAutre3Code = $tauxAutre3Code;
        return $this;
    }



    public function getTauxAutre4ServiceDu(): float
    {
        return $this->tauxAutre4ServiceDu;
    }



    public function setTauxAutre4ServiceDu(float $tauxAutre4ServiceDu): FormuleTestIntervenant
    {
        $this->tauxAutre4ServiceDu = $tauxAutre4ServiceDu;

        return $this;
    }



    public function getTauxAutre4ServiceCompl(): float
    {
        return $this->tauxAutre4ServiceCompl;
    }



    public function setTauxAutre4ServiceCompl(float $tauxAutre4ServiceCompl): FormuleTestIntervenant
    {
        $this->tauxAutre4ServiceCompl = $tauxAutre4ServiceCompl;

        return $this;
    }



    public function getTauxAutre4Code(): ?string
    {
        return $this->tauxAutre4Code;
    }



    public function setTauxAutre4Code(?string $tauxAutre4Code): FormuleTestIntervenant
    {
        $this->tauxAutre4Code = $tauxAutre4Code;
        return $this;
    }



    public function getTauxAutre5ServiceDu(): float
    {
        return $this->tauxAutre5ServiceDu;
    }



    public function setTauxAutre5ServiceDu(float $tauxAutre5ServiceDu): FormuleTestIntervenant
    {
        $this->tauxAutre5ServiceDu = $tauxAutre5ServiceDu;

        return $this;
    }



    public function getTauxAutre5ServiceCompl(): float
    {
        return $this->tauxAutre5ServiceCompl;
    }



    public function setTauxAutre5ServiceCompl(float $tauxAutre5ServiceCompl): FormuleTestIntervenant
    {
        $this->tauxAutre5ServiceCompl = $tauxAutre5ServiceCompl;

        return $this;
    }



    public function getTauxAutre5Code(): ?string
    {
        return $this->tauxAutre5Code;
    }



    public function setTauxAutre5Code(?string $tauxAutre5Code): FormuleTestIntervenant
    {
        $this->tauxAutre5Code = $tauxAutre5Code;
        return $this;
    }



    public function getTauxAutreServiceDu(int $index): float
    {
        return $this->{"tauxAutre" . $index . "ServiceDu"};
    }



    public function setTauxAutreServiceDu(int $index, float $tauxAutreServiceDu): FormuleTestIntervenant
    {
        $this->{"tauxAutre" . $index . "ServiceDu"} = $tauxAutreServiceDu;

        return $this;
    }



    public function getTauxAutreServiceCompl(int $index): float
    {
        return $this->{"tauxAutre" . $index . "ServiceCompl"};
    }



    public function setTauxAutreServiceCompl(int $index, float $tauxAutreServiceCompl): FormuleTestIntervenant
    {
        $this->{"tauxAutre" . $index . "ServiceCompl"} = $tauxAutreServiceCompl;

        return $this;
    }



    public function getTauxAutreCode(int $index): ?string
    {
        return $this->{"tauxAutre" . $index . "Code"};
    }



    public function setTauxAutreCode(int $index, ?string $tauxAutreCode): FormuleTestIntervenant
    {
        $this->{"tauxAutre" . $index . "Code"} = $tauxAutreCode;
        return $this;
    }



    public function getParam1(): ?string
    {
        return $this->param1;
    }



    public function setParam1(?string $param1): FormuleTestIntervenant
    {
        $this->param1 = $param1;

        return $this;
    }



    public function getParam2(): ?string
    {
        return $this->param2;
    }



    public function setParam2(?string $param2): FormuleTestIntervenant
    {
        $this->param2 = $param2;

        return $this;
    }



    public function getParam3(): ?string
    {
        return $this->param3;
    }



    public function setParam3(?string $param3): FormuleTestIntervenant
    {
        $this->param3 = $param3;

        return $this;
    }



    public function getParam4(): ?string
    {
        return $this->param4;
    }



    public function setParam4(?string $param4): FormuleTestIntervenant
    {
        $this->param4 = $param4;

        return $this;
    }



    public function getParam5(): ?string
    {
        return $this->param5;
    }



    public function setParam5(?string $param5): FormuleTestIntervenant
    {
        $this->param5 = $param5;

        return $this;
    }



    public function getAServiceDu(): ?float
    {
        return $this->aServiceDu;
    }



    public function setAServiceDu(?float $aServiceDu): FormuleTestIntervenant
    {
        $this->aServiceDu = $aServiceDu;

        return $this;
    }



    public function getCServiceDu(): ?float
    {
        return $this->cServiceDu;
    }



    public function setCServiceDu(?float $cServiceDu): FormuleTestIntervenant
    {
        $this->cServiceDu = $cServiceDu;

        return $this;
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->volumeHoraireTest = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Add volumeHoraireTest
     *
     * @param FormuleTestVolumeHoraire $volumeHoraireTest
     *
     * @return Service
     */
    public function addVolumeHoraireTest(FormuleTestVolumeHoraire $volumeHoraireTest): FormuleTestIntervenant
    {
        $this->volumeHoraireTest[] = $volumeHoraireTest;

        return $this;
    }



    /**
     * Remove volumeHoraireTest
     *
     * @param FormuleTestVolumeHoraire $volumeHoraireTest
     */
    public function removeVolumeHoraireTest(FormuleTestVolumeHoraire $volumeHoraireTest): FormuleTestIntervenant
    {
        $this->volumeHoraireTest->removeElement($volumeHoraireTest);

        return $this;
    }



    /**
     * @return FormuleTestIntervenant
     */
    public function clearVolumeHoraireTest(): FormuleTestIntervenant
    {
        $this->volumeHoraireTest->clear();

        return $this;
    }



    /**
     * Get volumeHoraireTest
     *
     * @return \Doctrine\Common\Collections\Collection|FormuleTestVolumeHoraire[]
     */
    public function getVolumeHoraireTest()
    {
        return $this->volumeHoraireTest;
    }



    /**
     * @return float
     */
    public function getAServiceFi()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getAServiceFi();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getAServiceFa()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getAServiceFa();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getAServiceFc()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getAServiceFc();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getAServiceReferentiel()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getAServiceReferentiel();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getAHeuresComplFi()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getAHeuresComplFi();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getAHeuresComplFa()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getAHeuresComplFa();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getAHeuresComplFc()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getAHeuresComplFc();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getAHeuresComplFcMajorees()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getAHeuresComplFcMajorees();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getAHeuresComplReferentiel()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getAHeuresComplReferentiel();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getCServiceFi()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getCServiceFi();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getCServiceFa()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getCServiceFa();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getCServiceFc()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getCServiceFc();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getCServiceReferentiel()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getCServiceReferentiel();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getCHeuresComplFi()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getCHeuresComplFi();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getCHeuresComplFa()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getCHeuresComplFa();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getCHeuresComplFc()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getCHeuresComplFc();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getCHeuresComplFcMajorees()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getCHeuresComplFcMajorees();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getCHeuresComplReferentiel()
    {
        $sum = 0;
        foreach ($this->getVolumeHoraireTest() as $vht) {
            $sum += $vht->getCHeuresComplReferentiel();
        }

        return $sum;
    }



    /**
     * @return float
     */
    public function getCServiceAssure()
    {
        return $this->getCServiceFi()
            + $this->getCServiceFa()
            + $this->getCServiceFc()
            + $this->getCServiceReferentiel();
    }



    /**
     * @return float
     */
    public function getCHeuresComplAPayer()
    {
        return $this->getCHeuresComplFi()
            + $this->getCHeuresComplFa()
            + $this->getCHeuresComplFc()
            + $this->getCHeuresComplFcMajorees()
            + $this->getCHeuresComplReferentiel();
    }



    /**
     * @return array
     */
    public function toArray(): array
    {
        $hydrator = new FormuleTestIntervenantHydrator();

        return $hydrator->extract($this);
    }



    /**
     * @param array $data
     *
     * @return FormuleTestIntervenant
     */
    public function fromArray(array $data): FormuleTestIntervenant
    {
        $hydrator = new FormuleTestIntervenantHydrator();
        $hydrator->hydrate($data, $this);

        return $this;
    }



    /**
     * @return string
     */
    public function getDebugInfo()
    {
        $data = ['lines' => [], 'cols' => [], 'cells' => [], 'inds' => []];
        $calcs = [];

        $a = explode('[', $this->debugInfo);
        foreach ($a as $d) {
            $d = explode('|', $d);
            switch ($d[0]) {
                case 'cell':
                    $c = $d[1];
                    $l = (int)$d[2];
                    $val = (float)$d[3];

                    if ($l > 0) {
                        $data['cells'][$c][$l] = $val;
                        $data['lines'][$l] = $l;
                        $data['cols'][$c] = $c;
                    } else {
                        $data['inds'][$c] = $val;
                    }

                    break;
                case 'calc':
                    $fnc = $d[1];
                    $c = $d[2];
                    $res = $d[3];
                    $data['cells'][$c][$fnc] = $res;
                    $calcs[$fnc] = $fnc;
                    $data['cols'][$c] = $c;
                    break;
            }
        }

        sort($data['lines']);
        sort($calcs);
        $data['lines'] = array_merge($data['lines'], $calcs);
        usort($data['cols'], function ($a, $b) {
            $diffLen = strlen($a) - strlen($b);
            if ($diffLen) return $diffLen;

            return $a > $b ? 1 : 0;
        });

        return $data;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}
