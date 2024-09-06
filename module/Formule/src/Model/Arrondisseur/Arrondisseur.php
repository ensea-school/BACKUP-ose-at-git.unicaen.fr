<?php

namespace Formule\Model\Arrondisseur;

use Enseignement\Entity\Db\Service;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;
use Referentiel\Entity\Db\ServiceReferentiel;


class Arrondisseur
{
    public function arrondir(FormuleIntervenant $fi): void
    {
        $data = $this->makeData($fi);

        $this->traitementHorizontal($data);
        $this->traitementVertical($data);

        $this->aff($data);
    }



    public function traitementHorizontal(Ligne $data)
    {
        // totaux traités en horizontal
        $total    = $data->getValeur(Ligne::TOTAL);
        $tValeurs = [];
        foreach (Ligne::CATEGORIES as $categorie) {
            $tValeurs[] = $data->getValeur($categorie);
        }
        $this->repartirDiff($total, $tValeurs);

        foreach (Ligne::CATEGORIES as $categorie) {
            $total    = $data->getValeur($categorie);
            $tValeurs = [];
            foreach (Ligne::TYPES as $type) {
                $tValeurs[] = $data->getValeur($categorie . $type);
            }
            $this->repartirDiff($total, $tValeurs);
        }
    }



    public function traitementVertical(Ligne $ligne): void
    {
        // Traitement vertical vers les services
        $subs = $ligne->getSubs();
        foreach (Ligne::CATEGORIES as $categorie) {
            foreach (Ligne::TYPES as $type) {
                $total = $ligne->getValeur($categorie.$type);
                $tValeurs = [];
                foreach ($subs as $sub) {
                    $tValeurs[] = $sub->getValeur($categorie.$type);
                }
                $this->repartirDiff($total, $tValeurs);
            }
        }
    }



    public function traiterTotal(Ligne $data): void
    {
        // $data->

        $totalTarget = round($data->getTotal(), 2);
        //$totalSommeArrondis = round()
    }



    /**
     * @param Valeur         $somme
     * @param array|Valeur[] $valeurs
     * @return void
     */
    protected function repartirDiff(Valeur $somme, array $valeurs): void
    {
        if ($somme->getValue() == 0.0) {
            return; // rien à répartir : on est à 0
        }

        $target = round($somme->getValue(), 2);
        $calc   = round($somme->getSommeArrondis(), 2);
        if ($target == $calc) {
            return; // rien à faire
        }
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



    private function aff(Ligne $ligne)
    {
        echo '<table class="table table-bordered table-xs">';
        echo '<tr>';
        echo '<th></th><th>Total</th>';
        foreach (Ligne::CATEGORIES as $categorie) {
            echo '<th>' . $categorie . '</th>';
            foreach (Ligne::TYPES as $type) {
                echo '<th>' . $categorie . $type . '</th>';
            }
        }
        echo '</tr>';

        $this->affLigne($ligne);
        echo '<tr><td colspan="13">&nbsp;</td></tr>';


        foreach ($ligne->getSubs() as $sk => $sl) {
            $this->affLigne($sl, $sk);
            foreach ($sl->getSubs() as $vk => $vl) {
                $this->affLigne($vl, $vk);
            }
            echo '<tr><td colspan="13">&nbsp;</td></tr>';
        }


        echo '</table>';
    }



    protected function affLigne(Ligne $ligne, ?string $name = null): void
    {
        echo '<tr>';
        echo '<th>' . $name . '</th>';
        echo '<td>';
        $this->affValeur($ligne->getValeur(Ligne::TOTAL));
        echo '</td>';
        foreach (Ligne::CATEGORIES as $categorie) {
            echo '<td>';
            $this->affValeur($ligne->getValeur($categorie));
            echo '</td>';
            foreach (Ligne::TYPES as $type) {
                echo '<td>';
                $this->affValeur($ligne->getValeur($categorie . $type));
                echo '</td>';
            }
        }
        echo '</tr>';
    }



    protected function affValeur(Valeur $valeur): void
    {
        echo $valeur->getValueFinale() . ' ';

        if ($valeur->getValue() !== $valeur->getValueFinale()) {
            echo '<span style="color:gray">' . round($valeur->getValue(), 4) . '</span> ';
        }
        if ($valeur->getSommeArrondis() !== round($valeur->getValue(), 2)) {
            echo '<span style="color:red">' . round($valeur->getSommeArrondis(), 4) . '</span> ';
        }
        if (0.0 !== $valeur->getDiff()) {
            echo '<span style="color:green">' . $valeur->getDiff() . '</span> ';
        }
    }
}