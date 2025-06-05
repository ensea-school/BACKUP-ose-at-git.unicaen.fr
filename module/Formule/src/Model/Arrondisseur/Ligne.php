<?php

namespace Formule\Model\Arrondisseur;

use Formule\Entity\FormuleVolumeHoraire;

class Ligne
{
    const TOTAL = 'Total';

    const TYPE_ENSEIGNEMENT = 'Enseignement';
    const TYPE_FI           = 'Fi';
    const TYPE_FA           = 'Fa';
    const TYPE_FC           = 'Fc';
    const TYPE_REFERENTIEL  = 'Referentiel';

    const CAT_SERVICE     = 'HeuresService';
    const CAT_COMPL       = 'HeuresCompl';
    const CAT_NON_PAYABLE = 'HeuresNonPayable';

    const CAT_TYPE_PRIME = 'HeuresPrimes';

    const TYPES_ENSEIGNEMENT = [self::TYPE_FI, self::TYPE_FA, self::TYPE_FC];
    const TYPES              = [self::TYPE_FI, self::TYPE_FA, self::TYPE_FC, self::TYPE_REFERENTIEL];
    const CATEGORIES         = [self::CAT_SERVICE, self::CAT_COMPL, self::CAT_NON_PAYABLE];


    protected ?Ligne $sup = null;

    /** @var array|Ligne[] */
    protected array $sub = [];

    /** @var array|Valeur[] */
    protected array $valeurs = [];

    protected ?FormuleVolumeHoraire $volumeHoraire = null;



    public function __construct()
    {
        // Total général
        $this->valeurs[self::TOTAL] = new Valeur($this, self::TOTAL);

        // Tout ce qui est payable
        foreach (self::CATEGORIES as $categorie) {
            if ($categorie == self::CAT_NON_PAYABLE) {
                continue;
            }
            $this->initCategorie($categorie);
        }
        $this->valeurs[self::CAT_TYPE_PRIME] = new Valeur($this, self::CAT_TYPE_PRIME);

        // On finit avec le non payable
        $this->initCategorie(self::CAT_NON_PAYABLE);
    }



    public function getValeursUtilisees(): array
    {
        $valeurs = array_fill_keys(array_keys($this->getValeurs()), false);

        foreach ($valeurs as $vn => $null) {
            if ($this->getValeur($vn)->getValue() != 0.0) {
                $valeurs[$vn] = true;
            }
        }
        foreach ($this->getSubs() as $sub) {
            $subValeurs = $sub->getValeursUtilisees();
            foreach ($subValeurs as $vn) {
                $valeurs[$vn] = true;
            }
        }


        $hasCat = [];
        foreach (self::CATEGORIES as $categorie) {
            $count = 0;

            foreach (self::TYPES_ENSEIGNEMENT as $tens) {
                if ($valeurs[$categorie . $tens]) {
                    $count++;
                    $hasCat[$categorie] = true;
                }
            }
            if ($count < 2) {
                $valeurs[$categorie . self::TYPE_ENSEIGNEMENT] = false;
            }

            if ($valeurs[$categorie . self::TYPE_REFERENTIEL]) {
                $hasCat[$categorie] = true;
            }

            if (!($count > 0 && $valeurs[$categorie . self::TYPE_REFERENTIEL])) {
                $valeurs[$categorie] = false;
            }
        }
        if ($valeurs[self::CAT_TYPE_PRIME]) {
            $hasCat[Ligne::CAT_TYPE_PRIME] = true;
        }

        if (count($hasCat) < 2) {
            $valeurs[self::TOTAL] = false;
        }


        $res = [];
        foreach ($valeurs as $vn => $vu) {
            if ($vu) {
                $res[] = $vn;
            }
        }

        return $res;
    }



    private function initCategorie(string $categorie): void
    {
        $this->valeurs[$categorie]                           = new Valeur($this, $categorie);
        $this->valeurs[$categorie . self::TYPE_ENSEIGNEMENT] = new Valeur($this, $categorie . self::TYPE_ENSEIGNEMENT);
        foreach (self::TYPES as $type) {
            $this->valeurs[$categorie . $type] = new Valeur($this, $categorie . $type);
        }
    }



    public function hasSub(string $key): bool
    {
        return array_key_exists($key, $this->sub);
    }



    /**
     * @return array|Ligne[]
     */
    public function getSubs(): array
    {
        return $this->sub;
    }



    public function getSub(string $key): Ligne
    {
        return $this->sub[$key];
    }



    public function addSub(string $key, Ligne $subLigne): void
    {
        $this->sub[$key] = $subLigne;
        $subLigne->sup   = $this;
    }



    public function getSup(): ?Ligne
    {
        return $this->sup;
    }



    public function setVolumeHoraire(FormuleVolumeHoraire|array $volumeHoraire): void
    {
        $this->volumeHoraire = $volumeHoraire;
        $vc                  = array_fill_keys(self::CATEGORIES, 0);
        $vEns                = array_fill_keys(self::CATEGORIES, 0);
        $vt                  = array_fill_keys(self::TYPES, 0);
        $t                   = 0;
        foreach (self::CATEGORIES as $categorie) {
            foreach (self::TYPES as $type) {
                if ($volumeHoraire instanceof FormuleVolumeHoraire) {
                    $value = $volumeHoraire->{'get' . $categorie . $type}();
                } else {
                    $value = $volumeHoraire[$categorie . $type] ?? 0.0;
                }

                $this->addHeures($categorie . $type, $value);

                $vc[$categorie] += $value;
                if (in_array($type, self::TYPES_ENSEIGNEMENT)) {
                    $vEns[$categorie] += $value;
                }
                $vt[$type] += $value;
                $t         += $value;
            }
            $this->addHeures($categorie, $vc[$categorie]);
            $this->addHeures($categorie . self::TYPE_ENSEIGNEMENT, $vEns[$categorie]);
        }
        $this->addHeures(self::CAT_TYPE_PRIME, $volumeHoraire->getHeuresPrimes());
        $t += $volumeHoraire->getHeuresPrimes();

        $this->addHeures(self::TOTAL, $t);
    }



    public function getVolumeHoraire(): ?FormuleVolumeHoraire
    {
        return $this->volumeHoraire;
    }



    public function getValeur(string $catType): Valeur
    {
        if (!array_key_exists($catType, $this->valeurs)) {
            throw new \Exception('La valeur "' . $catType . '" n\'existe pas');
        }
        return $this->valeurs[$catType];
    }



    /**
     * @return array|Valeur[]
     */
    public function getValeurs(): array
    {
        return $this->valeurs;
    }



    public function addHeures(string $catType, float $value): void
    {
        $valeur = $this->valeurs[$catType];

        $valeur->setValue($valeur->getValue() + $value);

        if ($this->sup) {
            $this->sup->addHeures($catType, $value);
        }
    }

}
