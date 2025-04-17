<?php

namespace Dossier\Tbl\Process\Sub;

use Intervenant\Entity\Db\Statut;

class CalculateurCompletude
{

    public function calculer(array $dossier, array &$destination): void
    {
        $destination['COMPLETUDE_STATUT']            = isset($dossier['DOSSIER_ID']) ? $this->completudeStatut($dossier) : false;
        $destination['COMPLETUDE_IDENTITE']          = isset($dossier['DOSSIER_ID']) ? $this->completudeIdentite($dossier) : false;
        $destination['COMPLETUDE_IDENTITE_COMP']     = isset($dossier['DOSSIER_ID']) ? $this->completudeIdentiteComplementaire($dossier) : false;
        $destination['COMPLETUDE_CONTACT']           = isset($dossier['DOSSIER_ID']) ? $this->completudeContact($dossier) : false;
        $destination['COMPLETUDE_ADRESSE']           = isset($dossier['DOSSIER_ID']) ? $this->completudeAdresse($dossier) : false;
        $destination['COMPLETUDE_BANQUE']            = isset($dossier['DOSSIER_ID']) ? $this->completudeBanque($dossier) : false;
        $destination['COMPLETUDE_INSEE']             = isset($dossier['DOSSIER_ID']) ? $this->completudeInsee($dossier) : false;
        $destination['COMPLETUDE_EMPLOYEUR']         = isset($dossier['DOSSIER_ID']) ? $this->completudeEmployeur($dossier) : false;
        $destination['COMPLETUDE_AUTRE_1']           = isset($dossier['DOSSIER_ID']) ? $this->completudeChampsAutres($dossier, 1) : false;
        $destination['COMPLETUDE_AUTRE_2']           = isset($dossier['DOSSIER_ID']) ? $this->completudeChampsAutres($dossier, 2) : false;
        $destination['COMPLETUDE_AUTRE_3']           = isset($dossier['DOSSIER_ID']) ? $this->completudeChampsAutres($dossier, 3) : false;
        $destination['COMPLETUDE_AUTRE_4']           = isset($dossier['DOSSIER_ID']) ? $this->completudeChampsAutres($dossier, 4) : false;
        $destination['COMPLETUDE_AUTRE_5']           = isset($dossier['DOSSIER_ID']) ? $this->completudeChampsAutres($dossier, 5) : false;
        $destination['COMPLETUDE_AVANT_RECRUTEMENT'] = isset($dossier['DOSSIER_ID']) ? $this->completudeAvantRecrutement($dossier, $destination) : false;
        $destination['COMPLETUDE_APRES_RECRUTEMENT'] = isset($dossier['DOSSIER_ID']) ? $this->completudeApresRecrutement($dossier, $destination) : false;


    }



    /**
     * Méthode qui retour si la partie statut est compléte
     * @param array $dossier
     * @return bool
     */
    public function completudeStatut(array $dossier): bool
    {
        // 1. Si le statut est autre, le statut n'a pas été correctement choisi
        if ($dossier['CODE_STATUT'] == Statut::CODE_AUTRES) {
            return false;
        }

        return true;
    }



    /**
     * Méthode qui retour si la partie identité du dossier est complète
     * @param array $dossier
     * @return bool
     */
    public function completudeIdentite(array $dossier): bool
    {
        // 1. Champs obligatoires : civilite, nom_usuel, prénom et date de naissance
        $champsObligatoires = ['CIVILITE_ID', 'NOM_USUEL', 'PRENOM', 'DATE_NAISSANCE'];
        foreach ($champsObligatoires as $champs) {
            if (empty($dossier[$champs])) {
                return false;
            }
        }

        return true;
    }



    /**
     * Méthode qui retour si la partie identité du dossier est complète
     * @param array $dossier
     * @return bool
     */
    public function completudeIdentiteComplementaire(array $dossier): bool
    {
        //Uniquement si la partie données identités complémentaires est activée
        if ($dossier['DOSSIER_IDENTITE_COMP']) {
            // 1. Complétude de la situation matrimoniale
            if (!empty($dossier['DOSSIER_SITUATION_MATRIMONIALE'])) {
                $code = $dossier['SITUATION_MATRIMONIALE_CODE'] ?? null;
                // Si ce n’est pas un célibataire, on vérifie la présence du code et de la date
                if ($code !== Statut::CODE_SITUATION_MATRIMONIALE_CELIBATAIRE &&
                    (!$code || empty($dossier['SITUATION_MATRIMONIALE_DATE']))) {
                    return false;
                }
            }

            // 2. Champs obligatoires : pays naissance, nationalité, commune naissance
            $champsObligatoires = ['PAYS_NAISSANCE_ID', 'PAYS_NATIONALITE_ID', 'COMMUNE_NAISSANCE'];
            foreach ($champsObligatoires as $champs) {
                if (empty($dossier[$champs])) {
                    return false;
                }
            }

            // 3. Département naissance requis si pays = France (insensible à la casse)
            if (isset($dossier['LIBELLE_PAYS_NAISSANCE']) &&
                strcasecmp($dossier['LIBELLE_PAYS_NAISSANCE'], 'france') === 0 &&
                empty($dossier['DEPARTEMENT_NAISSANCE_ID'])
            ) {
                return false;
            }
        }


        return true;
    }



    /**
     * Méthode qui retour si la partie contact du dossier est complète
     * @param array $dossier
     * @return bool
     */
    public function completudeContact(array $dossier): bool
    {
        $var = "";
        if ($dossier['DOSSIER_CONTACT']) {
            // 1. Vérification des emails
            if ($dossier['DOSSIER_EMAIL_PERSO'] && empty($dossier['EMAIL_PERSO'])) {
                return false;
            } elseif (empty($dossier['EMAIL_PRO']) && empty($dossier['EMAIL_PERSO'])) {
                return false;
            }

            // 2. Vérification des téléphones
            if ($dossier['DOSSIER_TEL_PERSO'] && empty($dossier['TEL_PERSO'])) {
                return false;
            } elseif (empty($dossier['TEL_PRO'])) {
                return false;
            }

        }
        return true;

    }



    /**
     * Méthode qui retour si la partie adresse du dossier est complète
     * @param array $dossier
     * @return bool
     */
    public function completudeAdresse(array $dossier): bool
    {
        if ($dossier['DOSSIER_ADRESSE']) {
            // 1. Test de l'adresse
            if (empty($dossier['ADRESSE_PRECISIONS']) &&
                empty($dossier['ADRESSE_LIEU_DIT']) &&
                (empty($dossier['ADRESSE_VOIE']) && empty($dossier['ADRESSE_NUMERO']))) {
                return false;
            }

            // 2. Test de la commune et du code postal
            if (empty($dossier['ADRESSE_COMMUNE']) || empty($dossier['ADRESSE_CODE_POSTAL'])) {
                return false;
            }
        }
        return true;
    }



    /**
     * Méthode qui test si la partie banque du dossier est complète
     * @param array $dossier
     * @return bool
     */
    public function completudeBanque(array $dossier): bool
    {
        if ($dossier['DOSSIER_BANQUE']) {
            if (empty($dossier['IBAN']) || empty($dossier['BIC'])) {
                return false;
            }
        }

        return true;

    }



    /**
     * Méthode qui test si la partie INSEE du dossier est complète
     * @param array $dossier
     * @return bool
     */
    public function completudeInsee(array $dossier): bool
    {
        if ($dossier['DOSSIER_INSEE']) {
            if (empty($dossier['NUMERO_INSEE'])) {
                return false;
            }
        }

        return true;

    }



    /**
     * Méthode qui test si la partie Employeur du dossier est complète
     * @param array $dossier
     * @return bool
     */
    public function completudeEmployeur(array $dossier): bool
    {
        if ($dossier['DOSSIER_EMPLOYEUR']) {
            // 1. Test si l'employeur est renseigné uniquement s'il est obligatoire
            if (!$dossier['DOSSIER_EMPLOYEUR_FACULTATIF'] && empty($dossier['EMPLOYEUR_ID'])) {
                return false;
            }
        }

        return true;

    }



    /**
     * Méthode qui test la complétude des champs autres
     * @param array $dossier
     * @return bool
     */
    public function completudeChampsAutres(array $dossier, int $numero): bool
    {
        if ($dossier['DOSSIER_AUTRE_' . $numero]) {
            if ($dossier['DOSSIER_AUTRE_' . $numero . '_OBLIGATOIRE'] && empty($dossier['AUTRE_' . $numero])) {
                return false;
            }
        }
        return true;
    }



    public function completudeAvantRecrutement(array $dossier, array $destination): bool
    {
        $dossierCompletudes = [
            'IDENTITE_COMP',
            'CONTACT',
            'ADRESSE',
            'INSEE',
            'BANQUE',
            'EMPLOYEUR',
            'AUTRE_1',
            'AUTRE_2',
            'AUTRE_3',
            'AUTRE_4',
            'AUTRE_5',
        ];

        // 1. Vérification de la complétude du bloc identite
        if (!$destination['COMPLETUDE_IDENTITE'] || !$destination['COMPLETUDE_STATUT']) {
            return false;
        }

        // 2. Vérification de la complétude concernée avant le recrutement
        foreach ($dossierCompletudes as $value) {
            if (array_key_exists('DOSSIER_'.$value, $dossier)) {
                if ($dossier['DOSSIER_' . $value] == 1) {
                    if (!$destination['COMPLETUDE_' . $value]) {
                        return false;
                    }
                }
            }
        }

        return true;
    }



    public function completudeApresRecrutement(array $dossier, array $destination): bool
    {
        $dossierCompletudes = [
            'IDENTITE_COMP',
            'CONTACT',
            'ADRESSE',
            'INSEE',
            'BANQUE',
            'EMPLOYEUR',
            'AUTRE_1',
            'AUTRE_2',
            'AUTRE_3',
            'AUTRE_4',
            'AUTRE_5',
        ];

        // 1. Vérification de la complétude concernée après le recrutement
        foreach ($dossierCompletudes as $value) {
            if (array_key_exists('DOSSIER_'.$value, $dossier)) {
                if ($dossier['DOSSIER_' . $value] == 2) {
                    if (!$destination['COMPLETUDE_' . $value]) {
                        return false;
                    }
                }
            }
        }

        return true;
    }






}
