<?php

namespace Paiement\Tbl\Process\Sub;

use Application\Entity\Db\Periode;

class Repartiteur
{
    // Répartition 4/10 des heures pour l'année antérieure, 6/10 pour l'année en cours
    const PAIEMENT_ANNEE_CIV_4_10_6_10 = '4-6sur10';
    // En fonction du semestre des heures ou de la date des cours
    const PAIEMENT_ANNEE_CIV_SEMESTRE_DATE = 'semestre-date';


    protected string $reglePaiementAnneeCiv = self::PAIEMENT_ANNEE_CIV_4_10_6_10;

    protected float $pourcS1PourAnneeCivile = 2 / 3;

    protected float $pourAAReferentiel = 0.4;


    public function getReglePaiementAnneeCiv(): string
    {
        return $this->reglePaiementAnneeCiv;
    }



    public function setReglePaiementAnneeCiv(string $reglePaiementAnneeCiv): Repartiteur
    {
        $regles = [self::PAIEMENT_ANNEE_CIV_4_10_6_10, self::PAIEMENT_ANNEE_CIV_SEMESTRE_DATE];
        if (!in_array($reglePaiementAnneeCiv, $regles)) {
            throw new \Exception('Règle inconnue');
        }

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



    public function getPourAAReferentiel(): float
    {
        return $this->pourAAReferentiel;
    }



    public function setPourAAReferentiel(float $pourAAReferentiel): Repartiteur
    {
        $this->pourAAReferentiel = $pourAAReferentiel;

        return $this;
    }



    public function fromBdd(array $data): float
    {
        return $this->calculPourcAA(
            semestriel: $data['calcul_semestriel'] === '1',
            periodeCode: $data['periode_ens_code'],
            anneeId: (int)$data['annee_id'],
            horaireDebut: (string)$data['horaire_debut'],
            horaireFin: (string)$data['horaire_fin'],
        );
    }



    /**
     * Retourne un taux AA/AC
     *
     * @param bool $semestriel Si on est en mode semestriel ou non
     * @param string|null $periodeCode Code de la période, pour détecter si on est en S1 ou S2
     * @param int $anneeId ID de l'année universitaire
     * @param string $horaireDebut Horaire de début au format Y-m-d
     * @param string $horaireFin Horaire de fin au format Y-m-d
     */
    public function calculPourcAA(
        bool    $semestriel,
        ?string $periodeCode,
        int     $anneeId,
        string  $horaireDebut,
        string  $horaireFin
    ): float
    {
        // On est en mode de calcul semestriel
        if ($semestriel) {
            // si on est sur la règle 4/10 / 6/10
            if ($this->reglePaiementAnneeCiv == self::PAIEMENT_ANNEE_CIV_4_10_6_10) {
                return 4 / 10;
            }

            // Sinon, on calcule en fonction du semestre
            return match ($periodeCode) {
                Periode::SEMESTRE_1 => $this->pourcS1PourAnneeCivile,
                Periode::SEMESTRE_2 => 0,
                default => $this->pourAAReferentiel,
            };
        }

        $debut = \DateTime::createFromFormat('Y-m-d', substr($horaireDebut, 0, 10));
        $fin = \DateTime::createFromFormat('Y-m-d', substr($horaireFin, 0, 10));

        // ça se termine avant ou bien en AA
        if ((int)$fin->format('Y') <= $anneeId){
            return 1;
        }

        // ça commence après ou en AC
        if ((int)$debut->format('Y') > $anneeId){
            return 0;
        }

        $endOfYear = \DateTime::createFromFormat('Y-m-d H:i:s', ($anneeId+1).'-01-01 00:00:00');

        $intervalAA = $endOfYear->diff($debut);
        $joursAA = $intervalAA->days+1;

        $intervalAC = $endOfYear->diff($fin);
        $joursAC = $intervalAC->days+1;

        $pourcAA = round($joursAA / ($joursAA + $joursAC), 2);

        return $pourcAA;
    }



    public function repartir(ServiceAPayer $sap)
    {
        foreach( $sap->lignesAPayer as $lap ){
            $this->repartirLap($lap);
        }
    }



    public function repartirLap(LigneAPayer $lap)
    {
        $heures = $lap->heuresAA + $lap->heuresAC;

        $lap->heuresAA = (int)round($heures * $lap->pourcAA);
        $lap->heuresAC = $heures - $lap->heuresAA;
    }
}