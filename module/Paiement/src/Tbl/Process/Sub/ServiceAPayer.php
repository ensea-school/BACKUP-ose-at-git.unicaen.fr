<?php

namespace Paiement\Tbl\Process\Sub;

class ServiceAPayer
{
    public string $key;
    public int $annee;
    public int $intervenant;
    public int $structure;
    public ?int $service;
    public ?int $referentiel;
    public ?int $mission;
    public ?int $formuleResService;
    public ?int $formuleResServiceRef;
    public int $typeHeures;
    public ?int $defDomaineFonctionnel;
    public ?int $defCentreCout;
    public float $tauxCongesPayes;
    public ?int $heures;

    /** @var array|LigneAPayer[] */
    public array $lignesAPayer = [];

    /** @var array|MiseEnPaiement[] */
    public array $misesEnPaiement = [];



    public function fromBdd(array $data)
    {
        $this->key = $data['KEY'];
        $this->annee = (int)$data['ANNEE_ID'];
        $this->intervenant = (int)$data['INTERVENANT_ID'];
        $this->structure = (int)$data['STRUCTURE_ID'];
        $this->service = (int)$data['SERVICE_ID'] ?: null;
        $this->referentiel = (int)$data['SERVICE_REFERENTIEL_ID'] ?: null;
        $this->mission = (int)$data['MISSION_ID'] ?: null;
        $this->formuleResService = (int)$data['FORMULE_RES_SERVICE_ID'] ?: null;
        $this->formuleResServiceRef = (int)$data['FORMULE_RES_SERVICE_REF_ID'] ?: null;
        $this->typeHeures = (int)$data['TYPE_HEURES_ID'];
        $this->defDomaineFonctionnel = (int)$data['DEF_DOMAINE_FONCTIONNEL_ID'] ?: null;
        $this->defCentreCout = (int)$data['DEF_CENTRE_COUT_ID'] ?: null;
        $this->tauxCongesPayes = (float)$data['TAUX_CONGES_PAYES'];
        $this->heures = (int)round((float)$data['HEURES']*100) ?: null;
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
                $lap = new LigneAPayer();
                $lap->fromArray($dlap);
                $this->lignesAPayer[$did] = $lap;
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



    public function toArray()
    {
        $vars = get_object_vars($this);

        foreach ($vars['lignesAPayer'] as $id => $lap) {
            $vars['lignesAPayer'][$id] = $lap->toArray();
        }
        foreach ($vars['misesEnPaiement'] as $id => $mep) {
            $vars['misesEnPaiement'][$id] = $mep->toArray();
        }

        if (empty($vars['misesEnPaiement'])){
            unset($vars['misesEnPaiement']);
        }

        return $vars;
    }
}