<?php

namespace Formule\Model;

use Formule\Entity\Db\Formule;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;

class AbstractFormuleCalcul
{
    protected Formule $formule;
    protected FormuleIntervenant $intervenant;

    protected int $ligne = 0;



    public function __construct(Formule $formule, FormuleIntervenant $intervenant)
    {
        $this->formule = $formule;
        $this->intervenant = $intervenant;
    }



    protected function setLigne(int $ligne)
    {
        $this->ligne = $ligne;
    }



    protected function max(string $cellName): float
    {
        $result = null;
        foreach ($this->intervenant->getVolumesHoraires() as $l => $vh) {
            $cr = $this->$cellName($l);
            if ($result === null || $cr > $result) {
                $result = $cr;
            }
        }

        return $result;
    }



    protected function somme(string $cellName): float
    {
        $result = 0;
        foreach ($this->intervenant->getVolumesHoraires() as $l => $vh) {
            $cr = $this->$cellName($l);
            $result += $cr;
        }

        return $result;
    }



    protected function c(string $name, int $l)
    {
        $cname = 'c_' . $name;
        return $this->$cname($l);
    }



    protected function intervenant(): FormuleIntervenant
    {
        return $this->intervenant;
    }



    protected function volumeHoraire(int $l): FormuleVolumeHoraire
    {
        return $this->intervenant->getVolumesHoraires()->get($l);
    }



    public function calculer()
    {

    }
}