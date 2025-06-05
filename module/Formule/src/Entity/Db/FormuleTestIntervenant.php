<?php

namespace Formule\Entity\Db;

use Formule\Entity\FormuleIntervenant;
use Formule\Hydrator\FormuleTestIntervenantHydrator;

class FormuleTestIntervenant extends FormuleIntervenant
{
    const TAUX_CM             = 'CM';
    const TAUX_TD             = 'TD';
    const TAUX_TP             = 'TP';
    const STRUCTURE_EXTERIEUR = '__EXTERIEUR__';
    const STRUCTURE_UNIV      = '__UNIV__';

    protected ?string $libelle = null;
    protected ?Formule $formule = null;

    // pas d'arrondisseur sur les tests
    protected int $arrondisseur = FormuleIntervenant::ARRONDISSEUR_NO;

    protected float $tauxCmServiceDu = 1.5;
    protected float $tauxCmServiceCompl = 1.5;
    protected float $tauxTpServiceDu = 1.0;
    protected float $tauxTpServiceCompl = 2 / 3;
    protected ?string $tauxAutre1Code = null;
    protected float $tauxAutre1ServiceDu = 1.0;
    protected float $tauxAutre1ServiceCompl = 1.0;
    protected ?string $tauxAutre2Code = null;
    protected float $tauxAutre2ServiceDu = 1.0;
    protected float $tauxAutre2ServiceCompl = 1.0;
    protected ?string $tauxAutre3Code = null;
    protected float $tauxAutre3ServiceDu = 1.0;
    protected float $tauxAutre3ServiceCompl = 1.0;
    protected ?string $tauxAutre4Code = null;
    protected float $tauxAutre4ServiceDu = 1.0;
    protected float $tauxAutre4ServiceCompl = 1.0;
    protected ?string $tauxAutre5Code = null;
    protected float $tauxAutre5ServiceDu = 1.0;
    protected float $tauxAutre5ServiceCompl = 1.0;
    protected array $debugTrace = [];



    /**
     * @return array|string[]
     */
    public function getStructures(): array
    {
        $structures = [];
        if ($this->getStructureCode()) {
            $structures[$this->getStructureCode()] = $this->getStructureCode();
        }
        foreach ($this->getVolumesHoraires() as $vht) {
            if ($vht->getStructureCode() && $vht->getStructureCode() != self::STRUCTURE_EXTERIEUR) {
                $structures[$vht->getStructureCode()] = $vht->getStructureCode();
            }
        }
        asort($structures);

        return $structures;
    }



    public function getTauxServiceDu(?string $typeInterventionCode): float
    {
        if (self::TAUX_CM == $typeInterventionCode) {
            return $this->getTauxCmServiceDu();
        }
        if (self::TAUX_TD == $typeInterventionCode) {
            return 1.0;
        }
        if (self::TAUX_TP == $typeInterventionCode) {
            return $this->getTauxTpServiceDu();
        }
        for ($i = 1; $i <= 5; $i++) {
            $tauxAutreCode = $this->getTauxAutreCode($i);
            if ($tauxAutreCode == $typeInterventionCode) {
                return $this->getTauxAutreServiceDu($i);
            }
        }

        // Retourne 1 par défaut
        return 1.0;
    }



    public function getTauxServiceCompl(?string $typeInterventionCode): float
    {
        if (self::TAUX_CM == $typeInterventionCode) {
            return $this->getTauxCmServiceCompl();
        }
        if (self::TAUX_TD == $typeInterventionCode) {
            return 1.0;
        }
        if (self::TAUX_TP == $typeInterventionCode) {
            return $this->getTauxTpServiceCompl();
        }
        for ($i = 1; $i <= 5; $i++) {
            $tauxAutreCode = $this->getTauxAutreCode($i);
            if ($tauxAutreCode == $typeInterventionCode) {
                return $this->getTauxAutreServiceCompl($i);
            }
        }

        // Retourne 1 par défaut
        return 1.0;
    }



    public function setTaux(string $typeInterventionCode, float $serviceDu, float $serviceCompl): FormuleTestIntervenant
    {
        if (self::TAUX_CM == $typeInterventionCode) {
            $this->setTauxCmServiceDu($serviceDu);
            $this->setTauxCmServiceCompl($serviceCompl);
        }elseif(self::TAUX_TP == $typeInterventionCode) {
            $this->setTauxTpServiceDu($serviceDu);
            $this->setTauxTpServiceCompl($serviceCompl);
        }elseif(self::TAUX_TD == $typeInterventionCode) {
            if ($serviceDu !== 1.0){
                throw new \Exception('Le taux HETD du TD en service doit être à 1');
            }
            if ($serviceCompl !== 1.0){
                throw new \Exception('Le taux HETD du TD en complémentaire doit être à 1');
            }
            /* Pas de SET pour le TD, car HETD = TD donc toujours 1*/
        }else{
            /* Si on trouve le taux, on le met à jour et on s'en va */
            for( $i = 1; $i <= 5; $i++ ){
                if ($this->getTauxAutreCode($i) == $typeInterventionCode){
                    $this->setTauxAutreServiceDu($i, $serviceDu);
                    $this->setTauxAutreServiceCompl($i, $serviceCompl);
                    return $this;
                }
            }

            /* pas trouvé, alors on l'ajoute et on s'en va */
            for( $i = 1; $i <= 5; $i++ ){
                if (!$this->getTauxAutreCode($i)){
                    $this->setTauxAutreCode($i, $typeInterventionCode);
                    $this->setTauxAutreServiceDu($i, $serviceDu);
                    $this->setTauxAutreServiceCompl($i, $serviceCompl);
                    return $this;
                }
            }

            /* Si le taux n'a pas pu être affecté */
            throw new \Exception('Le type d\'intervention '.$typeInterventionCode.' ne peut pas être pris en charge : il y a trop de types personnalisés');
        }

        return $this;
    }



    public function getTauxAutreServiceDu(int $index): float
    {
        return $this->{"tauxAutre" . $index . "ServiceDu"};
    }



    public function setTauxAutreServiceDu(int $index, float $tauxAutreServiceDu): FormuleTestIntervenant
    {
        $this->{"tauxAutre" . $index . "ServiceDu"} = $tauxAutreServiceDu;

        return $this;
    }



    public function getTauxAutreServiceCompl(int $index): float
    {
        return $this->{"tauxAutre" . $index . "ServiceCompl"};
    }



    public function setTauxAutreServiceCompl(int $index, float $tauxAutreServiceCompl): FormuleTestIntervenant
    {
        $this->{"tauxAutre" . $index . "ServiceCompl"} = $tauxAutreServiceCompl;

        return $this;
    }



    public function getTauxAutreCode(int $index): ?string
    {
        return $this->{"tauxAutre" . $index . "Code"};
    }



    public function setTauxAutreCode(int $index, ?string $tauxAutreCode): FormuleTestIntervenant
    {
        $this->{"tauxAutre" . $index . "Code"} = $tauxAutreCode;
        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }



    /***********************************/
    /* Accésseurs générés par PhpStorm */
    /***********************************/


    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    public function setLibelle(?string $libelle): FormuleTestIntervenant
    {
        $this->libelle = $libelle;
        return $this;
    }



    public function getFormule(): ?Formule
    {
        return $this->formule;
    }



    public function setFormule(?Formule $formule): FormuleTestIntervenant
    {
        $this->formule = $formule;
        return $this;
    }



    public function getTauxCmServiceDu(): float
    {
        return $this->tauxCmServiceDu;
    }



    public function setTauxCmServiceDu(float $tauxCmServiceDu): FormuleTestIntervenant
    {
        $this->tauxCmServiceDu = $tauxCmServiceDu;
        return $this;
    }



    public function getTauxCmServiceCompl(): float
    {
        return $this->tauxCmServiceCompl;
    }



    public function setTauxCmServiceCompl(float $tauxCmServiceCompl): FormuleTestIntervenant
    {
        $this->tauxCmServiceCompl = $tauxCmServiceCompl;
        return $this;
    }



    public function getTauxTpServiceDu(): float
    {
        return $this->tauxTpServiceDu;
    }



    public function setTauxTpServiceDu(float $tauxTpServiceDu): FormuleTestIntervenant
    {
        $this->tauxTpServiceDu = $tauxTpServiceDu;
        return $this;
    }



    public function getTauxTpServiceCompl(): float
    {
        return $this->tauxTpServiceCompl;
    }



    public function setTauxTpServiceCompl(float $tauxTpServiceCompl): FormuleTestIntervenant
    {
        $this->tauxTpServiceCompl = $tauxTpServiceCompl;
        return $this;
    }



    public function getTauxAutre1Code(): ?string
    {
        return $this->tauxAutre1Code;
    }



    public function setTauxAutre1Code(?string $tauxAutre1Code): FormuleTestIntervenant
    {
        $this->tauxAutre1Code = $tauxAutre1Code;
        return $this;
    }



    public function getTauxAutre1ServiceDu(): float
    {
        return $this->tauxAutre1ServiceDu;
    }



    public function setTauxAutre1ServiceDu(float $tauxAutre1ServiceDu): FormuleTestIntervenant
    {
        $this->tauxAutre1ServiceDu = $tauxAutre1ServiceDu;
        return $this;
    }



    public function getTauxAutre1ServiceCompl(): float
    {
        return $this->tauxAutre1ServiceCompl;
    }



    public function setTauxAutre1ServiceCompl(float $tauxAutre1ServiceCompl): FormuleTestIntervenant
    {
        $this->tauxAutre1ServiceCompl = $tauxAutre1ServiceCompl;
        return $this;
    }



    public function getTauxAutre2Code(): ?string
    {
        return $this->tauxAutre2Code;
    }



    public function setTauxAutre2Code(?string $tauxAutre2Code): FormuleTestIntervenant
    {
        $this->tauxAutre2Code = $tauxAutre2Code;
        return $this;
    }



    public function getTauxAutre2ServiceDu(): float
    {
        return $this->tauxAutre2ServiceDu;
    }



    public function setTauxAutre2ServiceDu(float $tauxAutre2ServiceDu): FormuleTestIntervenant
    {
        $this->tauxAutre2ServiceDu = $tauxAutre2ServiceDu;
        return $this;
    }



    public function getTauxAutre2ServiceCompl(): float
    {
        return $this->tauxAutre2ServiceCompl;
    }



    public function setTauxAutre2ServiceCompl(float $tauxAutre2ServiceCompl): FormuleTestIntervenant
    {
        $this->tauxAutre2ServiceCompl = $tauxAutre2ServiceCompl;
        return $this;
    }



    public function getTauxAutre3Code(): ?string
    {
        return $this->tauxAutre3Code;
    }



    public function setTauxAutre3Code(?string $tauxAutre3Code): FormuleTestIntervenant
    {
        $this->tauxAutre3Code = $tauxAutre3Code;
        return $this;
    }



    public function getTauxAutre3ServiceDu(): float
    {
        return $this->tauxAutre3ServiceDu;
    }



    public function setTauxAutre3ServiceDu(float $tauxAutre3ServiceDu): FormuleTestIntervenant
    {
        $this->tauxAutre3ServiceDu = $tauxAutre3ServiceDu;
        return $this;
    }



    public function getTauxAutre3ServiceCompl(): float
    {
        return $this->tauxAutre3ServiceCompl;
    }



    public function setTauxAutre3ServiceCompl(float $tauxAutre3ServiceCompl): FormuleTestIntervenant
    {
        $this->tauxAutre3ServiceCompl = $tauxAutre3ServiceCompl;
        return $this;
    }



    public function getTauxAutre4Code(): ?string
    {
        return $this->tauxAutre4Code;
    }



    public function setTauxAutre4Code(?string $tauxAutre4Code): FormuleTestIntervenant
    {
        $this->tauxAutre4Code = $tauxAutre4Code;
        return $this;
    }



    public function getTauxAutre4ServiceDu(): float
    {
        return $this->tauxAutre4ServiceDu;
    }



    public function setTauxAutre4ServiceDu(float $tauxAutre4ServiceDu): FormuleTestIntervenant
    {
        $this->tauxAutre4ServiceDu = $tauxAutre4ServiceDu;
        return $this;
    }



    public function getTauxAutre4ServiceCompl(): float
    {
        return $this->tauxAutre4ServiceCompl;
    }



    public function setTauxAutre4ServiceCompl(float $tauxAutre4ServiceCompl): FormuleTestIntervenant
    {
        $this->tauxAutre4ServiceCompl = $tauxAutre4ServiceCompl;
        return $this;
    }



    public function getTauxAutre5Code(): ?string
    {
        return $this->tauxAutre5Code;
    }



    public function setTauxAutre5Code(?string $tauxAutre5Code): FormuleTestIntervenant
    {
        $this->tauxAutre5Code = $tauxAutre5Code;
        return $this;
    }



    public function getTauxAutre5ServiceDu(): float
    {
        return $this->tauxAutre5ServiceDu;
    }



    public function setTauxAutre5ServiceDu(float $tauxAutre5ServiceDu): FormuleTestIntervenant
    {
        $this->tauxAutre5ServiceDu = $tauxAutre5ServiceDu;
        return $this;
    }



    public function getTauxAutre5ServiceCompl(): float
    {
        return $this->tauxAutre5ServiceCompl;
    }



    public function setTauxAutre5ServiceCompl(float $tauxAutre5ServiceCompl): FormuleTestIntervenant
    {
        $this->tauxAutre5ServiceCompl = $tauxAutre5ServiceCompl;
        return $this;
    }



    public function getDebugTrace(): array
    {
        return $this->debugTrace;
    }



    public function setDebugTrace(array $debugTrace): FormuleTestIntervenant
    {
        $this->debugTrace = $debugTrace;
        return $this;
    }
}
