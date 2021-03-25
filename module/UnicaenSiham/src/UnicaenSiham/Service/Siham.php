<?php

namespace UnicaenSiham\Service;

use UnicaenSiham\Entity\Agent;
use UnicaenSiham\Exception\SihamException;

class Siham
{

    protected $sihamClient;



    public function __construct(SihamClient $sihamClient)
    {
        $this->sihamClient = $sihamClient;
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
        $listAgents = [];

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
                $listAgents = $result->return;
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }

        $agents = [];

        foreach ($listAgents as $values) {
            $agent    = new Agent();
            $agent    = $agent->mapper($values);
            $agents[] = $agent;
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
            $agent  = $client->RecupDonneesPersonnelles($paramsWS);
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }

        return $agent;
    }



    public function modifDonneesPersonnellesAgent($agent)
    {

    }



    public function priseEnChargeAgent($agent)
    {

    }



    public function modifCoordonneesBancaires()
    {

    }

}