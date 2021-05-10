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
            'bisTer'              => (isset($params['bisTer'])) ? strtoupper($params['bisTer']) : '',
            'codePostal'          => (isset($params['codePostal'])) ? strtoupper($params['codePostal']) : '',
            'complementAdresse'   => (isset($params['complementAdresse'])) ? strtoupper($params['complementAdresse']) : '',
            'dateDebut'           => $dateDebut->format('Y-m-d'),//obligatoire
            'matricule'           => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'natureVoie'          => (isset($params['natureVoie'])) ? strtoupper($params['natureVoie']) : '',
            'noVoie'              => (isset($params['noVoie'])) ? strtoupper($params['noVoie']) : '',
            'nomVoie'             => (isset($params['nomVoie'])) ? strtoupper($params['nomVoie']) : '',
            'codeUOAffectAdresse' => (isset($params['codeUOAffectAdresse'])) ? strtoupper($params['codeUOAffectAdresse']) : '',
            'typeAction'          => self::SIHAM_TYPE_ACTION_AJOUT,//obligatoire
            'typeAdrPers'         => self::SIHAM_CODE_TYPOLOGIE_ADRESSE_PRINCIPALE,
            'ville'               => (isset($params['ville'])) ? strtoupper($params['ville']) : '',
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
            'bisTer'              => (isset($params['bisTer'])) ? strtoupper($params['bisTer']) : '',
            'codePostal'          => (isset($params['codePostal'])) ? strtoupper($params['codePostal']) : '',
            'complementAdresse'   => (isset($params['complementAdresse'])) ? strtoupper($params['complementAdresse']) : '',
            'dateDebut'           => (isset($params['dateDebut'])) ? strtoupper($params['dateDebut']) : '',//obligatoire
            'dateFin'             => (isset($params['dateFin'])) ? strtoupper($params['dateFin']) : '',//obligatoire
            'matricule'           => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'natureVoie'          => (isset($params['natureVoie'])) ? strtoupper($params['natureVoie']) : '',
            'noVoie'              => (isset($params['noVoie'])) ? strtoupper($params['noVoie']) : '',
            'nomVoie'             => (isset($params['nomVoie'])) ? strtoupper($params['nomVoie']) : '',
            'codeUOAffectAdresse' => (isset($params['codeUOAffectAdresse'])) ? strtoupper($params['codeUOAffectAdresse']) : '',
            'typeAction'          => self::SIHAM_TYPE_ACTION_MODIFICATION,//obligatoire
            'typeAdrPers'         => $typeAdresse,//obligatoire
            'ville'               => (isset($params['ville'])) ? strtoupper($params['ville']) : '',
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
        $paramsWS = ['ParamModifDP' => [
            'bisTer'              => (isset($params['bisTer'])) ? strtoupper($params['bisTer']) : '',
            'codePostal'          => (isset($params['codePostal'])) ? strtoupper($params['codePostal']) : '',
            'complementAdresse'   => (isset($params['complementAdresse'])) ? strtoupper($params['complementAdresse']) : '',
            'matricule'           => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'natureVoie'          => (isset($params['natureVoie'])) ? strtoupper($params['natureVoie']) : '',
            'noVoie'              => (isset($params['noVoie'])) ? strtoupper($params['noVoie']) : '',
            'nomVoie'             => (isset($params['nomVoie'])) ? strtoupper($params['nomVoie']) : '',
            'ville'               => (isset($params['ville'])) ? strtoupper($params['ville']) : '',
            'codeUOAffectAdresse' => (isset($params['codeUOAffectAdresse'])) ? strtoupper($params['codeUOAffectAdresse']) : '',
            'dateDebut'           => (isset($params['dateDebut'])) ? strtoupper($params['dateDebut']) : '',//obligatoire
            'dateFin'             => (isset($params['dateFin'])) ? strtoupper($params['dateFin']) : '',//obligatoire
            'typeAction'          => self::SIHAM_TYPE_ACTION_MODIFICATION,//obligatoire
            'typeAdrPers'         => $typeAdresse,//obligatoire
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



    public function modifierTelephoneAgent(array $params, $typeNumero = self::SIHAM_CODE_TYPOLOGIE_NUMERO_FIXE_PROFESSIONNEL): bool
    {
        $paramsWS = ['ParamModifDP' => [
            'matricule'           => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'codeUOAffectAdresse' => (isset($params['codeUOAffectAdresse'])) ? strtoupper($params['codeUOAffectAdresse']) : '',
            'dateDebut'           => (isset($params['dateDebut'])) ? strtoupper($params['dateDebut']) : '',//obligatoire
            'dateFin'             => (isset($params['dateFin'])) ? strtoupper($params['dateFin']) : '',//obligatoire
            'typeAction'          => self::SIHAM_TYPE_ACTION_MODIFICATION,//obligatoire
            'numero'              => (isset($params['numero'])) ? strtoupper($params['numero']) : '',//obligatoire
            'typeNumero'          => $typeNumero,//obligatoire
        ]];

        try {
            //On récupére l'agent pour pouvoir le modifier
            $agent = $this->recupererDonneesPersonnellesAgent(['listeMatricules' => [$params['matricule']]]);

            if ($agent) {
                $paramsWS['ParamModifDP']['dateDebut'] = $agent->getTelephoneProDateDebut();
                if (!empty($agent->getTelephoneProDateDebut())) {
                    $paramsWS['ParamModifDP']['typeAction'] = self::SIHAM_TYPE_ACTION_MODIFICATION;
                } else {
                    $paramsWS['ParamModifDP']['typeAction'] = self::SIHAM_TYPE_ACTION_AJOUT;
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



    public function ajouterTelephoneAgent(array $params, $typeNumero = self::SIHAM_CODE_TYPOLOGIE_FIXE_PRO): bool
    {
        $dateDebut = new \DateTime();

        $paramsWS = ['ParamModifDP' => [
            'matricule'           => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'codeUOAffectAdresse' => (isset($params['codeUOAffectAdresse'])) ? strtoupper($params['codeUOAffectAdresse']) : '',
            'dateDebut'           => $dateDebut->format('Y-m-d'),//obligatoire
            'dateFin'             => (isset($params['dateFin'])) ? strtoupper($params['dateFin']) : '',//obligatoire
            'typeAction'          => self::SIHAM_TYPE_ACTION_AJOUT,//obligatoire
            'numero'              => (isset($params['numero'])) ? strtoupper($params['numero']) : '',//obligatoire
            'typeNumero'          => $typeNumero,//obligatoire
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



    public function modifierCoordonnéesBancairesAgent(array $params): bool
    {

    }



    public function priseEnChargeAgent($agent)
    {

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