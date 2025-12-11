<?php

namespace Paiement\Tbl\Process\Sub;

class LigneAPayer
{
    public int $id;
    private ?string $key = null;
    public ?int $volumeHoraireId = null;
    public ?int $periode = null;
    public ?int $tauxRemu = null;
    public ?float $tauxValeur = null;
    public ?float $pourcAA = null;
    public int $heuresAA = 0;
    public int $heuresAC = 0;
    public ?int $intBuffer1 = null;
    public ?int $intBuffer2 = null;


    /** @var array|MiseEnPaiement[] */
    public array $misesEnPaiement = [];



    public function fromBdd(array $data)
    {
        $this->id = (int)$data['a_payer_id'];
        $this->tauxRemu = (int)$data['taux_remu_id'];
        $this->periode = $data['periode_ens_id'] ? (int)$data['periode_ens_id'] : null;
        $this->heuresAA = (int)round((float)$data['heures'] * 100);
        $this->volumeHoraireId = $data['volume_horaire_id'] ? (int)$data['volume_horaire_id'] : null;
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



    public function key(): string
    {
        if (empty($this->key)){
            $this->key = $this->tauxRemu . '-' . $this->tauxValeur . '-' . ($this->periode ?? 0);
        }

        return $this->key;
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