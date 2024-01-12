<?php

namespace Paiement\Tbl\Process\Sub;

class LigneAPayer
{
    public int $id;
    public ?int $volumeHoraireId = null;
    public ?int $periode = null;
    public ?int $tauxRemu = null;
    public ?float $tauxValeur = null;
    public ?float $pourcAA = null;
    public int $heuresAA = 0;
    public int $heuresAC = 0;

    /** @var array|MiseEnPaiement[] */
    public array $misesEnPaiement = [];



    public function fromBdd(array $data)
    {
        $this->id = (int)$data['A_PAYER_ID'];
        $this->tauxRemu = (int)$data['TAUX_REMU_ID'];
        $this->periode = $data['PERIODE_ENS_ID'] ? (int)$data['PERIODE_ENS_ID'] : null;
        $this->heuresAA = (int)round((float)$data['LAP_HEURES'] * 100);
        $this->volumeHoraireId = $data['VOLUME_HORAIRE_ID'] ? (int)$data['VOLUME_HORAIRE_ID'] : null;
    }



    public function fromArray(array $data)
    {
        foreach ($data as $k => $v) {
            if (!in_array($k, ['misesEnPaiement'])) {
                $this->$k = $v;
            }
        }
        if (isset($data['misesEnPaiement'])) {
            foreach ($data['misesEnPaiement'] as $mid => $dmep) {
                if (!isset($dmep['id'])){
                    $dmep['id'] = $mid;
                }
                $mep = new MiseEnPaiement();
                $mep->fromArray($dmep);
                $this->misesEnPaiement[$mid] = $mep;
            }
        }
    }



    public function nonPayeAA(): int
    {
        $res = $this->heuresAA;
        foreach ($this->misesEnPaiement as $mep) {
            $res -= $mep->heuresAA;
        }

        return $res;
    }



    public function nonPayeAC(): int
    {
        $res = $this->heuresAC;
        foreach ($this->misesEnPaiement as $mep) {
            $res -= $mep->heuresAC;
        }

        return $res;
    }



    public function toArray(): array
    {
        $vars = get_object_vars($this);

        foreach ($vars['misesEnPaiement'] as $id => $mep) {
            $vars['misesEnPaiement'][$id] = $mep->toArray();
        }

        if (empty($vars['misesEnPaiement'])) {
            unset($vars['misesEnPaiement']);
        }

        return $vars;
    }

}