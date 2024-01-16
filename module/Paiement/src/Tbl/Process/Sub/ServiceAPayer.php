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
    public ?int $referentiel = null;
    public ?int $mission = null;
    public ?int $formuleResService = null;
    public ?int $formuleResServiceRef = null;
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
        $this->key = $data['KEY'];
        $this->annee = (int)$data['ANNEE_ID'] ?: null;
        $this->typeIntervenant = (int)@$data['TYPE_INTERVENANT_ID'] ?: null;
        $this->intervenant = (int)@$data['INTERVENANT_ID'] ?: null;
        $this->structure = (int)@$data['STRUCTURE_ID'] ?: null;
        $this->service = (int)@$data['SERVICE_ID'] ?: null;
        $this->referentiel = (int)@$data['SERVICE_REFERENTIEL_ID'] ?: null;
        $this->mission = (int)@$data['MISSION_ID'] ?: null;
        $this->formuleResService = (int)@$data['FORMULE_RES_SERVICE_ID'] ?: null;
        $this->formuleResServiceRef = (int)@$data['FORMULE_RES_SERVICE_REF_ID'] ?: null;
        $this->typeHeures = (int)@$data['TYPE_HEURES_ID'] ?: null;
        $this->defDomaineFonctionnel = (int)@$data['DEF_DOMAINE_FONCTIONNEL_ID'] ?: null;
        $this->defCentreCout = (int)@$data['DEF_CENTRE_COUT_ID'] ?: null;
        $this->tauxCongesPayes = (float)$data['TAUX_CONGES_PAYES'] ?: null;
        $this->heures = (int)round((float)$data['HEURES'] * 100) ?: null;
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