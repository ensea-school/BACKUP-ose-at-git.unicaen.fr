<?php

namespace UnicaenSiham\Service;

use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Boolean;
use UnicaenSiham\Entity\Agent;
use UnicaenSiham\Exception\SihamException;

class Siham
{
    const SIHAM_TYPE_ACTION_MODIFICATION          = 'M';
    const SIHAM_TYPE_ACTION_AJOUT                 = 'A';
    const SIHAM_TYPE_ACTION_SUPPRESSION           = 'S';
    const SIHAM_TEMOIN_VALIDITE_DEFAULT           = 1;
    const SIHAM_TEMOIN_ADRESSE_PRINCIPALE         = 1;
    const SIHAM_MOTIF_ENTREE_DEFAULT              = 'PEC';
    const SIHAM_CODE_TYPOLOGIE_ADRESSE_PRINCIPALE = "TA01";
    const SIHAM_CODE_TYPOLOGIE_FIXE_PRO           = "TPR";
    const SIHAM_CODE_TYPOLOGIE_FIXE_PERSO         = "TPE";
    const SIHAM_CODE_TYPOLOGIE_PORTABLE_PRO       = "PPR";
    const SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO     = "PPE";
    const SIHAM_CODE_TYPOLOGIE_EMAIL_PRO          = "MPR";
    const SIHAM_CODE_TYPOLOGIE_EMAIL_PERSO        = "MPE";

    protected $sihamClient;



    public function __construct(SihamClient $sihamClient)
    {
        $this->sihamClient = $sihamClient;
    }



    public function getClient(): SihamClient
    {
        return $this->sihamClient;
    }



    /**
     * @param array $params Les paramètres possible sont les suivants (au moins l'un doit avoir une valeur) : nomUsuel,
     *                      nomPatronymique, prenom
     *
     * @throws SihamException
     *
     * @return array
     */

    public function rechercherAgent($params)
    {
        $agents = [];

        $paramsWS = ['ParamRechercheAgent' => [
            'nomUsuel'        => (isset($params['nomUsuel'])) ? strtoupper($params['nomUsuel']) : '',
            'nomPatronymique' => (isset($params['nomPatronymique'])) ? strtoupper($params['nomPatronymique']) : '',
            'prenom'          => (isset($params['prenom'])) ? strtoupper($params['prenom']) : '',
        ],
        ];

        try {
            $client = $this->sihamClient->getClient('RechercheAgentWebService');
            $result = $client->RechercheAgent($paramsWS);
            if (isset($result->return)) {
                if (is_array($result->return)) {
                    foreach ($result->return as $values) {
                        $agent    = new Agent();
                        $agent    = $agent->mapper($values);
                        $agents[] = $agent;
                    }
                } else {
                    $agent    = new Agent();
                    $agent    = $agent->mapper($result->return);
                    $agents[] = $agent;
                }
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }

        return $agents;
    }



    /**
     * @param array $params Les paramètres possible sont les suivants (au moins l'un doit avoir une valeur) : nomUsuel,
     *                      nomPatronymique, prenom, numeroInsee, dateObservation, temEnseignantChercheur, temEtat
     *
     * @throws SihamException
     *
     * @return array
     */

    public function recupererListeAgents($params)
    {
        $agents = [];

        $paramsWS = ['ParamRecupListeAgents' => [
            'nomUsuel'               => (isset($params['nomUsuel'])) ? strtoupper($params['nomUsuel']) : '',
            'nomPatronymique'        => (isset($params['nomPatronymique'])) ? strtoupper($params['nomPatronymique']) : '',
            'prenom'                 => (isset($params['prenom'])) ? strtoupper($params['prenom']) : '',
            'numeroInsee'            => (isset($params['numeroInsee'])) ? $params['numeroInsee'] : '',
            'dateObservation'        => (isset($params['dateObservation'])) ? $params['dateObservation'] : '',
            'temEnseignantChercheur' => (isset($params['TemEnseignantChercheur'])) ? $params['TemEnseignantChercheur'] : '',
            'temEtat'                => (isset($params['TemEtat'])) ? $params['TemEtat'] : '',

        ]];


        try {
            $client = $this->sihamClient->getClient('ListeAgentsWebService');
            $result = $client->recupListeAgents($paramsWS);
            if (isset($result->return)) {
                if (is_array($result->return)) {
                    foreach ($result->return as $values) {
                        $agent    = new Agent();
                        $agent    = $agent->mapper($values);
                        $agents[] = $agent;
                    }
                } else {
                    $agent    = new Agent();
                    $agent    = $agent->mapper($result->return);
                    $agents[] = $agent;
                }
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }

        return $agents;
    }



    /**
     * @param $params array Paramètres du webservice : codeEtablissement, dateFinObservation, dateObservation, listeMatricules
     *
     * @return Agent
     */


    public function recupererDonneesPersonnellesAgent($params)
    {
        $listMatricules = [];
        foreach ($params['listeMatricules'] as $matricule) {
            $listeMatricules[] = ['matricule' => $matricule];
        }


        $paramsWS = ['ParamListAgent' => [
            'codeEtablissement'  => (isset($params['codeEtablissement'])) ? $params['codeEtablissement'] : '',
            'dateFinObservation' => (isset($params['dateFinObservation'])) ? $params['dateFinObservation'] : '',
            'dateObservation'    => (isset($params['dateObservation'])) ? $params['dateObservation'] : '',
            'listeMatricules'    => $listeMatricules,
        ]];


        try {
            $client = $this->sihamClient->getClient('DossierAgentWebService');
            $result = $client->RecupDonneesPersonnelles($paramsWS);
            if (isset($result->return)) {

                $agent = new Agent();
                $agent = $agent->mapper($result->return);
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }

        return $agent;
    }



    public function ajouterAdresseAgent(array $params)
    {
        $dateDebut = new \DateTime('+1 day');

        $paramsWS = ['ParamModifDP' => [
            'bisTer'                 => (isset($params['bisTer'])) ? strtoupper($params['bisTer']) : '',
            'codeEtablissement'      => (isset($params['codeEtablissement'])) ? strtoupper($params['codeEtablissement']) : '',
            'codePays'               => (isset($params['codePays'])) ? strtoupper($params['codePays']) : '',
            'codePostal'             => (isset($params['codePostal'])) ? strtoupper($params['codePostal']) : '',
            'codeUOAffectAdresse'    => (isset($params['codeUOAffectAdresse'])) ? strtoupper($params['codeUOAffectAdresse']) : '',
            'complementAdresse'      => (isset($params['complementAdresse'])) ? strtoupper($params['complementAdresse']) : '',
            'dateDebut'              => $dateDebut->format('Y-m-d'),//obligatoire
            'dateFin'                => '',
            'matricule'              => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'natureVoie'             => (isset($params['natureVoie'])) ? strtoupper($params['natureVoie']) : '',
            'noVoie'                 => (isset($params['noVoie'])) ? strtoupper($params['noVoie']) : '',
            'nomVoie'                => (isset($params['nomVoie'])) ? strtoupper($params['nomVoie']) : '',
            'numero'                 => (isset($params['numero'])) ? strtoupper($params['numero']) : '',
            'pourcentageAffectation' => (isset($params['pourcentageAffectation'])) ? strtoupper($params['pourcentageAffectation']) : '',
            'typeAction'             => self::SIHAM_TYPE_ACTION_AJOUT,//obligatoire
            'typeAdrPers'            => self::SIHAM_CODE_TYPOLOGIE_ADRESSE_PRINCIPALE,
            'typeNUmero'             => '',
            'ville'                  => (isset($params['ville'])) ? strtoupper($params['ville']) : '',
        ]];

        try {
            $client = $this->sihamClient->getClient('DossierAgentWebService');
            $result = $client->ModifDonneesPersonnelles($paramsWS);
            if (isset($result->return)) {
                if ($result->return->statutMAJ == 1) {
                    return true;
                } else {
                    $message = $result->return->statutMAJ;

                    throw new SihamException($result->return->statutMAJ, 0);
                }
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }

        return false;
    }



    /**
     * @param $params       array Paramètres du webservice : bisTer, codePostal, complementAdresse,dateDebut, matricule,
     *                      natureVoie, noVoie, nomVoie, ville
     *
     * @return Boolean
     */

    public function modifierAdresseAgent(array $params, $typeAdresse = 'TA01'): bool
    {
        $paramsWS = ['ParamModifDP' => [
            'bisTer'                 => (isset($params['bisTer'])) ? strtoupper($params['bisTer']) : '',
            'codeEtablissement'      => (isset($params['codeEtablissement'])) ? strtoupper($params['codeEtablissement']) : '',
            'codePays'               => (isset($params['codePays'])) ? strtoupper($params['codePays']) : '',
            'codePostal'             => (isset($params['codePostal'])) ? strtoupper($params['codePostal']) : '',
            'codeUOAffectAdresse'    => (isset($params['codeUOAffectAdresse'])) ? strtoupper($params['codeUOAffectAdresse']) : '',
            'complementAdresse'      => (isset($params['complementAdresse'])) ? strtoupper($params['complementAdresse']) : '',
            'dateDebut'              => '',
            'dateFin'                => '',
            'matricule'              => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'natureVoie'             => (isset($params['natureVoie'])) ? strtoupper($params['natureVoie']) : '',
            'noVoie'                 => (isset($params['noVoie'])) ? strtoupper($params['noVoie']) : '',
            'nomVoie'                => (isset($params['nomVoie'])) ? strtoupper($params['nomVoie']) : '',
            'numero'                 => (isset($params['numero'])) ? strtoupper($params['numero']) : '',
            'pourcentageAffectation' => (isset($params['pourcentageAffectation'])) ? strtoupper($params['pourcentageAffectation']) : '',
            'typeAction'             => self::SIHAM_TYPE_ACTION_MODIFICATION,//obligatoire
            'typeAdrPers'            => self::SIHAM_CODE_TYPOLOGIE_ADRESSE_PRINCIPALE,
            'typeNUmero'             => '',
            'ville'                  => (isset($params['ville'])) ? strtoupper($params['ville']) : '',
        ]];

        try {
            //On récupére l'agent pour pouvoir le modifier
            $agent = $this->recupererDonneesPersonnellesAgent(['listeMatricules' => [$params['matricule']]]);

            if ($agent) {
                $paramsWS['ParamModifDP']['dateDebut'] = $agent->getDateDebutAdresse();
                $client                                = $this->sihamClient->getClient('DossierAgentWebService');
                $result                                = $client->ModifDonneesPersonnelles($paramsWS);
                if (isset($result->return)) {
                    if ($result->return->statutMAJ == 1) {
                        return true;
                    } else {
                        $message = $result->return->statutMAJ;
                        throw new SihamException($result->return->statutMAJ, 0);
                    }
                }
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }

        return false;
    }



    public function historiserAdresseAgent(array $params, $typeAdresse = 'TA01'): bool
    {
        $dateFin = new \DateTime();

        $paramsWS = ['ParamModifDP' => [
            'bisTer'                 => (isset($params['bisTer'])) ? strtoupper($params['bisTer']) : '',
            'codeEtablissement'      => (isset($params['codeEtablissement'])) ? strtoupper($params['codeEtablissement']) : '',
            'codePays'               => (isset($params['codePays'])) ? strtoupper($params['codePays']) : '',
            'codePostal'             => (isset($params['codePostal'])) ? strtoupper($params['codePostal']) : '',
            'codeUOAffectAdresse'    => (isset($params['codeUOAffectAdresse'])) ? strtoupper($params['codeUOAffectAdresse']) : '',
            'complementAdresse'      => (isset($params['complementAdresse'])) ? strtoupper($params['complementAdresse']) : '',
            'dateDebut'              => (isset($params['dateDebut'])) ? strtoupper($params['dateDebut']) : '',
            'dateFin'                => $dateFin->format('Y-m-d'),//obligatoire,
            'matricule'              => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'natureVoie'             => (isset($params['natureVoie'])) ? strtoupper($params['natureVoie']) : '',
            'noVoie'                 => (isset($params['noVoie'])) ? strtoupper($params['noVoie']) : '',
            'nomVoie'                => (isset($params['nomVoie'])) ? strtoupper($params['nomVoie']) : '',
            'numero'                 => (isset($params['numero'])) ? strtoupper($params['numero']) : '',
            'pourcentageAffectation' => (isset($params['pourcentageAffectation'])) ? strtoupper($params['pourcentageAffectation']) : '',
            'typeAction'             => self::SIHAM_TYPE_ACTION_MODIFICATION,//obligatoire
            'typeAdrPers'            => self::SIHAM_CODE_TYPOLOGIE_ADRESSE_PRINCIPALE,
            'typeNUmero'             => '',
            'ville'                  => (isset($params['ville'])) ? strtoupper($params['ville']) : '',
        ]];

        try {
            //On récupére l'agent pour pouvoir le modifier
            $agent = $this->recupererDonneesPersonnellesAgent(['listeMatricules' => [$params['matricule']]]);

            if ($agent) {
                $paramsWS['ParamModifDP']['dateDebut'] = $agent->getDateDebutAdresse();
                $client                                = $this->sihamClient->getClient('DossierAgentWebService');
                $result                                = $client->ModifDonneesPersonnelles($paramsWS);
                var_dump($this->sihamClient->getLastRequest());
                die;
                if (isset($result->return)) {
                    if ($result->return->statutMAJ == 1) {
                        return true;
                    } else {
                        $message = $result->return->statutMAJ;
                        throw new SihamException($result->return->statutMAJ, 0);
                    }
                }
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }

        return false;
    }



    public function modifierTelephoneAgent(array $params, $typeNumero = self::SIHAM_CODE_TYPOLOGIE_NUMERO_FIXE_PROFESSIONNEL): bool
    {
        $paramsWS = ['ParamModifDP' => [
            'bisTer'                 => (isset($params['bisTer'])) ? strtoupper($params['bisTer']) : '',
            'codeEtablissement'      => (isset($params['codeEtablissement'])) ? strtoupper($params['codeEtablissement']) : '',
            'codePays'               => (isset($params['codePays'])) ? strtoupper($params['codePays']) : '',
            'codePostal'             => (isset($params['codePostal'])) ? strtoupper($params['codePostal']) : '',
            'codeUOAffectAdresse'    => (isset($params['codeUOAffectAdresse'])) ? strtoupper($params['codeUOAffectAdresse']) : '',
            'complementAdresse'      => (isset($params['complementAdresse'])) ? strtoupper($params['complementAdresse']) : '',
            'dateDebut'              => (isset($params['dateDebut'])) ? strtoupper($params['dateDebut']) : '',
            'dateFin'                => '',//obligatoire,
            'matricule'              => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'natureVoie'             => (isset($params['natureVoie'])) ? strtoupper($params['natureVoie']) : '',
            'noVoie'                 => (isset($params['noVoie'])) ? strtoupper($params['noVoie']) : '',
            'nomVoie'                => (isset($params['nomVoie'])) ? strtoupper($params['nomVoie']) : '',
            'numero'                 => (isset($params['numero'])) ? strtoupper($params['numero']) : '',
            'pourcentageAffectation' => (isset($params['pourcentageAffectation'])) ? strtoupper($params['pourcentageAffectation']) : '',
            'typeAction'             => self::SIHAM_TYPE_ACTION_MODIFICATION,//obligatoire
            'typeAdrPers'            => '',
            'typeNumero'             => $typeNumero,
            'ville'                  => (isset($params['ville'])) ? strtoupper($params['ville']) : '',
        ]];

        try {
            //On récupére l'agent pour pouvoir le modifier
            $agent = $this->recupererDonneesPersonnellesAgent(['listeMatricules' => [$params['matricule']]]);

            if ($agent) {
                switch ($typeNumero) {
                    case self::SIHAM_CODE_TYPOLOGIE_FIXE_PRO:
                        $paramsWS['ParamModifDP']['dateDebut'] = $agent->getTelephoneProDateDebut();
                        if (!empty($agent->getTelephoneProDateDebut())) {
                            $paramsWS['ParamModifDP']['typeAction'] = self::SIHAM_TYPE_ACTION_MODIFICATION;
                        } else {
                            $paramsWS['ParamModifDP']['typeAction'] = self::SIHAM_TYPE_ACTION_AJOUT;
                        }
                    break;
                    case self::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO:
                        $paramsWS['ParamModifDP']['dateDebut'] = $agent->getTelephonePersoDateDebut();
                        if (!empty($agent->getTelephonePersoDateDebut())) {
                            $paramsWS['ParamModifDP']['typeAction'] = self::SIHAM_TYPE_ACTION_MODIFICATION;
                        } else {
                            $paramsWS['ParamModifDP']['typeAction'] = self::SIHAM_TYPE_ACTION_AJOUT;
                        }
                    break;
                }

                $client = $this->sihamClient->getClient('DossierAgentWebService');
                $result = $client->ModifDonneesPersonnelles($paramsWS);
                if (isset($result->return)) {
                    if ($result->return->statutMAJ == 1) {
                        return true;
                    } else {
                        $message = $result->return->statutMAJ;
                        throw new SihamException($result->return->statutMAJ, 0);
                    }
                }
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }

        return false;
    }



    public function supprimerTelephoneAgent(array $params, $typeNumero = self::SIHAM_CODE_TYPOLOGIE_NUMERO_FIXE_PROFESSIONNEL): bool
    {
        $dateFin = new \DateTime();

        $paramsWS = ['ParamModifDP' => [
            'bisTer'                 => (isset($params['bisTer'])) ? strtoupper($params['bisTer']) : '',
            'codeEtablissement'      => (isset($params['codeEtablissement'])) ? strtoupper($params['codeEtablissement']) : '',
            'codePays'               => (isset($params['codePays'])) ? strtoupper($params['codePays']) : '',
            'codePostal'             => (isset($params['codePostal'])) ? strtoupper($params['codePostal']) : '',
            'codeUOAffectAdresse'    => (isset($params['codeUOAffectAdresse'])) ? strtoupper($params['codeUOAffectAdresse']) : '',
            'complementAdresse'      => (isset($params['complementAdresse'])) ? strtoupper($params['complementAdresse']) : '',
            'dateDebut'              => (isset($params['dateDebut'])) ? strtoupper($params['dateDebut']) : '',
            'dateFin'                => $dateFin->format('Y-m-d'),//obligatoire,
            'matricule'              => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'natureVoie'             => (isset($params['natureVoie'])) ? strtoupper($params['natureVoie']) : '',
            'noVoie'                 => (isset($params['noVoie'])) ? strtoupper($params['noVoie']) : '',
            'nomVoie'                => (isset($params['nomVoie'])) ? strtoupper($params['nomVoie']) : '',
            'numero'                 => (isset($params['numero'])) ? strtoupper($params['numero']) : '',
            'pourcentageAffectation' => (isset($params['pourcentageAffectation'])) ? strtoupper($params['pourcentageAffectation']) : '',
            'typeAction'             => self::SIHAM_TYPE_ACTION_SUPPRESSION,//obligatoire
            'typeAdrPers'            => '',
            'typeNumero'             => $typeNumero,
            'ville'                  => (isset($params['ville'])) ? strtoupper($params['ville']) : '',
        ]];

        try {
            //On récupére l'agent pour pouvoir le modifier
            $agent = $this->recupererDonneesPersonnellesAgent(['listeMatricules' => [$params['matricule']]]);

            if ($agent) {


                switch ($typeNumero) {
                    case self::SIHAM_CODE_TYPOLOGIE_FIXE_PRO:
                        if (empty($agent->getTelephonePro())) {
                            return false;
                        }
                        $paramsWS['ParamModifDP']['dateDebut'] = $agent->getTelephoneProDateDebut();
                        $paramsWS['ParamModifDP']['numero']    = $agent->getTelephonePro();
                    break;
                    case self::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO:
                        if (empty($agent->getTelephonePerso())) {
                            return false;
                        }
                        $paramsWS['ParamModifDP']['dateDebut'] = $agent->getTelephonePersoDateDebut();
                        $paramsWS['ParamModifDP']['numero']    = $agent->getTelephonePerso();
                    break;
                }
                $client = $this->sihamClient->getClient('DossierAgentWebService');
                $result = $client->ModifDonneesPersonnelles($paramsWS);

                if (isset($result->return)) {
                    if ($result->return->statutMAJ == 1) {
                        return true;
                    } else {
                        $message = $result->return->statutMAJ;
                        throw new SihamException($result->return->statutMAJ, 0);
                    }
                }
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }

        return false;
    }



    public function modifierEmailAgent(array $params, $typeNumero = self::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO): bool
    {
        $paramsWS = ['ParamModifDP' => [
            'bisTer'                 => (isset($params['bisTer'])) ? strtoupper($params['bisTer']) : '',
            'codeEtablissement'      => (isset($params['codeEtablissement'])) ? strtoupper($params['codeEtablissement']) : '',
            'codePays'               => (isset($params['codePays'])) ? strtoupper($params['codePays']) : '',
            'codePostal'             => (isset($params['codePostal'])) ? strtoupper($params['codePostal']) : '',
            'codeUOAffectAdresse'    => (isset($params['codeUOAffectAdresse'])) ? strtoupper($params['codeUOAffectAdresse']) : '',
            'complementAdresse'      => (isset($params['complementAdresse'])) ? strtoupper($params['complementAdresse']) : '',
            'dateDebut'              => (isset($params['dateDebut'])) ? strtoupper($params['dateDebut']) : '',
            'dateFin'                => '',//obligatoire,
            'matricule'              => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'natureVoie'             => (isset($params['natureVoie'])) ? strtoupper($params['natureVoie']) : '',
            'noVoie'                 => (isset($params['noVoie'])) ? strtoupper($params['noVoie']) : '',
            'nomVoie'                => (isset($params['nomVoie'])) ? strtoupper($params['nomVoie']) : '',
            'numero'                 => (isset($params['numero'])) ? strtoupper($params['numero']) : '',
            'pourcentageAffectation' => (isset($params['pourcentageAffectation'])) ? strtoupper($params['pourcentageAffectation']) : '',
            'typeAction'             => self::SIHAM_TYPE_ACTION_MODIFICATION,//obligatoire
            'typeAdrPers'            => '',
            'typeNumero'             => $typeNumero,
            'ville'                  => (isset($params['ville'])) ? strtoupper($params['ville']) : '',
        ]];

        try {
            //On récupére l'agent pour pouvoir le modifier
            $agent = $this->recupererDonneesPersonnellesAgent(['listeMatricules' => [$params['matricule']]]);

            if ($agent) {
                switch ($typeNumero) {
                    case self::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO:
                        $paramsWS['ParamModifDP']['dateDebut'] = $agent->getEmailProDateDebut();
                        if (!empty($agent->getEmailPro())) {
                            $paramsWS['ParamModifDP']['typeAction'] = self::SIHAM_TYPE_ACTION_MODIFICATION;
                        } else {
                            $paramsWS['ParamModifDP']['typeAction'] = self::SIHAM_TYPE_ACTION_AJOUT;
                        }
                    break;
                    case self::SIHAM_CODE_TYPOLOGIE_EMAIL_PERSO:
                        $paramsWS['ParamModifDP']['dateDebut'] = $agent->getEmailPersoDateDebut();
                        if (!empty($agent->getEmailPerso())) {
                            $paramsWS['ParamModifDP']['typeAction'] = self::SIHAM_TYPE_ACTION_MODIFICATION;
                        } else {
                            $paramsWS['ParamModifDP']['typeAction'] = self::SIHAM_TYPE_ACTION_AJOUT;
                        }
                    break;
                }

                $client = $this->sihamClient->getClient('DossierAgentWebService');
                $result = $client->ModifDonneesPersonnelles($paramsWS);
                if (isset($result->return)) {
                    if ($result->return->statutMAJ == 1) {
                        return true;
                    } else {
                        $message = $result->return->statutMAJ;
                        throw new SihamException($result->return->statutMAJ, 0);
                    }
                }
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }

        return false;
    }



    public function modifierCoordonnéesBancairesAgent(array $params): bool
    {

    }



    public function priseEnChargeAgent($params)
    {
        //Traitement de l'adresse principale
        $listeCoordonneesPostales = [];

        if (!empty($params['listeCoordonneesPostales'])) {
            foreach ($params['listeCoordonneesPostales'] as $coordonnees) {
                $listeCoordonneesPostales[] = [
                    'bureauDistributeur'   => (isset($coordonnees['bureauDistributeur'])) ? strtoupper($coordonnees['bureauDistributeur']) : '',
                    'codePays'             => (isset($coordonnees['codePays'])) ? strtoupper($coordonnees['codePays']) : '',
                    'codePostal'           => (isset($coordonnees['codePostal'])) ? strtoupper($coordonnees['codePostal']) : '',
                    'commune'              => (isset($coordonnees['commune'])) ? strtoupper($coordonnees['commune']) : '',
                    'debutAdresse'         => (isset($coordonnees['debutAdresse'])) ? strtoupper($coordonnees['debutAdresse']) : '',
                    'natureVoie'           => (isset($coordonnees['natureVoie'])) ? strtoupper($coordonnees['natureVoie']) : '',
                    'nomVoie'              => (isset($coordonnees['nomVoie'])) ? strtoupper($coordonnees['nomVoie']) : '',
                    'numAdresse'           => (isset($coordonnees['numAdresse'])) ? strtoupper($coordonnees['numAdresse']) : '',
                    'temAdressePrincipale' => (isset($coordonnees['temAdressePrincipale'])) ? strtoupper($coordonnees['temAdressePrincipale']) : self::SIHAM_TEMOIN_ADRESSE_PRINCIPALE,
                    'temoinValidite'       => (isset($coordonnees['temoinValidite'])) ? strtoupper($coordonnees['temoinValidite']) : self::SIHAM_TEMOIN_VALIDITE_DEFAULT,
                    'typeAdresse'          => (isset($coordonnees['typeAdresse'])) ? strtoupper($coordonnees['typeAdresse']) : self::SIHAM_CODE_TYPOLOGIE_ADRESSE_PRINCIPALE,
                ];
            }
        } else {
            $listeCoordonneesPostales[] = [
                'bureauDistributeur'   => '',
                'codePays'             => '',
                'codePostal'           => '',
                'commune'              => '',
                'debutAdresse'         => '',
                'natureVoie'           => '',
                'nomVoie'              => '',
                'numAdresse'           => '',
                'temAdressePrincipale' => '',
                'temoinValidite'       => '',
                'typeAdresse'          => '',
            ];
        }


        //Traitement des coordonnées bancaires
        $listeCoordonneesBancaires = [];
        if (!empty($params['listeCoordonneesBancaires'])) {
            foreach ($params['listeCoordonneesBancaires'] as $coordonnees) {
                $listeCoordonneesBancaires[] = [
                    'cleCompte'      => (isset($coordonnees['cleCompte'])) ? strtoupper($coordonnees['cleCompte']) : '',
                    'codeAgence'     => (isset($coordonnees['codeAgence'])) ? strtoupper($coordonnees['codeAgence']) : '',
                    'codeBanque'     => (isset($coordonnees['codeBanque'])) ? strtoupper($coordonnees['codeBanque']) : '',
                    'dateDebBanque'  => (isset($coordonnees['dateDebBanque'])) ? strtoupper($coordonnees['dateDebBanque']) : '',
                    'dateFinBanque'  => (isset($coordonnees['dateFinBanque'])) ? strtoupper($coordonnees['dateFinBanque']) : '',
                    'IBAN'           => (isset($coordonnees['IBAN'])) ? strtoupper($coordonnees['IBAN']) : '',
                    'libelleAgence'  => (isset($coordonnees['libelleAgence'])) ? strtoupper($coordonnees['libelleAgence']) : '',
                    'modePaiement'   => (isset($coordonnees['modePaiement'])) ? strtoupper($coordonnees['modePaiement']) : '',
                    'numCompte'      => (isset($coordonnees['numCompte'])) ? strtoupper($coordonnees['numCompte']) : '',
                    'paysBanque'     => (isset($coordonnees['paysBanque'])) ? strtoupper($coordonnees['paysBanque']) : '',
                    'SWIFT'          => (isset($coordonnees['SWIFT'])) ? strtoupper($coordonnees['SWIFT']) : '',
                    'temoinValidite' => (isset($coordonnees['temoinValidite'])) ? strtoupper($coordonnees['temoinValidite']) : '',

                ];
            }
        } else {
            $listeCoordonneesBancaires[] = [
                'cleCompte'      => '',
                'codeAgence'     => '',
                'codeBanque'     => '',
                'dateDebBanque'  => '',
                'dateFinBanque'  => '',
                'IBAN'           => '',
                'libelleAgence'  => '',
                'modePaiement'   => '',
                'numCompte'      => '',
                'paysBanque'     => '',
                'SWIFT'          => '',
                'temoinValidite' => '',

            ];
        }

        //Traitement des modalités de services
        $listeModalitesServices = [];

        if (!empty($params['listeModalitesServices'])) {
            foreach ($params['listeModalitesServices'] as $modalite) {
                $listeModalitesServices[] = ['dateEffetModalite' => $modalite['dateEffetModalite'],
                                             'modalite'          => $modalite['modalite']];
            }
        } else {
            $listeModalitesServices[] = ['dateEffetModalite' => '',
                                         'modalite'          => ''];
        }

        //Traitement du statut

        $listeStatuts = [];

        if (!empty($params['listeStatuts'])) {
            foreach ($params['listeStatuts'] as $statut) {
                $listeStatuts[] = ['dateEffetStatut' => $statut['dateEffetStatut'],
                                   'statut'          => $statut['statut']];
            }
        } else {
            $listeStatuts[] = ['dateEffetStatut' => '',
                               'statut'          => ''];
        }

        //Traitement de la nationalité

        $listeNationalites = [];
        if (!empty($params['listeNationalites'])) {
            foreach ($params['listeNationalites'] as $nationalite) {
                $listeNationalites[] = [
                    'nationalite' => (isset($nationalite['nationalite'])) ? strtoupper($nationalite['nationalite']) : '',
                ];
            }
        } else {
            $listeNationalites[] = [
                'nationalite' => '',
            ];
        }

        //Traitement du numéro de téléphone

        $listeNumerosTelephoneFax = [];
        if (!empty($params['listeNumerosTelephoneFax'])) {
            foreach ($params['listeNumerosTelephoneFax'] as $numero) {
                $listeNumerosTelephoneFax[] = [
                    'dateDebutTel' => (isset($numero['dateDebutTel'])) ? strtoupper($numero['dateDebutTel']) : '',
                    'numero'       => (isset($numero['numero'])) ? strtoupper($numero['numero']) : '',
                    'typeNumero'   => (isset($numero['typeNumero'])) ? strtoupper($numero['typeNumero']) : '',

                ];
            }
        } else {
            $listeNumerosTelephoneFax[] = [
                'dateDebutTel' => '',
                'numero'       => '',
                'typeNumero'   => '',

            ];
        }

        //Traitement des positions
        $listePositions = [];
        if (!empty($params['listePositions'])) {
            foreach ($params['listePositions'] as $position) {
                $listePositions[] = [
                    'dateEffetPosition' => (isset($position['dateEffetPosition'])) ? strtoupper($position['dateEffetPosition']) : '',
                    'position'          => (isset($position['position'])) ? strtoupper($position['position']) : '',
                    'temoinValidite'    => (isset($position['temoinValidite'])) ? strtoupper($position['temoinValidite']) : '',
                ];
            }
        } else {
            $listePositions[] = [
                'dateEffetPosition' => '',
                'position'          => '',
                'temoinValidite'    => '',
            ];
        }

        $paramsWS = ['ParamPEC' => [
            'categorieEntree'           => (isset($params['categorieEntree'])) ? strtoupper($params['categorieEntree']) : '',
            'civilite'                  => (isset($params['civilite'])) ? strtoupper($params['civilite']) : '',
            'codeAdministration'        => (isset($params['codeAdministration'])) ? strtoupper($params['codeAdministration']) : '',
            'codeEtablissement'         => (isset($params['codeEtablissement'])) ? strtoupper($params['codeEtablissement']) : '',
            'dateEmbauche'              => (isset($params['dateEmbauche'])) ? strtoupper($params['dateEmbauche']) : '',
            'dateNaissance'             => (isset($params['dateNaissance'])) ? strtoupper($params['dateNaissance']) : '',
            'villeNaissance'            => (isset($params['villeNaissance'])) ? strtoupper($params['villeNaissance']) : '',
            'departementNaissance'      => (isset($params['departementNaissance'])) ? strtoupper($params['departementNaissance']) : '',
            'emploi'                    => (isset($params['emploi'])) ? strtoupper($params['emploi']) : '',
            'listeCoordonneesPostales'  => $listeCoordonneesPostales,
            'listeCoordonneesBancaires' => $listeCoordonneesBancaires,
            'listeModalitesServices'    => $listeModalitesServices,
            'listeStatuts'              => $listeStatuts,
            'listeNationalites'         => $listeNationalites,
            'listeNumerosTelephoneFax'  => $listeNumerosTelephoneFax,
            'listePositions'            => $listePositions,
            'motifEntree'               => (isset($params['motifEntree'])) ? strtoupper($params['motifEntree']) : self::SIHAM_MOTIF_ENTREE_DEFAULT,
            'nomPatronymique'           => (isset($params['nomPatronymique'])) ? strtoupper($params['nomPatronymique']) : '',
            'nomUsuel'                  => (isset($params['nomUsuel'])) ? strtoupper($params['nomUsuel']) : '',
            'numeroInsee'               => (isset($params['numeroInsee'])) ? strtoupper($params['numeroInsee']) : '',
            'paysNaissance'             => (isset($params['paysNaissance'])) ? strtoupper($params['paysNaissance']) : '',
            'prenom'                    => (isset($params['prenom'])) ? strtoupper($params['prenom']) : '',
            'sexe'                      => (isset($params['sexe'])) ? strtoupper($params['sexe']) : '1',
            'temoinValidite'            => (isset($params['temoinValidite'])) ? strtoupper($params['temoinValidite']) : '',
            'UO'                        => (isset($params['UO'])) ? strtoupper($params['UO']) : '',

        ]];

        try {
            $client = $this->sihamClient->getClient('PECWebService');
            $result = $client->PriseEnChargeAgent($paramsWS);
            //var_dump($this->sihamClient->getLastRequest());
            //die;
            if ($result->return->statut == 'ERREUR_GENERALE') {
                var_dump($result->return);
                die;
                throw new SihamException('Erreur générale', 0);
            }

            return $result->return;
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }
    }



    /**
     * @param $params       array Paramètres du webservice : codeRepertoire
     *
     * @return array
     */

    public function recupererNomenclatureRH(array $params)
    {
        $listeNomenclatures = [];

        foreach ($params['listeNomenclatures'] as $nomenclature) {
            $listeNomenclatures[] = ['codeRepertoire' => $nomenclature];
        }

        $paramsWS = ['ParamNomenclature' => [
            'dateObservation'    => '',
            'listeNomenclatures' => $listeNomenclatures,
        ]];


        try {
            $client = $this->sihamClient->getClient('DossierParametrageWebService');
            $result = $client->RecupNomenclaturesRH($paramsWS);

            if (isset($result->return)) {
                return $result->return;
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }
    }

}