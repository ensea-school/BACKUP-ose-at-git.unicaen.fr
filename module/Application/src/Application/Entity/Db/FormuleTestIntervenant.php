<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\EtatVolumeHoraireAwareTrait;
use Application\Entity\Db\Traits\FormuleAwareTrait;
use Application\Entity\Db\Traits\FormuleTestStructureAwareTrait;
use Application\Entity\Db\Traits\TypeIntervenantAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Application\Hydrator\FormuleTestIntervenantHydrator;

class FormuleTestIntervenant
{
    use FormuleAwareTrait;
    use AnneeAwareTrait;
    use TypeIntervenantAwareTrait;
    use FormuleTestStructureAwareTrait;
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
     * @var float
     */
    private $heuresDecharge = 0;

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
     * @return float
     */
    public function getHeuresDecharge()
    {
        return $this->heuresDecharge;
    }



    /**
     * @param float $heuresDecharge
     */
    public function setHeuresDecharge(float $heuresDecharge): FormuleTestIntervenant
    {
        $this->heuresDecharge = $heuresDecharge;

        return $this;
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
     * @return string
     */
    public function getParam1()
    {
        return $this->param1;
    }



    /**
     * @param string $param1
     */
    public function setParam1(string $param1): FormuleTestIntervenant
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
    public function setParam2(string $param2): FormuleTestIntervenant
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
    public function setParam3(string $param3): FormuleTestIntervenant
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
    public function setParam4(string $param4): FormuleTestIntervenant
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
    public function setParam5(string $param5): FormuleTestIntervenant
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
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}
