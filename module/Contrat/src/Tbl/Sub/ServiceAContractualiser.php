<?php





class ServiceAContractualiser
{
    public ?string $key              = null;

    public ?int    $contrat          = null;

    public ?int    $annee            = null;

    public ?int    $intervenant      = null;

    public ?int    $structure        = null;

    public ?int    $service          = null;

    public ?int    $referentiel      = null;

    public ?int    $mission          = null;

    public ?int    $heures           = null;

    public ?int    $hetd             = null;

    public ?int    $cm               = null;

    public ?int    $td               = null;

    public ?int    $tp               = null;

    public ?int    $autre            = null;

    public ?int    $autreLibelle     = null;

    public ?int    $taux_remu        = null;

    public ?float  $taux_remu_valeur = null;

    public ?float  $tauxCongesPayes  = null;


    public function fromBdd(array $data)
    {
        $this->key             = $data['KEY'];

        $this->heures          = (int)round((float)$data['HEURES'] * 100) ?: null;

        $this->contrat          = (int)round((float)$data['CONTRAT_ID']) ?: null;

        $this->annee           = (int)$data['ANNEE_ID'] ?: null;

        $this->intervenant     = (int)@$data['INTERVENANT_ID'] ?: null;

        $this->structure       = (int)@$data['STRUCTURE_ID'] ?: null;

        $this->service         = (int)@$data['SERVICE_ID'] ?: null;
        $this->referentiel     = (int)@$data['SERVICE_REFERENTIEL_ID'] ?: null;
        $this->mission         = (int)@$data['MISSION_ID'] ?: null;
        $this->cm         = (int)@$data['CM'] ?: null;
        $this->td         = (int)@$data['TD'] ?: null;
        $this->tp         = (int)@$data['TP'] ?: null;
        $this->autre         = (int)@$data['AUTRE'] ?: null;
        $this->tauxCongesPayes = (float)$data['TAUX_CONGES_PAYES'] ?: null;

    }



    public function fromArray(array $data)
    {

    }



    public function toArray()
    {
        return [];
    }
    }