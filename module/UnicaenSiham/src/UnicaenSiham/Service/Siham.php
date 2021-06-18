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
            'codeEtablissement' => (isset($params['codeEtablissement'])) ? $params['codeEtablissement'] : '',
            'nomUsuel'          => (isset($params['nomUsuel'])) ? strtoupper($params['nomUsuel']) : '',
            'nomPatronymique'   => (isset($params['nomPatronymique'])) ? strtoupper($params['nomPatronymique']) : '',
            'prenom'            => (isset($params['prenom'])) ? strtoupper($params['prenom']) : '',
            'dateNaissance'     => (isset($params['dateNaissance'])) ? strtoupper($params['dateNaissance']) : '',
            'codeNIRSsCle'      => (isset($params['codeNIRSsCle'])) ? strtoupper($params['codeNIRSsCle']) : '',
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
            'codeEtablissement'      => (isset($params['codeEtablissement'])) ? $params['codeEtablissement'] : '',
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



    /**
     * @param $params       array Paramètres du webservice : bisTer, codePostal, complementAdresse,dateDebut, matricule,
     *                      natureVoie, noVoie, nomVoie, ville
     *
     * @return Boolean
     */

    public function modifierAdressePrincipaleAgent(array $params): bool
    {
        $dateDebut = new \DateTime();

        $paramsWS = ['ParamModifDP' => [
            'codeEtablissement' => (isset($params['codeEtablissement'])) ? strtoupper($params['codeEtablissement']) : '',
            'typeAction'        => (!empty($params['dateDebut'])) ? self::SIHAM_TYPE_ACTION_MODIFICATION : self::SIHAM_TYPE_ACTION_AJOUT,//obligatoire
            'typeAdrPers'       => self::SIHAM_CODE_TYPOLOGIE_ADRESSE_PRINCIPALE,
            'matricule'         => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'bisTer'            => (isset($params['bisTer'])) ? strtoupper($params['bisTer']) : '',
            'natureVoie'        => (isset($params['natureVoie'])) ? strtoupper($params['natureVoie']) : '',
            'noVoie'            => (isset($params['noVoie'])) ? strtoupper($params['noVoie']) : '',
            'nomVoie'           => (isset($params['nomVoie'])) ? strtoupper($params['nomVoie']) : '',
            'codePostal'        => (isset($params['codePostal'])) ? strtoupper($params['codePostal']) : '',
            'complementAdresse' => (isset($params['complementAdresse'])) ? strtoupper($params['complementAdresse']) : '',
            'ville'             => (isset($params['ville'])) ? strtoupper($params['ville']) : '',
            'codePays'          => (isset($params['codePays'])) ? strtoupper($params['codePays']) : '',
            'dateDebut'         => (!empty($params['dateDebut'])) ? $params['dateDebut'] : $dateDebut->format('Y-m-d'),
            'dateFin'           => '',
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



    public function modifierCoordonneesAgent(array $params, $type = null): bool
    {
        if (empty($type)) {
            throw new SihamException("Vous devez préciser le type de coordonnées à mettre à jour : TPR, TPE etc...");
        }
        
        $dateDebut = new \DateTime();

        $paramsWS = ['ParamModifDP' => [
            'codeEtablissement'   => (isset($params['codeEtablissement'])) ? strtoupper($params['codeEtablissement']) : '',
            'codeUOAffectAdresse' => '',//Obligatoire sinon le WS plante...
            'typeAction'          => (!empty($params['dateDebut'])) ? self::SIHAM_TYPE_ACTION_MODIFICATION : self::SIHAM_TYPE_ACTION_AJOUT,//obligatoire
            'dateDebut'           => (!empty($params['dateDebut'])) ? $params['dateDebut'] : $dateDebut->format('Y-m-d'),
            'dateFin'             => '',//obligatoire,
            'matricule'           => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'numero'              => (isset($params['numero'])) ? strtoupper($params['numero']) : '',
            'typeNumero'          => $type,
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



    public function historiserCoordonneesAgent(array $params, $type = self::SIHAM_CODE_TYPOLOGIE_NUMERO_FIXE_PROFESSIONNEL): bool
    {
        $dateFin = new \DateTime();

        $paramsWS = ['ParamModifDP' => [
            'codeEtablissement' => (isset($params['codeEtablissement'])) ? strtoupper($params['codeEtablissement']) : '',
            'dateFin'           => $dateFin->format('Y-m-d'),//obligatoire,
            'matricule'         => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',//obligatoire
            'typeAction'        => self::SIHAM_TYPE_ACTION_SUPPRESSION,//obligatoire
            'typeNumero'        => $type,
        ]];

        try {
            $client = $this->sihamClient->getClient('DossierAgentWebService');
            $agent  = $this->recupererDonneesPersonnellesAgent(['listeMatricules' => [$params['matricule']]]);
            if ($agent) {
                switch ($type) {
                    case Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO:
                        $paramsWS['ParamModifDP']['dateDebut'] = $agent->getTelephoneProDateDebut();
                    break;

                    case Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO:
                        $paramsWS['ParamModifDP']['dateDebut'] = $agent->getTelephonePersoDateDebut();
                    break;

                    case Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PERSO:
                        $paramsWS['ParamModifDP']['dateDebut'] = $agent->getEmailPersoDateDebut();
                    break;

                    case Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO:
                        $paramsWS['ParamModifDP']['dateDebut'] = $agent->getEmailProDateDebut();
                    break;
                }
            } else {
                throw new SihamException('Agent non trouvé dans SIHAM', 0);
            }

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



    public function modifierCoordonnéesBancairesAgent(array $params): bool
    {
        $paramsWS = ['ParamMCB' => [
            'cleCompte'      => (isset($params['cleCompte'])) ? strtoupper($params['cleCompte']) : '',
            'codeAgence'     => (isset($params['codeAgence'])) ? strtoupper($params['codeAgence']) : '',
            'codeBanque'     => (isset($params['codeBanque'])) ? strtoupper($params['codeBanque']) : '',
            'dateDebBanque'  => (isset($params['dateDebBanque'])) ? strtoupper($params['dateDebBanque']) : '',
            'dateFinBanque'  => (isset($params['dateFinBanque'])) ? strtoupper($params['dateFinBanque']) : '',
            'IBAN'           => (isset($params['IBAN'])) ? strtoupper($params['IBAN']) : '',
            'libelleAgence'  => (isset($params['libelleAgence'])) ? strtoupper($params['libelleAgence']) : '',
            'matricule'      => (isset($params['matricule'])) ? strtoupper($params['matricule']) : '',
            'modePaiement'   => (isset($params['modePaiement'])) ? strtoupper($params['modePaiement']) : '',
            'numCompte'      => (isset($params['numCompte'])) ? strtoupper($params['numCompte']) : '',
            'paysBanque'     => (isset($params['paysBanque'])) ? strtoupper($params['paysBanque']) : '',
            'SWIFT'          => (isset($params['SWIFT'])) ? strtoupper($params['SWIFT']) : '',
            'temoinValidite' => (isset($params['temoinValidite'])) ? strtoupper($params['temoinValidite']) : '',
        ]];

        try {
            //On récupére l'agent pour pouvoir le modifier
            $client = $this->sihamClient->getClient('DossierAgentWebService');
            //$result->ModifCoordonneesBancaires($paramsWS);
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }
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
            // var_dump($result->return);
            //var_dump($this->sihamClient->getLastRequest());
            //die;
            if ($result->return->statut == 'ERREUR_GENERALE') {
                $messageErreur  = '';
                $messageWarning = '';
                if (!empty($result->return->listeAnomaliesWebServices)) {
                    if (is_array($result->return->listeAnomaliesWebServices)) {
                        foreach ($result->return->listeAnomaliesWebServices as $anomalie) {
                            if (isset($anomalie->anomalie)) {
                                if (strpos($anomalie->anomalie, 'Erreur') === 0) {
                                    $messageErreur .= $anomalie->anomalie . '<br/>';
                                }
                            }
                        }
                    } else {
                        if (isset($result->return->listeAnomaliesWebServices->anomalie)) {
                            if (strpos($result->return->listeAnomaliesWebServices->anomalie, 'Erreur') === 0) {
                                $messageErreur .= $result->return->listeAnomaliesWebServices->anomalie . '<br/>';
                            }
                        }
                    }
                }
                //Traitement du message d'erreur spécifique à la PEC
                throw new SihamException($messageErreur, 0);
            } elseif ($result->return->statut == 'MAJ OK' && !empty($result->return->matricule)) {
                return $result->return->matricule;
            } else {
                throw new SihamException('Erreur non identifié, veuillez vous rapprocher du support informatique', 0);
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }
    }



    /**
     * @param $params       array Paramètres du webservice : codeRepertoire
     *
     * @return array
     */

    public
    function recupererNomenclatureRH(array $params)
    {
        $listeNomenclatures = [];

        foreach ($params['listeNomenclatures'] as $nomenclature) {
            $listeNomenclatures[] = ['codeRepertoire' => $nomenclature];
        }

        $paramsWS = ['ParamNomenclature' => [
            'codeAdministration' => (isset($params['codeAdministration'])) ? strtoupper($params['codeAdministration']) : '',
            'dateObservation'    => (isset($params['dateObservation'])) ? $params['dateObservation'] : date("Y-m-d"),
            'listeNomenclatures' => $listeNomenclatures,
        ]];


        try {
            $client = $this->sihamClient->getClient('DossierParametrageWebService');
            $result = $client->RecupNomenclaturesRH($paramsWS);

            if (isset($result->return)) {
                $nomenclature = [];
                //traitement pour classer alpha sur le libelle
                foreach ($result->return as $value) {
                    $nomenclature[$value->codeNomenclature] = $value->libLongNomenclature;
                }
                asort($nomenclature);

                return $nomenclature;
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }
    }



    public
    function recupererListeUO(array $params)
    {
        //Traitement des listes unité organisationnelle
        $listeUO = [];
        if (!empty($params['listeUO'])) {
            foreach ($params['listeUO'] as $uo) {
                $listeUO[] = [
                    'codeUO'      => (isset($uo['codeUO'])) ? strtoupper($uo['codeUO']) : '',
                    'structureUO' => (isset($uo['structureUO'])) ? strtoupper($uo['structureUO']) : '',
                    'typeUO'      => (isset($uo['typeUO'])) ? strtoupper($uo['typeUO']) : '',
                ];
            }
        } else {
            $listeUO[] = [
                'codeUO'      => '',
                'structureUO' => '',
                'typeUO'      => '',
            ];
        }


        $paramsWS = ['ParamStructure' => [
            'codeAdministration' => (isset($params['codeAdministration'])) ? strtoupper($params['codeAdministration']) : '',
            'dateObservation'    => (isset($params['dateObservation'])) ? $params['dateObservation'] : '',
            'listeUO'            => $listeUO,
        ]];


        try {
            $client = $this->sihamClient->getClient('DossierParametrageWebService');
            $result = $client->RecupStructures($paramsWS);

            if (isset($result->return)) {
                return $result->return;
            }
        } catch (\SoapFault $e) {
            throw new SihamException($e->faultstring, 0, $e);
        }
    }



    public
    function ibanToSiham($iban)
    {
        //On récupére l'iban et on doit le décompser pour récupérer les informations nécessaire à SIHAM
        $iban       = 'FR7615589297430269316674054';
        $payBanque  = substr($iban, 0, 2);
        $cleCompte  = substr($iban, 2, 2);
        $codeBanque = substr($iban, 4, 5);
        $codeAgence = substr($iban, 9, 5);
        $reste      = substr($iban, 14);
        $numCompte  = substr($reste, 0, strlen($reste) - 2);
    }
}