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

    protected $sihamClient;



    public function __construct(SihamClient $sihamClient)
    {
        $this->sihamClient = $sihamClient;
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


    public function recupDonneesPersonnellesAgent($params)
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



    public function ajouteAdresseAgent(Agent $agent)
    {
        return $agent;
    }



    /**
     * @param $params       array Paramètres du webservice : bisTer, codePostal, complementAdresse,dateDebut, matricule,
     *                      natureVoie, noVoie, nomVoie, ville
     *
     * @return Boolean
     */

    public function modificationAdresseAgent(array $params): bool
    {
        $agents = [];

        $paramsWS = ['ParamModifDP' => [
            'bisTer'            => (isset($params['bisTer'])) ? strtoupper($params['bisTer']) : '',
            'codePostal'        => (isset($params['codePostal'])) ? strtoupper($params['codePostal']) : '',
            'complementAdresse' => (isset($params['complementAdresse'])) ? strtoupper($params['complementAdresse']) : '',
            'dateDebut'         => (isset($params['dateDebut'])) ? strtoupper($params['dateDebut']) : '',//obligatoire
            'matricule'         => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'natureVoie'        => (isset($params['natureVoie'])) ? strtoupper($params['natureVoie']) : '',
            'noVoie'            => (isset($params['noVoie'])) ? strtoupper($params['noVoie']) : '',
            'nomVoie'           => (isset($params['nomVoie'])) ? strtoupper($params['nomVoie']) : '',
            'typeAction'        => self::SIHAM_TYPE_ACTION_MODIFICATION,//obligatoire
            'typeAdrPers'       => self::SIHAM_CODE_TYPOLOGIE_ADRESSE_PRINCIPALE,//obligatoire
            'ville'             => (isset($params['ville'])) ? strtoupper($params['ville']) : '',
            //'typeNumero'        => (isset($params['typeNumero'])) ? $params['typeNumero'] : '',
            //'numero'            => $agent->getNu,
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
    }



    public function priseEnChargeAgent($agent)
    {

    }



    public function modifCoordonneesBancaires()
    {

    }

}