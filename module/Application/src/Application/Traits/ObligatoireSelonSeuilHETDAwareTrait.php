<?php

namespace Application\Traits;

/**
 * Description of TypeAgrementAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait ObligatoireSelonSeuilHETDAwareTrait
{
    /**
     * @var boolean
     */
    private $obligatoire;

    /**
     * @var integer
     */
    private $seuilHetd;
    
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
     * Get seuilHetd
     *
     * @return integer 
     */
    public function getSeuilHetd()
    {
        return $this->seuilHetd;
    }
    
    /**
     * Indique si le type de pièce jointe est obligatoire.
     * NB: prend en compte le seil HETD éventuellement spécifié.
     * 
     * @param integer $totalHETDIntervenant Total d'HETD de l'intervenant, exploité dans le cas où le caractère
     * obligatoire dépend d'un seuil d'HETD
     * @return boolean
     */
    public function isObligatoire($totalHETDIntervenant)
    {
        $obligatoire = $this->getObligatoire();
        
        if ($obligatoire && $this->getSeuilHetd() && !$this->isSeuilHETDDepasse($totalHETDIntervenant)) {
            $obligatoire = false;
        }
        
        return $obligatoire;
    }
    
    /**
     * Retourne le libellé du caractère obligatoire ou non du type de pièce jointe.
     * NB: prend en compte le seil HETD éventuellement spécifié.
     * 
     * @param integer $totalHETDIntervenant Total d'HETD de l'intervenant, exploité dans le cas où le caractère
     * obligatoire dépend d'un seuil d'HETD
     * @return string
     */
    public function getObligatoireToString($totalHETDIntervenant)
    {
        if ($this->isObligatoire($totalHETDIntervenant)) {
            $seuilHETD   = $this->getSeuilHetd();
            $obligatoire = "Obligatoire";
            $obligatoire .= $this->isSeuilHETDDepasse($totalHETDIntervenant) ? 
                    " car <abbr title=\"Total d'heures de service réelles de l'intervenant toutes structures confondues\">total HETD</abbr> > {$seuilHETD}h" : 
                    null;
            return $obligatoire;
        }
        
//        if ($this->getType()->getCode() === TypeAgrement::RIB) {
//            return "En cas de changement";
//        }
        
        return "Facultatif";
    }
    
    /**
     * Indique si le seuil d'HETD est dépassé.
     * 
     * @param float $totalHETDIntervenant Total d'HETD de l'intervenant à tester
     * @return boolean
     */
    public function isSeuilHETDDepasse($totalHETDIntervenant)
    {
        if (!$this->getSeuilHetd()) {
            return false;
        }
        
        return (float)$this->getSeuilHetd() < (float)$totalHETDIntervenant;
    }
}