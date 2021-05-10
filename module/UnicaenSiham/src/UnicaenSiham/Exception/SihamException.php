<?php

namespace UnicaenSiham\Exception;


class SihamException extends \Exception
{

    protected $errorMessages = [
        "PARAMETRES_VIDES"                       => "Aucun paramétre n'a été passé à l'API SIHAM",
        "is not a valid method for this service" => "La méthode appelée n'est pas disponible via l'API SIHAM",
        "MATRICULE_VIDE"                         => "Aucun paramétre matricule valide n'a été fourni à l'API SIHAM",
        "ERREUR_NATVOI"                          => "La nature de la voie de l'adresse est invalide",
        "DATDEB_ADRESSE_NON_TROUVE"              => "La date de début de l'adresse n'a pas été fourni à l'API",
        "TYPADD_DEJA_PRESENT"                    => "Le type d'adresse pour cette agent existe déjà",
        "ADR_VIDE"                               => "Aucune adresse n'a été renseigné",
        "AJOUT_IMPOSSIBLE"                       => "Ajout de l'adresse impossible",
        "TYPTEL_DEJA_PRESENT"                    => "Un type de numéro de téléphone existe déjà pour cet agent",
    ];



    /**
     * ERREUR_GENERALE
     * AGENT_NON_TROUVE
     * ADRESSE_NON_TROUVEE
     * AJOUT_IMPOSSIBLE
     * TYPE_ACTION_VIDE
     * TYPE_ACTION_ERRONE
     * MATRICULE_VIDE
     * LONG_COMPL_ADRESSE_SUPERIEURE_A_38
     */


    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {

        /*   var_dump($previous);
           die;*/
        //Message par défault dans le cas d'une erreur de l'API
        $defaultMessage = "Un problème est survenu lors de l'appel à l'API SIHAM / " . $message;

        if (!is_null($previous)) {
            $defaultMessage .= " (" . $previous->getMessage() . ")";
        }

        $translate = '';

        foreach ($this->errorMessages as $error => $mess) {
            if (preg_match("/$error/", $message)) {
                $translate = "Un problème est survenu lors de l'appel à l'API SIHAM / " . $mess;
                break;
            }
        }

        $message = (!empty($translate)) ? $translate : $defaultMessage;

        parent::__construct($message, $code, $previous);
    }



    public function __toString()
    {
        return $this->getMessage();
    }

}