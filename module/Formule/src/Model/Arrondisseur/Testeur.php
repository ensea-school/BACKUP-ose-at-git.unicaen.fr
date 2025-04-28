<?php

namespace Formule\Model\Arrondisseur;

class Testeur
{
    public function tester(Ligne $data): int
    {
        $errors = 0;

        $this->remonteeTotaux($data);

        $services = $data->getSubs();
        foreach ($services as $service) {
            $this->calcSommesLigne($service);
            $vhs = $service->getSubs();
            foreach ($vhs as $vh) {
                $this->calcSommesLigne($vh);
                $this->controleColonnes($vh);
                $errors += $this->countErrorsLigne($vh);
            }
            $errors += $this->countErrorsLigne($service);
        }

        $errors += $this->countErrorsLigne($data);

        return $errors;
    }



    protected function countErrorsLigne(Ligne $data): int
    {
        $errors = 0;
        foreach ($data->getValeurs() as $valeur) {
            if (!$valeur->isControleOk()) $errors++;
        }
        return $errors;
    }



    protected function controleColonnes(Ligne $ligne): void
    {
        if ($ligne->getVolumeHoraire()){
            $vh = $ligne->getVolumeHoraire();
            $pService = $vh->getTauxServiceDu() * $vh->getPonderationServiceDu();
            $pCompl = $vh->getTauxServiceCompl() * $vh->getPonderationServiceCompl();

            if ($ligne->getValeur(Ligne::CAT_SERVICE)->getValue() != 0 && $ligne->getValeur(Ligne::CAT_COMPL)->getValue() == 0 && $ligne->getValeur(Ligne::CAT_TYPE_PRIME)->getValue() == 0){
                $ligne->getValeur(Ligne::TOTAL)->setControle($vh->getHeures() * $pService);
            }
            if ($ligne->getValeur(Ligne::CAT_SERVICE)->getValue() == 0 && $ligne->getValeur(Ligne::CAT_COMPL)->getValue() != 0 && $ligne->getValeur(Ligne::CAT_TYPE_PRIME)->getValue() == 0){
                $ligne->getValeur(Ligne::TOTAL)->setControle($vh->getHeures() * $pCompl);
            }
            if ($ligne->getValeur(Ligne::CAT_SERVICE)->getValue() == 0 && $ligne->getValeur(Ligne::CAT_COMPL)->getValue() == 0 && $ligne->getValeur(Ligne::CAT_TYPE_PRIME)->getValue() != 0){
                $ligne->getValeur(Ligne::TOTAL)->setControle($vh->getHeuresPrimes() * $pCompl);
            }
            if (abs($pService-$pCompl) < 0.001){ // taux service = taux compl
                $ligne->getValeur(Ligne::TOTAL)->setControle($vh->getHeures() * $pCompl);
            }
        }
        foreach( $ligne->getValeurs() as $valeur ){
            $value = $valeur->getValueFinale();

            $sValeur = $ligne->getSup()->getValeur($valeur->getName());
            $sValeur->setControle(round($sValeur->getControle() + $value,2));

            $tValeur = $sValeur->getLigne()->getSup()->getValeur($valeur->getName());
            $tValeur->setControle(round($tValeur->getControle() + $value,2));
        }
    }



    protected function controleSommesLigne(Ligne $ligne): void
    {
        $sommeTotale = $ligne->getValeur(Ligne::CAT_TYPE_PRIME)->getValueFinale();

        foreach (Ligne::CATEGORIES as $categorie) {
            $somme =
                $ligne->getValeur($categorie . Ligne::TYPE_FI)->getValueFinale()
                + $ligne->getValeur($categorie . Ligne::TYPE_FA)->getValueFinale()
                + $ligne->getValeur($categorie . Ligne::TYPE_FC)->getValueFinale();

            $somme = round($somme, 2);

            $ligne->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT)->setControle($somme);

            $somme += $ligne->getValeur($categorie . Ligne::TYPE_REFERENTIEL)->getValueFinale();
            $somme = round($somme, 2);

            $ligne->getValeur($categorie)->setControle($somme);

            $sommeTotale += $somme;
        }

        $sommeTotale = round($sommeTotale, 2);
        $ligne->getValeur(Ligne::TOTAL)->setControle($sommeTotale);
    }



    protected function calcSommesLigne(Ligne $ligne): void
    {
        $sommeTotale = $ligne->getValeur(Ligne::CAT_TYPE_PRIME)->getValueFinale();

        foreach (Ligne::CATEGORIES as $categorie) {
            $somme =
                $ligne->getValeur($categorie . Ligne::TYPE_FI)->getValueFinale()
                + $ligne->getValeur($categorie . Ligne::TYPE_FA)->getValueFinale()
                + $ligne->getValeur($categorie . Ligne::TYPE_FC)->getValueFinale();

            $somme = round($somme, 2);

            $ligne->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT)->setValue($somme);

            $somme += $ligne->getValeur($categorie . Ligne::TYPE_REFERENTIEL)->getValueFinale();
            $somme = round($somme, 2);

            $ligne->getValeur($categorie)->setValue($somme);

            $sommeTotale += $somme;
        }

        $sommeTotale = round($sommeTotale, 2);
        $ligne->getValeur(Ligne::TOTAL)->setValue($sommeTotale);
    }



    protected function remonteeTotaux(Ligne $ligne)
    {
        $vns = [
            Ligne::CAT_TYPE_PRIME,
            Ligne::TOTAL,
        ];
        foreach (Ligne::CATEGORIES as $categorie) {
            $vns[] = $categorie;
            $vns[] = $categorie . Ligne::TYPE_FI;
            $vns[] = $categorie . Ligne::TYPE_FA;
            $vns[] = $categorie . Ligne::TYPE_FC;
            $vns[] = $categorie . Ligne::TYPE_REFERENTIEL;
        }

        foreach( $vns as $vn ) {
            $iVal = $ligne->getValeur($vn);
            $iVal->setValue(0);
            foreach ($ligne->getSubs() as $service) {
                $sVal = $service->getValeur($vn);
                $sVal->setValue(0);
                foreach ($service->getSubs() as $vh) {
                    $vhValue = $vh->getValeur($vn)->getValueFinale();
                    $iVal->setValue($iVal->getValue() + $vhValue);
                    $sVal->setValue($sVal->getValue() + $vhValue);
                }
            }
        }
    }
}