<?php

namespace Paiement\Tbl\Process\Sub;

use Application\Entity\Db\Periode;

class Repartiteur
{
    protected string $reglePaiementAnneeCiv = '4-6sur10';

    protected float $pourcS1PourAnneeCivile = 2 / 3;



    public function getReglePaiementAnneeCiv(): string
    {
        return $this->reglePaiementAnneeCiv;
    }



    public function setReglePaiementAnneeCiv(string $reglePaiementAnneeCiv): Repartiteur
    {
        $this->reglePaiementAnneeCiv = $reglePaiementAnneeCiv;

        return $this;
    }



    public function getPourcS1PourAnneeCivile(): float
    {
        return $this->pourcS1PourAnneeCivile;
    }



    public function setPourcS1PourAnneeCivile(float $pourcS1PourAnneeCivile): Repartiteur
    {
        $this->pourcS1PourAnneeCivile = $pourcS1PourAnneeCivile;

        return $this;
    }



    public function fromBdd(array $data): float
    {
        return $this->calculPourcAA(
            semestriel: $data['CALCUL_SEMESTRIEL'] === '1',
            periodeCode: $data['PERIODE_ENS_CODE'],
            anneeId: (int)$data['ANNEE_ID'],
            horaireDebut: (string)$data['HORAIRE_DEBUT'],
            horaireFin: (string)$data['HORAIRE_FIN'],
        );
    }



    public function calculPourcAA(
        bool    $semestriel,
        ?string $periodeCode = null,
        int     $anneeId,
        string  $horaireDebut,
        string  $horaireFin
    ): float
    {
        // On est en mode de calcul semestriel
        if ($semestriel) {
            // si on est sur la règle 4/10 / 6/10
            if ($this->reglePaiementAnneeCiv == '4-6sur10') {
                return 4 / 10;
            }

            // Sinon, on calcule en fonction du semestre
            switch ($periodeCode) {
                case Periode::SEMESTRE_1:
                    return $this->pourcS1PourAnneeCivile;
                case Periode::SEMESTRE_2:
                    return 0; // le S2 n'est jamais effectué l'année antérieure
                default: // si on ne trouve pas la période
                    return 4 / 10;
            }
        }

        // Mode calendaire : en fonction des dates

        $debut = \DateTime::createFromFormat('Y-m-d', substr($horaireDebut, 0, 10));
        $fin = \DateTime::createFromFormat('Y-m-d', substr($horaireFin, 0, 10));


        //   FUNCTION CALC_POURC_AA( periode_id NUMERIC, horaire_debut DATE, horaire_fin DATE, annee_id NUMERIC ) RETURN FLOAT IS
        //    regle_paiement_annee_civ VARCHAR2(50);
        //    nbjaa NUMERIC;
        //    nbjac NUMERIC;
        //  BEGIN
        //    regle_paiement_annee_civ := ose_parametre.get_regle_paiement_annee_civ;
        //
        //    IF regle_paiement_annee_civ = '4-6sur10' THEN
        //      RETURN 4/10;
        //    END IF;
        //
        //    -- Sinon on calcule en fonction du nombre du semestre
        //    IF horaire_debut IS NULL AND horaire_fin IS NULL AND periode_id IS NOT NULL THEN
        //      IF CPA_S1_ID IS NULL THEN
        //        SELECT id INTO CPA_S1_ID FROM periode WHERE code = 'S1';
        //      END IF;
        //
        //      IF periode_id = CPA_S1_ID THEN
        //        RETURN ose_parametre.get_pourc_s1_annee_civ;
        //      ELSE
        //        RETURN 0;
        //      END IF;
        //    END IF;
        //
        //    -- S'il y a des dates, alors on s'appuie dessus
        //    IF horaire_debut IS NOT NULL AND horaire_fin IS NULL THEN
        //      IF to_number(to_char(horaire_debut,'YYYY')) = annee_id THEN
        //        RETURN 1;
        //      ELSE
        //        RETURN 0;
        //      END IF;
        //    END IF;
        //
        //    IF horaire_fin IS NOT NULL AND horaire_debut IS NULL THEN
        //      IF to_number(to_char(horaire_fin,'YYYY')) = annee_id THEN
        //        RETURN 1;
        //      ELSE
        //        RETURN 0;
        //      END IF;
        //    END IF;
        //
        //    IF horaire_fin IS NOT NULL AND horaire_debut IS NOT NULL THEN
        //      IF to_number(to_char(horaire_debut,'YYYY')) = to_number(to_char(horaire_fin,'YYYY')) THEN -- si c'est la même année
        //        IF to_number(to_char(horaire_debut,'YYYY')) = annee_id THEN
        //          RETURN 1;
        //        ELSE
        //          RETURN 0;
        //        END IF;
        //      ELSE
        //        nbjaa := to_date('01/01/' || (annee_id+1), 'dd/mm/YYYY') - horaire_debut;
        //        IF nbjaa < 1 THEN
        //          RETURN 0;
        //        END IF;
        //
        //        nbjac := horaire_fin - to_date('31/12/' || annee_id, 'dd/mm/YYYY');
        //        IF nbjac < 1 THEN
        //          RETURN 1;
        //        END IF;
        //
        //        RETURN nbjaa / (nbjaa + nbjac);
        //      END IF;
        //    END IF;
        //
        //    IF periode_id IS NULL THEN
        //      -- on se trouve dans du référentiel ou dans un enseignement annuel, on utilise le ratio configuré
        //      RETURN ose_parametre.get_pourc_s1_annee_civ;
        //    ELSE
        //      -- Sinon on retourne comme avant, CAD 4/10
        //      RETURN 4/10;
        //    END IF;
        //  END;
    }


}