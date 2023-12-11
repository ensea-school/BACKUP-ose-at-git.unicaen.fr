<?php

namespace Paiement\Tbl\Process\Sub;

use Paiement\Service\TauxRemuServiceAwareTrait;

class LigneAPayer
{
    public int $id;
    public ?int $volumeHoraireId = null;
    public ?int $periode = null;
    public int $tauxRemu;
    public float $tauxValeur;
    public float $pourcAA;
    public int $heures;
    public int $heuresAA;
    public int $heuresAC;

    /** @var array|MiseEnPaiement[] */
    public array $misesEnPaiement = [];



    public function fromBdd(array $data)
    {
        $this->id = (int)$data['A_PAYER_ID'];
        $this->tauxRemu = (int)$data['TAUX_REMU_ID'];
        $this->periode = $data['PERIODE_ENS_ID'] ? (int)$data['PERIODE_ENS_ID'] : null;
        $this->heures = (int)round((float)$data['LAP_HEURES'] * 100);
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
                $mep = new MiseEnPaiement();
                $mep->fromArray($dmep);
                $this->misesEnPaiement[$mid] = $mep;
            }
        }
    }



    public function toArray(): array
    {
        $vars = get_object_vars($this);

        foreach ($vars['misesEnPaiement'] as $id => $mep) {
            $vars['misesEnPaiement'][$id] = $mep->toArray();
        }

        if (empty($vars['misesEnPaiement'])){
            unset($vars['misesEnPaiement']);
        }

        return $vars;
    }

}