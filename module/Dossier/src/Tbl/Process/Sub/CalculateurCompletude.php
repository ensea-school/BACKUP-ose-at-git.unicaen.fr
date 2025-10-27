<?php

namespace Dossier\Tbl\Process\Sub;

use Intervenant\Entity\Db\Statut;

class CalculateurCompletude
{

    private const DOSSIER_BLOCS = [
        'identite',
        'statut',
        'identite_comp',
        'contact',
        'adresse',
        'insee',
        'banque',
        'employeur',
    ];

    private const REALISEE = 'realisee';
    private const ATTENDUE = 'attendue';



    public function calculer(array $dossier, array &$destination): void
    {
        $destination['completude_statut']                  = isset($dossier['dossier_id']) && $this->completudeStatut($dossier);
        $destination['completude_identite']                = isset($dossier['dossier_id']) && $this->completudeIdentite($dossier);
        $destination['completude_identite_comp']           = isset($dossier['dossier_id']) && $this->completudeIdentiteComplementaire($dossier);
        $destination['completude_contact']                 = isset($dossier['dossier_id']) && $this->completudeContact($dossier);
        $destination['completude_adresse']                 = isset($dossier['dossier_id']) && $this->completudeAdresse($dossier);
        $destination['completude_banque']                  = isset($dossier['dossier_id']) && $this->completudeBanque($dossier);
        $destination['completude_insee']                   = isset($dossier['dossier_id']) && $this->completudeInsee($dossier);
        $destination['completude_employeur']               = isset($dossier['dossier_id']) && $this->completudeEmployeur($dossier);
        $destination['completude_autre_avant_recrutement'] = isset($dossier['dossier_id']) && $this->completudeAutreAvantRecrutement($dossier);
        $destination['completude_autre_apres_recrutement'] = isset($dossier['dossier_id']) && $this->completudeAutreApresRecrutement($dossier);
        $destination['apres_recrutement_attendue']         = isset($dossier['dossier_id']) ? $this->calculerCompletude($dossier, $destination, 2, self::ATTENDUE) : false;
        $destination['avant_recrutement_attendue']         = isset($dossier['dossier_id']) ? $this->calculerCompletude($dossier, $destination, 1, self::ATTENDUE) : false;
        $destination['apres_recrutement_realisee']         = isset($dossier['dossier_id']) ? $this->calculerCompletude($dossier, $destination, 2, self::REALISEE) : false;
        $destination['avant_recrutement_realisee']         = isset($dossier['dossier_id']) ? $this->calculerCompletude($dossier, $destination, 1, self::REALISEE) : false;
    }



    /**
     * Méthode qui retour si la partie statut est compléte
     * @param array $dossier
     * @return bool
     */
    public function completudeStatut(array $dossier): bool
    {
        // 1. Si le statut est autre, le statut n'a pas été correctement choisi
        if ($dossier['code_statut'] == Statut::CODE_AUTRES) {
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
        $champsObligatoires = ['civilite_id',
                               'nom_usuel',
                               'prenom',
                               'date_naissance'];
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
        if ($dossier['dossier_identite_comp']) {
            // 1. Complétude de la situation matrimoniale
            if (!empty($dossier['dossier_situation_matrimoniale'])) {
                $code = $dossier['situation_matrimoniale_code'] ?? null;
                // Si ce n’est pas un célibataire, on vérifie la présence du code et de la date
                if ($code !== Statut::CODE_SITUATION_MATRIMONIALE_CELIBATAIRE &&
                    (!$code || empty($dossier['situation_matrimoniale_date']))) {
                    return false;
                }
            }

            // 2. Champs obligatoires : pays naissance, nationalité, commune naissance
            $champsObligatoires = ['pays_naissance_id',
                                   'pays_nationalite_id',
                                   'commune_naissance'];
            foreach ($champsObligatoires as $champs) {
                if (empty($dossier[$champs])) {
                    return false;
                }
            }

            // 3. Département naissance requis si pays = France (insensible à la casse)
            if (isset($dossier['libelle_pays_naissance']) &&
                strcasecmp($dossier['libelle_pays_naissance'], 'france') === 0 &&
                empty($dossier['departement_naissance_id'])
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
        
        if ($dossier['dossier_contact']) {
            // 1. Vérification des emails
            if ($dossier['dossier_email_perso'] && empty($dossier['email_perso'])) {
                return false;
            } elseif (empty($dossier['email_pro']) && empty($dossier['email_perso'])) {
                return false;
            }

            // 2. Vérification des téléphones
            if ($dossier['dossier_tel_perso'] && empty($dossier['tel_perso'])) {
                return false;
            } elseif (empty($dossier['tel_pro'])) {
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
        if ($dossier['dossier_adresse']) {
            // 1. Test de l'adresse
            if (empty($dossier['adresse_precisions']) &&
                empty($dossier['adresse_lieu_dit']) &&
                (empty($dossier['adresse_voie']) && empty($dossier['adresse_numero']))) {
                return false;
            }

            // 2. Test de la commune et du code postal
            if (empty($dossier['adresse_commune']) || empty($dossier['adresse_code_postal'])) {
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
        if ($dossier['dossier_banque']) {
            if (empty($dossier['iban']) || empty($dossier['bic'])) {
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
        if ($dossier['dossier_insee']) {
            if (empty($dossier['numero_insee'])) {
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
        if ($dossier['dossier_employeur']) {
            // 1. Test si l'employeur est renseigné uniquement s'il est obligatoire
            if (!$dossier['dossier_employeur_facultatif'] && empty($dossier['employeur_id'])) {
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
        if ($dossier['dossier_autre_' . $numero]) {
            if ($dossier['dossier_autre_' . $numero . '_obligatoire'] && empty($dossier['autre_' . $numero])) {
                return false;
            }
        }
        return true;
    }



    public function completudeAutreAvantRecrutement(array $dossier): bool
    {
        for ($i = 1; $i < 6; $i++) {
            if ($dossier['dossier_autre_' . $i . '_obligatoire']
                && empty($dossier['autre_' . $i])
                && $dossier['dossier_autre_' . $i] == 1
            ) {
                return false;
            }
        }

        return true;

    }



    public function completudeAutreApresRecrutement(array $dossier): bool
    {
        for ($i = 1; $i < 6; $i++) {
            if ($dossier['dossier_autre_' . $i . '_obligatoire']
                && empty($dossier['autre_' . $i])
                && $dossier['dossier_autre_' . $i] == 2
            ) {
                return false;
            }
        }

        return true;

    }



    /**
     * Méthode qui calcule la complétude avant ou aprés recurtement
     *
     * @param array $dossier     Données du dossier
     * @param array $destination Données de destination
     * @param int   $etat        1 = avant, 2 = après
     * @param bool  $type        realisee ou attendue
     */
    private function calculerCompletude(array $dossier, array $destination, int $etat, string $type): int
    {
        $completude = 0;

        foreach (self::DOSSIER_BLOCS as $value) {
            $keyDossier = 'dossier_' . $value;

            if (!array_key_exists($keyDossier, $dossier)) {
                continue;
            }

            if ($dossier[$keyDossier] != $etat) {
                continue;
            }

            if ($type === self::REALISEE) {
                $keyDestination = 'completude_' . $value;
                if (!empty($destination[$keyDestination])) {
                    $completude++;
                }
            } else {
                $completude++;
            }
        }

        //Gestion des champs autres
        for ($i = 1; $i < 6; $i++) {
            if ($dossier['dossier_autre_' . $i] != $etat) {
                continue;
            }
            //Champs autre non obligatoire on passe
            if (empty($dossier['dossier_autre_' . $i . '_obligatoire'])) {
                $completude++;
                continue;
            }

            if ($type === self::REALISEE) {
                if (!empty($dossier['autre_' . $i])) {
                    $completude++;
                }
            } else {
                $completude++;
            }
        }
        return $completude;
    }


}
