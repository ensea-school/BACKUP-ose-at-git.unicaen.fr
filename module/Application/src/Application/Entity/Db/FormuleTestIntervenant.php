<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\EtatVolumeHoraireAwareTrait;
use Application\Entity\Db\Traits\FormuleAwareTrait;
use Application\Entity\Db\Traits\FormuleTestStructureAwareTrait;
use Application\Entity\Db\Traits\TypeIntervenantAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;

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
    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;
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
    public function setHeuresDecharge(float $heuresDecharge)
    {
        $this->heuresDecharge = $heuresDecharge;
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
    public function setHeuresServiceStatutaire(float $heuresServiceStatutaire)
    {
        $this->heuresServiceStatutaire = $heuresServiceStatutaire;
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
    public function setHeuresServiceModifie(float $heuresServiceModifie)
    {
        $this->heuresServiceModifie = $heuresServiceModifie;
    }



    /**
     * @return bool
     */
    public function isDepassementServiceDuSansHC(): bool
    {
        return $this->depassementServiceDuSansHC;
    }



    /**
     * @param bool $depassementServiceDuSansHC
     */
    public function setDepassementServiceDuSansHC(bool $depassementServiceDuSansHC)
    {
        $this->depassementServiceDuSansHC = $depassementServiceDuSansHC;
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
    public function setParam1(string $param1)
    {
        $this->param1 = $param1;
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
    public function setParam2(string $param2)
    {
        $this->param2 = $param2;
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
    public function setParam3(string $param3)
    {
        $this->param3 = $param3;
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
    public function setParam4(string $param4)
    {
        $this->param4 = $param4;
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
    public function setParam5(string $param5)
    {
        $this->param5 = $param5;
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
    public function setAServiceDu(float $aServiceDu)
    {
        $this->aServiceDu = $aServiceDu;
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
    public function setCServiceDu(float $cServiceDu)
    {
        $this->cServiceDu = $cServiceDu;
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
    public function addVolumeHoraireTest(FormuleTestVolumeHoraire $volumeHoraireTest)
    {
        $this->volumeHoraireTest[] = $volumeHoraireTest;

        return $this;
    }



    /**
     * Remove volumeHoraireTest
     *
     * @param FormuleTestVolumeHoraire $volumeHoraireTest
     */
    public function removeVolumeHoraireTest(FormuleTestVolumeHoraire $volumeHoraireTest)
    {
        $this->volumeHoraireTest->removeElement($volumeHoraireTest);
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
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}
