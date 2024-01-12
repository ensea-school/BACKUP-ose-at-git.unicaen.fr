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
        $this->id = (int)$data['MISE_EN_PAIEMENT_ID'];
        $this->heuresAA = (int)round((float)$data['MEP_HEURES'] * 100);
        $this->date = $data['DATE_MISE_EN_PAIEMENT'] ? substr($data['DATE_MISE_EN_PAIEMENT'], 0, 10) : null;
        $this->periodePaiement = (int)$data['PERIODE_PAIEMENT_ID'] ?: null;
        $this->centreCout = (int)$data['MEP_CENTRE_COUT_ID'];
        $this->domaineFonctionnel = (int)$data['MEP_DOMAINE_FONCTIONNEL_ID'];
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