<?php

namespace Mission\Entity;

use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use UnicaenVue\Axios\AxiosExtractorInterface;

class MissionSuivi implements AxiosExtractorInterface
{

    /** @var VolumeHoraireMission[] */
    private array $volumesHoraires = [];



    public function guid(): ?string
    {
        if (empty($this->volumesHoraires)) {
            return null;
        } else {
            return $this->getVolumeHoraire()->guid();
        }
    }



    public function hasValides(): bool
    {
        foreach ($this->volumesHoraires as $vh) {
            if ($vh->isValide()) {
                return true;
            }
        }

        return false;
    }



    public function getMission(): ?Mission
    {
        return $this->getVolumeHoraire()->getMission();
    }



    public function setMission(?Mission $mission): MissionSuivi
    {
        if ($this->hasValides() && $mission !== $this->getMission()) {
            throw new \Exception('La mission ne peut pas être modifiée : des heures ont déjà été validées');
        }

        foreach ($this->volumesHoraires as $vh) {
            $vh->setMission($mission);
        }

        return $this;
    }



    public function getHoraireDebut(): ?\DateTime
    {
        return $this->getVolumeHoraire()->getHoraireDebut();
    }



    public function setHoraireDebut(?\DateTime $horaireDebut): MissionSuivi
    {
        if ($this->hasValides() && $horaireDebut !== $this->getHoraireDebut()) {
            throw new \Exception('L\'horaire de début ne peut pas être modifié : des heures ont déjà été validées');
        }

        foreach ($this->volumesHoraires as $vh) {
            $vh->setHoraireDebut($horaireDebut);
        }

        return $this;
    }



    public function getHoraireFin(): ?\DateTime
    {
        return $this->getVolumeHoraire()->getHoraireFin();
    }



    public function setHoraireFin(?\DateTime $horaireFin): MissionSuivi
    {
        if ($this->hasValides() && $horaireFin !== $this->getHoraireFin()) {
            throw new \Exception('L\'horaire de fin ne peut pas être modifié : des heures ont déjà été validées');
        }

        foreach ($this->volumesHoraires as $vh) {
            $vh->setHoraireFin($horaireFin);
        }

        return $this;
    }



    public function getDate(): ?string
    {
        return $this->getHoraireDebut()?->format('Y-m-d');
    }



    public function setDate(?string $dateStr): MissionSuivi
    {
        if ($this->hasValides() && $dateStr !== $this->getDate()) {
            throw new \Exception('La date ne peut pas être modifiée : des heures ont déjà été validées');
        }

        if (!$dateStr) {
            $dateStr = '0000-00-00';
        }
        $date = explode('-', $dateStr);

        $horaireDebut = $this->getHoraireDebut() ?: new \DateTime;
        $horaireFin   = $this->getHoraireFin() ?: new \DateTime;

        $horaireDebut->setDate($date[0], $date[1], $date[2]);
        $horaireFin->setDate($date[0], $date[1], $date[2]);

        $this->setHoraireDebut($horaireDebut);
        $this->setHoraireFin($horaireFin);

        return $this;
    }



    public function getHeureDebut(): ?string
    {
        return $this->getHoraireDebut()?->format('H:i');
    }



    public function setHeureDebut(?string $heureStr): MissionSuivi
    {
        if (!$heureStr) {
            $heureStr = '00:00';
        }
        $heure = explode(':', $heureStr);

        $horaireDebut = $this->getHoraireDebut() ?: new \DateTime();

        $horaireDebut->setTime($heure[0], $heure[1], 0);

        $this->setHoraireDebut($horaireDebut);

        return $this;
    }



    public function getHeureFin(): ?string
    {
        return $this->getHoraireFin()?->format('H:i');
    }



    public function setHeureFin(?string $heureStr): MissionSuivi
    {
        if (!$heureStr) {
            $heureStr = '00:00';
        }
        $heure = explode(':', $heureStr);

        $horaireFin = $this->getHoraireFin() ?: new \DateTime();

        $horaireFin->setTime($heure[0], $heure[1], 0);

        $this->setHoraireFin($horaireFin);

        return $this;
    }



    public function getHeures(): ?float
    {
        $heures = 0;
        foreach ($this->volumesHoraires as $vh) {
            $heures += $vh->getHeures();
        }

        return $heures;
    }



    public function setHeures(?float $heures): MissionSuivi
    {
        $newHeures = $heures - $this->getHeures();

        $vh = $this->getEditableVolumeHoraire();

        $vh->setHeures($newHeures + $vh->getHeures());

        return $this;
    }



    public function getHeuresValidees(): ?float
    {
        $heures = 0;
        foreach ($this->volumesHoraires as $vh) {
            if ($vh->isValide()) {
                $heures += $vh->getHeures();
            }
        }

        return $heures;
    }



    public function isNocturne(): bool
    {
        return $this->getVolumeHoraire()->isNocturne();
    }



    public function setNocturne(bool $nocturne): MissionSuivi
    {
        if ($this->hasValides() && $nocturne !== $this->isNocturne()) {
            throw new \Exception('Le témoin "nocturne" ne peut pas être modifié : des heures ont déjà été validées');
        }

        foreach ($this->volumesHoraires as $vh) {
            $vh->setNocturne($nocturne);
        }

        return $this;
    }



    public function isFormation(): bool
    {
        return $this->getVolumeHoraire()->isFormation();
    }



    public function setFormation(bool $formation): MissionSuivi
    {
        if ($this->hasValides() && $formation !== $this->isFormation()) {
            throw new \Exception('Le témoin "formation" ne peut pas être modifié : des heures ont déjà été validées');
        }

        foreach ($this->volumesHoraires as $vh) {
            $vh->setFormation($formation);
        }

        return $this;
    }



    public function getDescription(): ?string
    {
        return $this->getVolumeHoraire()->getDescription();
    }



    public function setDescription(?string $description): MissionSuivi
    {
        foreach ($this->volumesHoraires as $vh) {
            if (!$vh->isValide()) {
                $vh->setDescription($description);
            }
        }

        return $this;
    }



    public function getVolumesHoraires(): array
    {
        return $this->volumesHoraires;
    }



    private function populateVolumeHoraire(VolumeHoraireMission $volumeHoraireMission)
    {
        $volumeHoraireMission->setMission($this->getMission());
        $volumeHoraireMission->setHoraireDebut($this->getHoraireDebut());
        $volumeHoraireMission->setHoraireFin($this->getHoraireFin());
        $volumeHoraireMission->setNocturne($this->isNocturne());
        $volumeHoraireMission->setFormation($this->isFormation());
        $volumeHoraireMission->setDescription($this->getDescription());
    }



    public function addVolumeHoraire(VolumeHoraireMission $volumeHoraireMission): VolumeHoraireMission
    {
        $guid   = $this->guid();
        $vhGuid = $volumeHoraireMission->guid();

        if ($guid && $vhGuid && $guid !== $vhGuid) {
            throw new \Exception('Le volume horaire n\'est pas compatible avec ce suivi de mission');
        }

        if (null !== $guid && null === $vhGuid) {
            $this->populateVolumeHoraire($volumeHoraireMission);
        }
        $this->volumesHoraires[] = $volumeHoraireMission;

        return $volumeHoraireMission;
    }



    protected function getVolumeHoraire(): VolumeHoraireMission
    {
        $vhm          = null;
        $vhHistoModif = '0000-00-00 00:00:00';
        foreach ($this->volumesHoraires as $vh) {
            $histoModif = ($vh->getHistoModification() ?: new \DateTime())->format('Y-m-d H:i:s');
            if ($histoModif > $vhHistoModif) {
                $vhHistoModif = $histoModif;
                $vhm          = $vh;
            }
        }

        if (null === $vhm) {
            $vhm = $this->addVolumeHoraire(new VolumeHoraireMission());
        }

        return $vhm;
    }



    protected function getEditableVolumeHoraire(): VolumeHoraireMission
    {
        $vhm          = null;
        $vhHistoModif = '0000-00-00 00:00:00';
        foreach ($this->volumesHoraires as $vh) {
            $histoModif = ($vh->getHistoModification() ?: new \DateTime())->format('Y-m-d H:i:s');
            if (!$vh->isValide() && $histoModif > $vhHistoModif) {
                $vhHistoModif = $histoModif;
                $vhm          = $vh;
            }
        }

        if (null === $vhm) {
            $vhm = $this->addVolumeHoraire(new VolumeHoraireMission());
        }

        return $vhm;
    }



    public function axiosDefinition(): array
    {
        return [
            'guid',
            ['mission',['id','libelle']],
            'date',
            'heureDebut',
            'heureFin',
            'heures',
            'nocturne',
            'formation',
            'description',
        ];
    }

}