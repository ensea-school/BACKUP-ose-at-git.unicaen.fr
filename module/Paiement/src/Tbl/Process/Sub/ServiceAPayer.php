<?php

namespace Paiement\Tbl\Process\Sub;

class ServiceAPayer
{
    public ?string $key = null;
    public ?int $annee = null;
    public ?int $typeIntervenant = null;
    public ?int $intervenant = null;
    public ?int $structure = null;
    public ?int $service = null;
    public ?int $mission = null;
    public ?int $serviceReferentiel = null;
    public ?int $typeHeures = null;
    public ?int $defDomaineFonctionnel = null;
    public ?int $defCentreCout = null;
    public ?float $tauxCongesPayes = null;
    public ?int $heures = 0;

    /** @var array|LigneAPayer[] */
    public array $lignesAPayer = [];

    /** @var array|MiseEnPaiement[] */
    public array $misesEnPaiement = [];



    public function fromBdd(array $data)
    {
        $this->key = $data['key'];
        $this->annee = (int)$data['annee_id'] ?: null;
        $this->typeIntervenant = (int)@$data['type_intervenant_id'] ?: null;
        $this->intervenant = (int)@$data['intervenant_id'] ?: null;
        $this->structure = (int)@$data['structure_id'] ?: null;
        $this->mission = (int)@$data['mission_id'] ?: null;
        $this->service = (int)@$data['service_id'] ?: null;
        $this->serviceReferentiel = (int)@$data['service_referentiel_id'] ?: null;
        $this->typeHeures = (int)@$data['type_heures_id'] ?: null;
        $this->defDomaineFonctionnel = (int)@$data['def_domaine_fonctionnel_id'] ?: null;
        $this->defCentreCout = (int)@$data['def_centre_cout_id'] ?: null;
        $this->tauxCongesPayes = (float)$data['taux_conges_payes'] ?: null;
        $this->heures = (int)round((float)$data['heures'] * 100) ?: null;
        $this->lignesAPayer = [];
        $this->misesEnPaiement = [];
    }



    public function fromArray(array $data)
    {
        foreach ($data as $k => $v) {
            if (!in_array($k, ['lignesAPayer', 'misesEnPaiement'])) {
                $this->$k = $v;
            }
        }
        if (isset($data['lignesAPayer'])) {
            foreach ($data['lignesAPayer'] as $did => $dlap) {
                if (!isset($dmep['id'])){
                    $dlap['id'] = $did;
                }
                $lap = new LigneAPayer();
                $lap->fromArray($dlap);
                $this->lignesAPayer[$did] = $lap;
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



    public function toArray()
    {
        $vars = get_object_vars($this);

        foreach ($vars['lignesAPayer'] as $id => $lap) {
            $vars['lignesAPayer'][$id] = $lap->toArray();
        }
        foreach ($vars['misesEnPaiement'] as $id => $mep) {
            $vars['misesEnPaiement'][$id] = $mep->toArray();
        }

        if (empty($vars['misesEnPaiement'])) {
            unset($vars['misesEnPaiement']);
        }

        return $vars;
    }
}