<?php

namespace Formule\Model\Arrondisseur;

class Testeur
{
    public function tester(Ligne $data): int
    {
        $vks    = array_keys($data->getValeurs());
        foreach( $vks as $vki => $vk ){
            if ($vk == Ligne::TOTAL){
                unset($vks[$vki]); // pas de total
            }
        }
        $errors = 0;

        $services = $data->getSubs();
        foreach ($services as $service) {
            $vhs = $service->getSubs();
            foreach ($vhs as $vh) {
                $this->calcControles($vh);

                foreach ($vks as $vk) {
                    if (!$vh->getValeur($vk)->isControleOk()) $errors++;
                }
            }
            foreach ($vks as $vk) {
                if (!$service->getValeur($vk)->isControleOk()) $errors++;
            }
        }
        foreach ($vks as $vk) {
            if (!$data->getValeur($vk)->isControleOk()) $errors++;
        }

        return $errors;
    }



    protected function calcControles(Ligne $volumeHoraire): void
    {
        $total = 0;
        foreach (Ligne::CATEGORIES as $categorie) {
            $cv = 0;
            foreach (Ligne::TYPES_ENSEIGNEMENT as $type) {
                $cv += $volumeHoraire->getValeur($categorie . $type)->getValueFinale();
            }
            $volumeHoraire->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT)->setValue(round($cv, 2));

            $cv += $volumeHoraire->getValeur($categorie . Ligne::TYPE_REFERENTIEL)->getValueFinale();
            $volumeHoraire->getValeur($categorie)->setValue(round($cv, 2));

            $total += $cv;
        }

        $total += $volumeHoraire->getValeur(Ligne::CAT_TYPE_PRIME)->getValueFinale();
        $volumeHoraire->getValeur(Ligne::TOTAL)->setValue($total);

        $this->calculSommesControles($volumeHoraire);
    }



    protected function calculSommesControles(Ligne $volumeHoraire): void
    {
        if (!$volumeHoraire->getSup()) {
            return;
        }

        $valeurs = $volumeHoraire->getValeurs();
        foreach ($valeurs as $vk => $valeur) {
            $valeurSup = $volumeHoraire->getSup()->getValeur($vk);
            $valeurSup->setControle(round($valeurSup->getControle() + $valeur->getValueFinale(), 2));

            $valeurSupSup = $volumeHoraire->getSup()->getSup()->getValeur($vk);
            $valeurSupSup->setControle(round($valeurSupSup->getControle() + $valeur->getValueFinale(), 2));
        }
    }
}