<?php

namespace Formule\Model\Arrondisseur;

use Enseignement\Entity\Db\Service;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleServiceIntervenant;
use Formule\Entity\FormuleVolumeHoraire;
use Referentiel\Entity\Db\ServiceReferentiel;


class Arrondisseur
{

    /** @var array|Calcul[] */
    protected $calculs = [];

    protected string $intervenant;



    public function arrondir(FormuleIntervenant $fi): void
    {
        if ($fi instanceof FormuleServiceIntervenant) {
            $this->intervenant = $fi->getIntervenantId();
        } else {
            $this->intervenant = $fi->getId() ?? "inconnu";
        }

        $this->calculs = [];

        // Optimisation dangeureuse : on doit entrer dans l'arrondisseur même s'il n'est pas utilisé afin de pouvoir déboguer
        //if ($fi::ARRONDISSEUR_NO == $fi->getArrondisseur()) {
        //    return; // Aucun arrondissage à effectuer
        //}

        $data = $this->makeData($fi);


        switch ($fi->getArrondisseur()) {
            case $fi::ARRONDISSEUR_FULL:
                $this->preparerCalculs($data);
                break;
            case $fi::ARRONDISSEUR_MINIMAL:
                $this->preparerCalculsMinimaux($data);
                break;
            case $fi::ARRONDISSEUR_CUSTOM:
                $this->preparerCalculsCustom($data);
                break;
        }

        foreach ($this->calculs as $ci => $calcul) {
            $this->traitement($calcul);
            unset($this->calculs[$ci]);
        }


        // On passe le résultat de l'arrondisseur pour débug éventuel
        $fi->setArrondisseurTrace($data);

        // Et on applique ensuite les résultats arrondis
        $this->transfertResultats($data);
    }



    protected function preparerCalculs(Ligne $data): void
    {
        $this->preparationHorizontaleDescendante($data);
        $this->preparationVerticaleDescendante($data);
    }



    protected function preparerCalculsMinimaux(Ligne $data): void
    {
        $services = $data->getSubs();
        foreach ($services as $service) {
            $this->preparationVerticaleDescendante($service);
        }
    }



    protected function preparerCalculsCustom(Ligne $data): void
    {
        $services = $data->getSubs();
        foreach ($services as $service) {
            $vhs = $service->getSubs();
            foreach ($vhs as $vh) {
                //$this->preparationHorizontaleMontante($vh);
                $this->preparationHorizontaleDescendante($vh);
            }
        }
    }



    protected function preparationHorizontaleMontante(Ligne $data): void
    {
        // Sous-total par catégorie
        foreach (Ligne::CATEGORIES as $categorie) {
            // Sous-sous-total par enseignement FI/FA/FC
            $ceth = $this->addCalcul($data->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT));
            foreach (Ligne::TYPES_ENSEIGNEMENT as $type) {
                $ceth->addValeur($data->getValeur($categorie . $type));
            }

            // calcul par catégorie
            $cc = $this->addCalcul($data->getValeur($categorie));
            $cc->addValeur($data->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT));
            $cc->addValeur($data->getValeur($categorie . Ligne::TYPE_REFERENTIEL));
        }

        /* le total général est recalculé */
        $totalGeneral = $data->getValeur(Ligne::CAT_TYPE_PRIME)->getValueFinale();
        foreach (Ligne::CATEGORIES as $categorie) {
            $totalGeneral += $data->getValeur($categorie)->getValueFinale();
        }
        $data->getValeur(Ligne::TOTAL)->setValue(round($totalGeneral, 2));
    }



    protected function preparationHorizontaleDescendante(Ligne $data): void
    {
        /* le total général est recalculé */
        $ct = $this->addCalcul($data->getValeur(Ligne::TOTAL));
        $ct->addValeur($data->getValeur(Ligne::CAT_TYPE_PRIME));

        // Sous-total par catégorie
        foreach (Ligne::CATEGORIES as $categorie) {
            $ct->addValeur($data->getValeur($categorie));

            $cc = $this->addCalcul($data->getValeur($categorie));
            $cc->addValeur($data->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT));
            $cc->addValeur($data->getValeur($categorie . Ligne::TYPE_REFERENTIEL));

            // Sous-sous-total par enseignement FI/FA/FC
            $ceth = $this->addCalcul($data->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT));
            foreach (Ligne::TYPES_ENSEIGNEMENT as $type) {
                $ceth->addValeur($data->getValeur($categorie . $type));
            }
        }
    }



    protected function preparationVerticaleDescendante(Ligne $data): void
    {
        $subs = $data->getSubs();

        // on fait ruisseler par sous-ligne s'il y en a
        if (empty($subs)) {
            return;
        }

        $vns = [
            Ligne::CAT_TYPE_PRIME,
        ];
        foreach (Ligne::CATEGORIES as $categorie) {
            $vns[] = $categorie . Ligne::TYPE_FI;
            $vns[] = $categorie . Ligne::TYPE_FA;
            $vns[] = $categorie . Ligne::TYPE_FC;
            $vns[] = $categorie . Ligne::TYPE_REFERENTIEL;
        }

        foreach ($vns as $vn) {
            $v      = $data->getValeur($vn);
            $c      = $this->addCalcul($v);
            $noDiff = true;
            foreach ($subs as $sub) {
                $sv = $sub->getValeur($v->getName());
                $c->addValeur($sv);
                if ($sv->getDiff() !== 0) {
                    $noDiff = false;
                }
                $this->preparationVerticaleDescendante($sub);
            }
            if ($noDiff) {
                $v->setDiff(0);
            }
        }
    }



    protected function addCalcul(Valeur $total): Calcul
    {
        $calcul          = new Calcul($total);
        $this->calculs[] = $calcul;

        return $calcul;
    }



    protected function traitement(Calcul $calcul): void
    {
        // Calcul de la somme des arrondis des valeurs
        $sommeArrondis = 0;
        $valeurs       = $calcul->getValeurs();
        foreach ($valeurs as $vk => $valeur) {
            $sommeArrondis += $valeur->getValueFinale();
            if (0 == $valeur->getDiff()) {
                // les valeurs non approximatives n'ont pas à être modifiées
                unset($valeurs[$vk]);
            }
        }

        // Calcul de l'arrondi de la somme
        $arrondiSomme = round($calcul->getTotal()->getValueFinale(), 2);

        // Calcul du différentiel entre la somme des arrondis et l'arrondi de la somme
        // Le diff est un int représentant le nombre de centièmes d'écart
        $diff = (int)(round($arrondiSomme - $sommeArrondis, 2) * 100);

        // Si pas de diff, alors pas besoin de correction
        if (0 == $diff) {
            return; // rien à modifier : la somme est un chiffre rond
        }

        $moins = $diff < 0;
        // Tant que le diff demeure, on boucle pour le résorber
        while ($diff != 0) {
            if ($moins) {
                $diff++;
            } else {
                $diff--;
            }

            if ($moins) {
                // On cherche une valeur pour faire une troncature
                $this->repartirStepMoins($valeurs);
            } else {
                // On cherche une valeur pour forcer un arrondi à l'excès
                $this->repartirStepPlus($valeurs);
            }

        }
    }



    /**
     * @param array|Valeur[] $valeurs
     * @return void
     */
    private function repartirStepPlus(array $valeurs): void
    {
        $maxDiff    = -1000;
        $valToModif = null;

        foreach ($valeurs as $valeur) {
            $vDiff = $valeur->getDiff();
            if ($vDiff > $maxDiff) {
                $maxDiff    = $vDiff;
                $valToModif = $valeur;
            }
        }

        if (!$valToModif) {
            throw new \Exception('Aucune valeur n\'a été trouvée pour arrondir en excès, intervenant ' . $this->intervenant);
        }

        $valToModif->addArrondi(1);

    }



    /**
     * @param array|Valeur[] $valeurs
     * @return void
     */
    private function repartirStepMoins(array $valeurs): void
    {
        $minDiff    = 1000;
        $valToModif = null;

        foreach ($valeurs as $valeur) {
            $vDiff = $valeur->getDiff();
            if ($vDiff < $minDiff) {
                $minDiff    = $vDiff;
                $valToModif = $valeur;
            }
        }

        if (!$valToModif) {
            throw new \Exception('Aucune valeur n\'a été trouvée pour arrondir par troncature, intervenant ' . $this->intervenant);
        }

        $valToModif->addArrondi(-1);
    }



    private function makeData(FormuleIntervenant $fi): Ligne
    {
        $data = new Ligne();
        foreach ($fi->getVolumesHoraires() as $index => $volumeHoraire) {
            $sKey  = $this->makeServiceKey($volumeHoraire);
            $vhKey = (string)$index;

            if (!$data->hasSub($sKey)) {
                $data->addSub($sKey, new Ligne());
            }

            $vhLigne = new Ligne();
            $data->getSub($sKey)->addSub($vhKey, $vhLigne);
            $vhLigne->setVolumeHoraire($volumeHoraire);
        }

        return $data;
    }



    private function transfertResultats(Ligne $data): void
    {
        $subs = $data->getSubs();

        if (!empty($subs)) {
            foreach ($subs as $sub) {
                $this->transfertResultats($sub);
            }
            return;
        }

        $volumeHoraire = $data->getVolumeHoraire();
        if (!$volumeHoraire) {
            throw new \Exception('Aucun volume horaire attaché');
        }

        foreach (Ligne::CATEGORIES as $categorie) {
            foreach (Ligne::TYPES as $type) {
                $value = $data->getValeur($categorie . $type)->getValueFinale();
                $volumeHoraire->{'set' . $categorie . $type}($value);
            }
        }
        $volumeHoraire->setHeuresPrimes($data->getValeur(Ligne::CAT_TYPE_PRIME)->getValueFinale());
    }



    private function makeServiceKey(FormuleVolumeHoraire $volumeHoraire): string
    {
        $service = $volumeHoraire->getService();
        if ($service instanceof Service) {
            return 'e' . $service->getId();
        } elseif ($service) {
            return 'e' . $service;
        }

        $referentiel = $volumeHoraire->getServiceReferentiel();
        if ($referentiel instanceof ServiceReferentiel) {
            return 'r' . $referentiel->getId();
        } elseif ($referentiel) {
            return 'r' . $referentiel;
        }

        return uniqid('u');
    }

}