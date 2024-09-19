<?php

namespace Formule\Model\Arrondisseur;

use Enseignement\Entity\Db\Service;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;
use Referentiel\Entity\Db\ServiceReferentiel;


class Arrondisseur
{

    /** @var array|Calcul[] */
    protected $calculs = [];



    public function arrondir(FormuleIntervenant $fi): void
    {
        $data = $this->makeData($fi);

        $this->preparerCalculs($data);

        foreach ($this->calculs as $ci => $calcul) {
            $this->traitement($calcul);
        }

        // On passe le résultat de l'arrondisseur pour débug éventuel
        $fi->setArrondisseurTrace($data);

        // Et on applique ensuite les résultats arrondis

    }



    protected function preparerCalculs(Ligne $data): void
    {
        // Total toutes catégories
        $c = $this->addCalcul($data->getValeur(Ligne::TOTAL));
        foreach (Ligne::CATEGORIES as $categorie) {
            $c->addValeur($data->getValeur($categorie));
        }
        $c->addValeur($data->getValeur(Ligne::CAT_TYPE_PRIME));


        // Sous-total par catégorie
        foreach (Ligne::CATEGORIES as $categorie) {
            $c = $this->addCalcul($data->getValeur($categorie));
            $c->addValeur($data->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT));
            $c->addValeur($data->getValeur($categorie . Ligne::TYPE_REFERENTIEL));

            // Sous-sous-total par enseignement FI/FA/FC
            $c = $this->addCalcul($data->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT));
            foreach (Ligne::TYPES_ENSEIGNEMENT as $type) {
                $c->addValeur($data->getValeur($categorie . $type));
            }
        }


        // Ensuite, on fait ruisseler par ligne de service, puis par ligne de volume horaire
        $valeurs  = $data->getValeurs();
        $services = $data->getSubs();
        foreach ($valeurs as $v) {
            $cs = $this->addCalcul($v);
            foreach ($services as $service) {
                $sv = $service->getValeur($v->getName());
                $cs->addValeur($sv);

                $cvh = $this->addCalcul($sv);
                $vhs = $service->getSubs();
                foreach ($vhs as $vh) {
                    $cvh->addValeur($vh->getValeur($v->getName()));
                }
            }
        }
    }



    protected function addCalcul(Valeur $total): Calcul
    {
        $calcul          = new Calcul($total);
        $this->calculs[] = $calcul;

        return $calcul;
    }



    /**
     * @param Valeur         $total
     * @param array|Valeur[] $valeurs
     * @return void
     */
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
            // le vDiff doit être <50, sinon l'arrondi par excès est déjà celui par défaut
            // la valeur ne doit pas déjà être arrondie
            if ($valeur->getArrondi() == 0 && $vDiff > $maxDiff && $vDiff < 50) {
                $maxDiff    = $vDiff;
                $valToModif = $valeur;
            }
        }

        if (!$valToModif) {
            throw new \Exception('Aucune valeur n\'a été trouvée pour arrondir en excès');
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
            // le vDiff doit être >=50, sinon l'arrondi par troncature est déjà celui par défaut
            // la valeur ne doit pas déjà être arrondie
            if ($valeur->getArrondi() == 0 && $vDiff < $minDiff && $vDiff >= 50) {
                $minDiff    = $vDiff;
                $valToModif = $valeur;
            }
        }

        if (!$valToModif) {
            throw new \Exception('Aucune valeur n\'a été trouvée pour arrondir par troncature');
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



    private function test(Ligne $ligne)
    {

    }

}