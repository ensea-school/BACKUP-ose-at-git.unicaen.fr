<?php

namespace Application\Traits;

/**
 * Description of ObligatoireSelonSeuilHeuresTrait
 *
 */
trait ObligatoireSelonSeuilHeuresTrait
{
    /**
     * @var boolean
     */
    private $obligatoire;

    /**
     * @var integer
     */
    private $seuilHeures;



    /**
     * Get obligatoire
     *
     * @return boolean
     */
    public function getObligatoire()
    {
        return $this->obligatoire;
    }



    /**
     * Get seuilHeures
     *
     * @return integer
     */
    public function getSeuilHeures()
    {
        return $this->seuilHeures;
    }



    /**
     * Indique si le type de pièce jointe est obligatoire.
     * NB: prend en compte le seuil d'heures réelles éventuellement spécifié.
     *
     * @param integer $totalHeuresReellesIntervenant Total d'heures réelles de l'intervenant, exploité dans le cas où le caractère
     *                                               obligatoire dépend d'un seuil d'heures réelles
     *
     * @return boolean
     */
    public function isObligatoire($totalHeuresReellesIntervenant)
    {
        $obligatoire = $this->getObligatoire();

        if ($obligatoire && $this->getSeuilHeures() && !$this->isSeuilHeuresDepasse($totalHeuresReellesIntervenant)) {
            $obligatoire = false;
        }

        return $obligatoire;
    }



    /**
     * Retourne le libellé du caractère obligatoire ou non du type de pièce jointe.
     * NB: prend en compte le sseuil d'heures réelles éventuellement spécifié.
     *
     * @param integer $totalHeuresReellesIntervenant Total d'heures réelles de l'intervenant, exploité dans le cas où le caractère
     *                                               obligatoire dépend d'un seuil d'heures réelles
     *
     * @return string
     */
    public function getObligatoireToString($totalHeuresReellesIntervenant)
    {
        if ($this->isObligatoire($totalHeuresReellesIntervenant)) {
            $seuilHETD   = $this->getSeuilHeures();
            $obligatoire = "Obligatoire";
            $obligatoire .= $this->isSeuilHeuresDepasse($totalHeuresReellesIntervenant) ?
                " car le <abbr title=\"Total d'heures de service réelles de l'intervenant toutes structures confondues\">total d'heures réelles</abbr> > {$seuilHETD}h" :
                null;

            return $obligatoire;
        }

        return "Facultatif";
    }



    /**
     * Indique si le seuil d'heures réelles est dépassé.
     *
     * @param float $totalHETDIntervenant Total d'heures réelles de l'intervenant à tester
     *
     * @return boolean
     */
    public function isSeuilHeuresDepasse($totalHETDIntervenant)
    {
        if (!$this->getSeuilHeures()) {
            return false;
        }

        return (float)$this->getSeuilHeures() < (float)$totalHETDIntervenant;
    }
}