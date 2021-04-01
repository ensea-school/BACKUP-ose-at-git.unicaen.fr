<?php

namespace UnicaenSiham\Exception;

use Throwable;

class SihamException extends \Exception
{
    const PARAM_VIDE          = 'PARAMETRES_VIDES';
    const INVALID_METHOD      = 'is not a valid method for this service';
    const MATRICULE_VIDE      = 'MATRICULE_VIDE';
    const INVALID_NATURE_VOIE = 'ERREUR_NATVOI';



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


    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {

        //Message par défault dans le cas d'une erreur de l'API
        $defaultMessage = "Un problème est survenu lors de l'appel à l'API SIHAM";
        if (method_exists($previous, 'getMessage')) {
            $defaultMessage .= " (" . $previous->getMessage() . ")";
        }
        $translate = '';

        //Personnalisation du message selon le code erreur
        if (preg_match("/" . self::INVALID_METHOD . "/", $message)) {
            $translate = "La méthode appelée n'est pas disponible via l'API SIHAM";
        }

        if (preg_match("/" . self::PARAM_VIDE . "/", $message)) {
            $translate = "Aucun paramétre n'a été passé à l'API SIHAM";
        }

        if (preg_match("/" . self::MATRICULE_VIDE . "/", $message)) {
            $translate = "Aucun paramétre matricule valide n'a été fourni à l'API SIHAM";
        }

        if (preg_match("/" . self::INVALID_NATURE_VOIE . "/", $message)) {
            $translate = "La nature de la voie de l'adresse est invalide";
        }

        $message = (!empty($translate)) ? $translate : $defaultMessage;


        parent::__construct($message, $code, $previous);
    }



    public function __toString()
    {
        return $this->getMessage();
    }

}