<?php

namespace Paiement\Tbl\Process\Sub;

class MiseEnPaiement
{
    public int $id;
    public int $heuresAA = 0;
    public int $heuresAC = 0;
    public ?string $date = null;
    public ?int $periodePaiement = null;
    public ?int $centreCout = null;
    public ?int $domaineFonctionnel = null;



    public function fromBdd(array $data)
    {
        $this->id = (int)$data['mise_en_paiement_id'];
        $this->heuresAA = (int)round((float)$data['mep_heures'] * 100);
        $this->date = $data['date_mise_en_paiement'] ? substr($data['date_mise_en_paiement'], 0, 10) : null;
        $this->periodePaiement = (int)$data['periode_paiement_id'] ?: null;
        $this->centreCout = (int)$data['mep_centre_cout_id'];
        $this->domaineFonctionnel = (int)$data['mep_domaine_fonctionnel_id'];
    }



    public function fromArray(array $data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }



    public function toArray(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }



    public function newFrom(): MiseEnPaiement
    {
        $nmep = new MiseEnPaiement();
        $nmep->id = $this->id;
        $nmep->domaineFonctionnel = $this->domaineFonctionnel;
        $nmep->centreCout = $this->centreCout;
        $nmep->periodePaiement = $this->periodePaiement;
        $nmep->date = $this->date;

        return $nmep;
    }

}