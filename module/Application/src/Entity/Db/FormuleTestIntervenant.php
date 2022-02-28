<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\EtatVolumeHoraireAwareTrait;
use Application\Entity\Db\Traits\FormuleAwareTrait;
use Intervenant\Entity\Db\TypeIntervenantAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Application\Hydrator\FormuleTestIntervenantHydrator;

class FormuleTestIntervenant
{
    use FormuleAwareTrait;
    use AnneeAwareTrait;
    use TypeIntervenantAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string|null
     */
    private $structureCode;

    /**
     * @var float
     */
    private $heuresServiceStatutaire = 0;

    /**
     * @var float
     */
    private $heuresServiceModifie = 0;

    /**
     * @var bool
     */
    private $depassementServiceDuSansHC = false;

    /**
     * @var float
     */
    private $tauxCmServiceDu = 1.5;

    /**
     * @var float
     */
    private $tauxCmServiceCompl = 1.5;

    /**
     * @var float
     */
    private $tauxTpServiceDu = 1;

    /**
     * @var float
     */
    private $tauxTpServiceCompl = 2 / 3;

    /**
     * @var float
     */
    private $tauxAutreServiceDu = 1;

    /**
     * @var float
     */
    private $tauxAutreServiceCompl = 1;

    /**
     * @var string
     */
    private $param1;

    /**
     * @var string
     */
    private $param2;

    /**
     * @var string
     */
    private $param3;

    /**
     * @var string
     */
    private $param4;

    /**
     * @var string
     */
    private $param5;

    /**
     * @var float
     */
    private $aServiceDu = 0;

    /**
     * @var float
     */
    private $cServiceDu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $volumeHoraireTest;

    /**
     * @var string
     */
    private $debugInfo;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     */
    public function setLibelle(string $libelle): FormuleTestIntervenant
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getStructureCode(): ?string
    {
        return $this->structureCode;
    }



    /**
     * @param string|null $structureCode
     *
     * @return FormuleTestIntervenant
     */
    public function setStructureCode(?string $structureCode): FormuleTestIntervenant
    {
        $this->structureCode = $structureCode;

        return $this;
    }



    /**
     * @return string[]
     */
    public function getStructures(): array
    {
        $structures = [];
        if ($this->getStructureCode()) {
            $structures[$this->getStructureCode()] = $this->getStructureCode();
        }
        foreach ($this->getVolumeHoraireTest() as $vht) {
            if ($vht->getStructureCode()) {
                $structures[$vht->getStructureCode()] = $vht->getStructureCode();
            }
        }
        asort($structures);

        return $structures;
    }



    /**
     * @return float
     */
    public function getHeuresServiceStatutaire()
    {
        return $this->heuresServiceStatutaire;
    }



    /**
     * @param float $heuresServiceStatutaire
     */
    public function setHeuresServiceStatutaire(float $heuresServiceStatutaire): FormuleTestIntervenant
    {
        $this->heuresServiceStatutaire = $heuresServiceStatutaire;

        return $this;
    }



    /**
     * @return float
     */
    public function getHeuresServiceModifie()
    {
        return $this->heuresServiceModifie;
    }



    /**
     * @param float $heuresServiceModifie
     */
    public function setHeuresServiceModifie(float $heuresServiceModifie): FormuleTestIntervenant
    {
        $this->heuresServiceModifie = $heuresServiceModifie;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDepassementServiceDuSansHC(): bool
    {
        return $this->depassementServiceDuSansHC;
    }



    /**
     * @param bool $depassementServiceDuSansHC
     */
    public function setDepassementServiceDuSansHC(bool $depassementServiceDuSansHC): FormuleTestIntervenant
    {
        $this->depassementServiceDuSansHC = $depassementServiceDuSansHC;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxCmServiceDu(): float
    {
        return $this->tauxCmServiceDu;
    }



    /**
     * @param float $tauxCmServiceDu
     *
     * @return FormuleTestIntervenant
     */
    public function setTauxCmServiceDu(float $tauxCmServiceDu): FormuleTestIntervenant
    {
        $this->tauxCmServiceDu = $tauxCmServiceDu;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxCmServiceCompl(): float
    {
        return $this->tauxCmServiceCompl;
    }



    /**
     * @param float $tauxCmServiceCompl
     *
     * @return FormuleTestIntervenant
     */
    public function setTauxCmServiceCompl(float $tauxCmServiceCompl): FormuleTestIntervenant
    {
        $this->tauxCmServiceCompl = $tauxCmServiceCompl;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxTpServiceDu(): float
    {
        return $this->tauxTpServiceDu;
    }



    /**
     * @param float $tauxTpServiceDu
     *
     * @return FormuleTestIntervenant
     */
    public function setTauxTpServiceDu(float $tauxTpServiceDu): FormuleTestIntervenant
    {
        $this->tauxTpServiceDu = $tauxTpServiceDu;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxTpServiceCompl(): float
    {
        return $this->tauxTpServiceCompl;
    }



    /**
     * @param float $tauxTpServiceCompl
     *
     * @return FormuleTestIntervenant
     */
    public function setTauxTpServiceCompl(float $tauxTpServiceCompl): FormuleTestIntervenant
    {
        $this->tauxTpServiceCompl = $tauxTpServiceCompl;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxAutreServiceDu(): float
    {
        return $this->tauxAutreServiceDu;
    }



    /**
     * @param float $tauxAutreServiceDu
     *
     * @return FormuleTestIntervenant
     */
    public function setTauxAutreServiceDu(float $tauxAutreServiceDu): FormuleTestIntervenant
    {
        $this->tauxAutreServiceDu = $tauxAutreServiceDu;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxAutreServiceCompl(): float
    {
        return $this->tauxAutreServiceCompl;
    }



    /**
     * @param float $tauxAutreServiceCompl
     *
     * @return FormuleTestIntervenant
     */
    public function setTauxAutreServiceCompl(float $tauxAutreServiceCompl): FormuleTestIntervenant
    {
        $this->tauxAutreServiceCompl = $tauxAutreServiceCompl;

        return $this;
    }



    /**
     * @return string
     */
    public function getParam1()
    {
        return $this->param1;
    }



    /**
     * @param string $param1
     */
    public function setParam1($param1): FormuleTestIntervenant
    {
        $this->param1 = $param1;

        return $this;
    }



    /**
     * @return string
     */
    public function getParam2()
    {
        return $this->param2;
    }



    /**
     * @param string $param2
     */
    public function setParam2($param2): FormuleTestIntervenant
    {
        $this->param2 = $param2;

        return $this;
    }



    /**
     * @return string
     */
    public function getParam3()
    {
        return $this->param3;
    }



    /**
     * @param string $param3
     */
    public function setParam3($param3): FormuleTestIntervenant
    {
        $this->param3 = $param3;

        return $this;
    }



    /**
     * @return string
     */
    public function getParam4()
    {
        return $this->param4;
    }



    /**
     * @param string $param4
     */
    public function setParam4($param4): FormuleTestIntervenant
    {
        $this->param4 = $param4;

        return $this;
    }



    /**
     * @return string
     */
    public function getParam5()
    {
        return $this->param5;
    }



    /**
     * @param string $param5
     */
    public function setParam5($param5): FormuleTestIntervenant
    {
        $this->param5 = $param5;

        return $this;
    }



    /**
     * @return float
     */
    public function getAServiceDu()
    {
        return $this->aServiceDu;
    }



    /**
     * @param float $aServiceDu
     */
    public function setAServiceDu(float $aServiceDu): FormuleTestIntervenant
    {
        $this->aServiceDu = $aServiceDu;

        return $this;
    }



    /**
     * @return float
     */
    public function getCServiceDu()
    {
        return $this->cServiceDu;
    }



    /**
     * @param float $cServiceDu
     */
    public function setCServiceDu(float $cServiceDu): FormuleTestIntervenant
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
        $data  = ['lines' => [], 'cols' => [], 'cells' => [], 'inds' => []];
        $calcs = [];

        $a = explode('[', $this->debugInfo);
        foreach ($a as $d) {
            $d = explode('|', $d);
            switch ($d[0]) {
                case 'cell':
                    $c   = $d[1];
                    $l   = (int)$d[2];
                    $val = (float)$d[3];

                    if ($l > 0) {
                        $data['cells'][$c][$l] = $val;
                        $data['lines'][$l]     = $l;
                        $data['cols'][$c]      = $c;
                    } else {
                        $data['inds'][$c] = $val;
                    }

                break;
                case 'calc':
                    $fnc                     = $d[1];
                    $c                       = $d[2];
                    $res                     = $d[3];
                    $data['cells'][$c][$fnc] = $res;
                    $calcs[$fnc]             = $fnc;
                    $data['cols'][$c]        = $c;
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
