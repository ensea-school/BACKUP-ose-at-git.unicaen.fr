<?php

namespace OSETest;

use Application\Entity\Db\Periode;
use Application\Entity\Db\Service;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\VolumeHoraire;
use Application\Entity\VolumeHoraireListe;
use Application\Service\Traits\MotifNonPaiementServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Zend\Code\Reflection\MethodReflection;

class VolumeHoraireListeTest
{
    use MotifNonPaiementServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use SourceServiceAwareTrait;
    use ServiceServiceAwareTrait;

    const DT_FORMAT = 'd/m/Y H:i';

    /**
     * @var Service
     */
    private $service;

    /**
     * @var array
     */
    private $scenario;

    /**
     * @var TypeVolumeHoraire
     */
    private $typeVolumeHoraire;

    /**
     * @var Periode
     */
    private $periode;

    /**
     * @var TypeIntervention
     */
    private $typeIntervention;

    /**
     * @var VolumeHoraireListe
     */
    private $volumeHoraireListe;



    /**
     * @return Service
     */
    public function getService(): Service
    {
        return $this->service;
    }



    /**
     * @param Service $service
     *
     * @return VolumeHoraireListeTest
     */
    public function setService(Service $service): VolumeHoraireListeTest
    {
        $this->service = $service;

        return $this;
    }



    /**
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire(): TypeVolumeHoraire
    {
        if (!$this->typeVolumeHoraire) {
            $this->typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        }

        return $this->typeVolumeHoraire;
    }



    /**
     * @return Periode
     */
    public function getPeriode(): Periode
    {
        if (!$this->periode) {
            $this->periode = $this->getServicePeriode()->getSemestre1();
        }

        return $this->periode;
    }



    /**
     * @return TypeIntervention
     */
    public function getTypeIntervention(): TypeIntervention
    {
        if (!$this->typeIntervention) {
            $this->typeIntervention = $this->getServiceTypeIntervention()->getByCode('CM');
        }

        return $this->typeIntervention;
    }



    /**
     * @return VolumeHoraireListe
     */
    public function getVolumeHoraireListe(): VolumeHoraireListe
    {
        return $this->volumeHoraireListe;
    }



    /**
     * @return float
     */
    public function getHeures(): float
    {
        return array_sum($this->heuresAttendues);
    }



    /**
     * @param array|float $vhData
     * @param integer     $id
     *
     * @return VolumeHoraire
     */
    protected function dataToVolumeHoraire($vhData, $id)
    {
        $volumeHoraire = new VolumeHoraire();
        $volumeHoraire->setId($id);

        if (is_array($vhData)) {
            if (isset($vhData['horaireDebut']) && $vhData['horaireDebut']) {
                $volumeHoraire->setHoraireDebut(\DateTime::createFromFormat(self::DT_FORMAT, $vhData['horaireDebut']));
            }
            if (isset($vhData['motifNonPaiement']) && $vhData['motifNonPaiement']) {
                if (is_int($vhData['motifNonPaiement'])){
                    $motifNonPaiement = $this->getServiceMotifNonPaiement()->get($vhData['motifNonPaiement']);
                }else{
                    $motifNonPaiement = $this->getServiceMotifNonPaiement()->getRepo()->findOneBy(['libelleCourt' => $vhData['motifNonPaiement']]);
                }
                $volumeHoraire->setMotifNonPaiement($motifNonPaiement);
            }
            if (isset($vhData['histoCreation']) && $vhData['HistoCreation']) {
                $volumeHoraire->setHistoCreation(\DateTime::createFromFormat(self::DT_FORMAT, $vhData['HistoCreation']));
            }
            if (isset($vhData['heures'])) {
                $volumeHoraire->setHeures((float)$vhData['heures']);
            }
            if (isset($vhData['valide'])) {
                $volumeHoraire->setAutoValidation($vhData['valide']);
            }
            if (isset($vhData['source'])) {
                if (is_int($vhData['source'])){
                    $source = $this->getServiceSource()->get($vhData['source']);
                }else{
                    $source = $this->getServiceSource()->getByCode($vhData['source']);
                }
                $volumeHoraire->setSource($source);
            }
            if (isset($vhData['removed'])) {
                $volumeHoraire->setRemove($vhData['removed']);
            }
        } else {
            $volumeHoraire->setHeures((float)$vhData);
        }

        return $volumeHoraire;
    }



    private function normalizedData($vhData, $autoDeleteDefaults=false)
    {
        $nd = [
            'horaireDebut'     => null,
            'motifNonPaiement' => null,
            'heures'           => null,
            'removed'          => null,
            'valide'           => null,
            'source'           => null,
        ];

        if (is_array($vhData)) {
            if (isset($vhData['horaireDebut'])) {
                $nd['horaireDebut'] = $vhData['horaireDebut'];
            }
            if (isset($vhData['motifNonPaiement'])) {
                if (is_int($vhData['motifNonPaiement'])) {
                    $vhData['motifNonPaiement'] = $this->getServiceMotifNonPaiement()->get($vhData['motifNonPaiement'])->getLibelleCourt();
                }
                $nd['motifNonPaiement'] = $vhData['motifNonPaiement'];
            }
            if (isset($vhData['heures'])) {
                $nd['heures'] = (float)$vhData['heures'];
            }
            if (isset($vhData['removed'])) {
                $nd['removed'] = (bool)$vhData['removed'];
            }
            if (isset($vhData['valide'])) {
                $nd['valide'] = (bool)$vhData['valide'];
            }
            if (isset($vhData['source'])) {
                $nd['source'] = is_int($vhData['source']) ? $this->getServiceSource()->get($vhData['source'])->getLibelle() : $vhData['source'];
            }
        } elseif ($vhData instanceof VolumeHoraire) {
            if ($vhData->getHoraireDebut()) {
                $nd['horaireDebut'] = $vhData->getHoraireDebut()->format(self::DT_FORMAT);
            }
            if ($vhData->getMotifNonPaiement()) {
                $nd['motifNonPaiement'] = $vhData->getMotifNonPaiement()->getLibelleCourt();
            }
            $nd['heures']  = $vhData->getHeures();
            $nd['removed'] = $vhData->getRemove() || (!$vhData->estNonHistorise());
            $nd['valide']  = $vhData->isValide();
            $nd['source']  = $vhData->getSource() ? $vhData->getSource()->getLibelle() : null;
        } elseif ($vhData) {
            $nd['heures'] = (float)$vhData;
        }

        if ($autoDeleteDefaults){
            if ($nd['horaireDebut'] === null) unset($nd['horaireDebut']);
            if ($nd['motifNonPaiement'] === null) unset($nd['motifNonPaiement']);
            if ($nd['heures'] === null) unset($nd['heures']);
            if ($nd['removed'] === null) unset($nd['removed']);
            if ($nd['removed'] === false) unset($nd['removed']);
            if ($nd['valide'] === null) unset($nd['valide']);
            if ($nd['valide'] === false) unset($nd['valide']);
            if ($nd['source'] === null) unset($nd['source']);
            if ($nd['source'] === 'OSE') unset($nd['source']);
        }

        return $nd;
    }



    protected function addVolumeHoraire(VolumeHoraire $volumeHoraire)
    {
        if (!$volumeHoraire->getId() && $volumeHoraire->getId() !== 0) {
            throw new \Exception('Un ID doit être fourni pour chaque volume horaire');
        }
        if (!$volumeHoraire->getTypeIntervention()) {
            $volumeHoraire->setTypeIntervention($this->getTypeIntervention());
        }
        if (!$volumeHoraire->getPeriode()) {
            $volumeHoraire->setPeriode($this->getPeriode());
        }
        if (!$volumeHoraire->getTypeVolumeHoraire()) {
            $volumeHoraire->setTypeVolumeHoraire($this->getTypeVolumeHoraire());
        }
        $this->service->addVolumeHoraire($volumeHoraire);
        $volumeHoraire->setService($this->service);

        return $this;
    }



    /**
     * @return array|null
     */
    public function getScenario()
    {
        return $this->scenario;
    }



    /**
     * @param array $scenario
     *
     * @return VolumeHoraireListeTest
     */
    public function setScenario(array $scenario): VolumeHoraireListeTest
    {
        $this->scenario = $scenario;
        $this->applyScenario($scenario);

        return $this;
    }



    public function createScenarioFromService($serviceId, $typeVolumeHoraireCode, $periodeCode, $typeInterventionCode)
    {
        $service           = $this->getServiceService()->get($serviceId);
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);
        $periode           = $this->getServicePeriode()->getByCode($periodeCode);
        $typeIntervention  = $this->getServiceTypeIntervention()->getByCode($typeInterventionCode);

        $vhl = new VolumeHoraireListe($service);
        $vhl->setTypeVolumeHoraire($typeVolumeHoraire);
        $vhl->setPeriode($periode);
        $vhl->setTypeIntervention($typeIntervention);

        $volumesHoraires = [];
        $vhs = $vhl->getVolumeHoraires();
        foreach( $vhs as $volumeHoraire){
            $volumesHoraires[$volumeHoraire->getId()] = $this->normalizedData($volumeHoraire, true);
        }
        $scenario = [
            'input' => $volumesHoraires,
            'output' => [],
            'actions' => [],
        ];

        return $scenario;
    }



    private function applyScenario(array $scenario)
    {
        $this->service = new Service;
        if (isset($scenario['input'])) {
            if (is_array($scenario['input'])) {
                foreach ($scenario['input'] as $id => $vhData) {
                    $volumeHoraire = $this->dataToVolumeHoraire($vhData, $id);
                    $this->addVolumeHoraire($volumeHoraire);
                }
            } elseif ($scenario['input'] instanceof VolumeHoraire) {
                $this->addVolumeHoraire($scenario['input']);
            }
        }
    }



    public function calc(array $scenario = null)
    {
        if ($scenario) {
            $this->setScenario($scenario);
        }

        if (!$this->scenario) {
            throw new \Exception('Scénario non fourni');
        }

        $this->volumeHoraireListe = new VolumeHoraireListe($this->service);
        $this->volumeHoraireListe->setTypeVolumeHoraire($this->getTypeVolumeHoraire());
        $this->volumeHoraireListe->setPeriode($this->getPeriode());
        $this->volumeHoraireListe->setTypeIntervention($this->getTypeIntervention());

        if (isset($this->scenario['actions']) && is_array($this->scenario['actions'])) {
            foreach ($this->scenario['actions'] as $action) {
                $method = $action[0];
                unset($action[0]);
                $args = array_values($action);
                $this->callVhlMethod($method, $args);
            }
        }

        /* on Ajoute des ID aux nouveaux volumes horaires ! */
        /** @var VolumeHoraire[] $vhs */
        $vhs = $this->service->getVolumeHoraire();
        $nid = 1;
        foreach ($vhs as $volumeHoraire) {
            if (!$volumeHoraire->getId()) {
                $volumeHoraire->setId('n' . $nid);
                $nid++;
            }
        }

        return $this;
    }



    private function callVhlMethod($method, array $args)
    {
        switch($method){
            case 'setHeuresWithMotifNonPaiement':
                if (is_string($args[1])) {
                    $args[1] = $this->getServiceMotifNonPaiement()->getRepo()->findOneBy(['libelleCourt' => $args[1]]);
                }
                if (is_string($args[2])) {
                    $args[2] = $this->getServiceMotifNonPaiement()->getRepo()->findOneBy(['libelleCourt' => $args[2]]);
                }
            break;
        }

        call_user_func_array([$this->volumeHoraireListe, $method], $args);
    }



    public function getResultArray()
    {
        $ids = [];

        /* Recherche des IDS */
        if (isset($this->scenario['input']) && is_array($this->scenario['input'])) {
            $ids = array_keys($this->scenario['input']);
        }
        if (isset($this->scenario['output']) && is_array($this->scenario['output'])) {
            foreach ($this->scenario['output'] as $id => $null) {
                if (!in_array($id, $ids)) {
                    $ids[] = $id;
                }
            }
        }
        /** @var VolumeHoraire[] $vhs */
        $vhs = $this->service->getVolumeHoraire();
        foreach ($vhs as $volumeHoraire) {
            if (!in_array($volumeHoraire->getId(), $ids)) {
                $ids[] = $volumeHoraire->getId();
            }
        }


        /* Confection du tableau de sortie */
        $res = [];
        foreach ($ids as $id) {
            $input  = $this->normalizedData(isset($this->scenario['input'][$id]) ? $this->scenario['input'][$id] : null);
            $output = $this->normalizedData(isset($this->scenario['output'][$id]) ? $this->scenario['output'][$id] : null);
            $calc   = $this->normalizedData(null);

            foreach ($input as $k => $v) {
                if (null === $output[$k]) {
                    $output[$k] = $v;
                }
            }

            foreach ($vhs as $vh) {
                if ($vh->getId() == $id) {
                    $calc = $this->normalizedData($vh);
                }
            }

            $res[$id] = compact('input', 'output', 'calc');
        }

        return $res;
    }



    public function displayResult()
    {
        $r = $this->getResultArray();

        $heuresInput  = 0;
        $heuresOutput = 0;
        $heuresCalc   = 0;

        foreach ($r as $vhData) {
            $heuresInput  += $vhData['input']['removed'] ? 0 : (float)$vhData['input']['heures'];
            $heuresOutput += $vhData['output']['removed'] ? 0 : (float)$vhData['output']['heures'];
            $heuresCalc   += $vhData['calc']['removed'] ? 0 : (float)$vhData['calc']['heures'];
        }

        ?>
        <h3>Heures : (Sources=<?= $heuresInput ?>, Attendues=<?= $heuresOutput ?>, Calculées=<?= $heuresCalc ?>)</h3>
        <?php foreach ($this->scenario['actions'] as $action): ?>
        <div>
            <b><?= $action[0] ?></b>
            <span style="color:gray">(</span><?php unset($action[0]);
            echo implode('<span style="color:gray">, </span>', $action) ?><span style="color:gray">);</span>
        </div>
    <?php endforeach; ?>
        <table class="table table-bordered table-condensed">
            <thead>
            <tr>
                <th>Id</th>
                <th>&nbsp;</th>
                <th>Horaire début</th>
                <th>Non paiement</th>
                <th>Validé</th>
                <th>Source</th>
                <th>Heures</th>
                <th>Supprimé</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($r as $id => $vhData): ?>
                <tr>
                    <th><?= $id ?></th>
                    <th>Source<br/>Attendu<br/>Calculé</th>
                    <?= $this->displayCell($vhData, 'horaireDebut') ?>
                    <?= $this->displayCell($vhData, 'motifNonPaiement') ?>
                    <?= $this->displayCell($vhData, 'valide') ?>
                    <?= $this->displayCell($vhData, 'source') ?>
                    <?= $this->displayCell($vhData, 'heures') ?>
                    <?= $this->displayCell($vhData, 'removed') ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }



    private function displayCell(array $vhData, $column)
    {
        $input  = $vhData['input'][$column];
        $output = $vhData['output'][$column];
        $calc   = $vhData['calc'][$column];

        $style = '';
        if ($output != $calc) {
            $style = ' style="background-color:#FFD3C1"';
        } elseif ($output != $input) {
            $style = ' style="background-color:#CAFFD1"';
        }

        return '<td' . $style . '>'
            . $this->formatDisplayCell($input, $column) . '<br />'
            . $this->formatDisplayCell($output, $column) . '<br />'
            . $this->formatDisplayCell($calc, $column) . '</td>';
    }



    private function formatDisplayCell($data, $column)
    {
        switch ($column) {
            case 'removed':
                return $data ? 'Supprimé' : '';
            break;
            case 'valide':
                return $data ? 'Validé' : '';
            break;
            case 'heures':
                return (float)$data;
            break;
        }

        return $data;
    }
}