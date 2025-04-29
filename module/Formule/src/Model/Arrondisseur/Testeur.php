<?php

namespace Formule\Model\Arrondisseur;

use Formule\Entity\FormuleIntervenant;

class Testeur
{
    public bool $depassementServiceDuSansHC = false;
    public int  $arrondisseurMode           = 0;



    public function tester(FormuleIntervenant $fi): int
    {
        $this->depassementServiceDuSansHC = $fi->isDepassementServiceDuSansHC();
        $this->arrondisseurMode           = $fi->getArrondisseur();

        $data = $fi->getArrondisseurTrace();

        // première passe
        $this->controleColonnes($data);

        // on recalcule les totaux
        $this->recalculTotaux($data);

        // comptage d'erreurs en seconde passe
        $errors = $this->controleColonnes($data);

        $serviceDuCalcule = $data->getValeur(Ligne::CAT_SERVICE)->getValueFinale();

        if ($serviceDuCalcule != $fi->getServiceDu() && $this->arrondisseurMode == FormuleIntervenant::ARRONDISSEUR_FULL) {
            $errors++;
            $data->getValeur(Ligne::CAT_SERVICE)->addError(
                'Le service calculé (' . $serviceDuCalcule . ') ne correspond pas au service dû (' . $fi->getServiceDu() . ')'
            );
        }

        return $errors;
    }



    protected function controleColonnes(Ligne $ligne): int
    {
        $errors = 0;

        $valeurs = $ligne->getValeurs();
        $subs    = $ligne->getSubs();

        $totaux  = [];
        $calculs = [];
        foreach ($valeurs as $valeur) {
            $vn           = $valeur->getName();
            $totaux[$vn]  = $valeur->getValueFinale();
            $calculs[$vn] = 0.0;
        }

        if ($ligne->getSup()) {
            $errors += $this->controleLigne($ligne);
        } else {
            if ($this->arrondisseurMode == FormuleIntervenant::ARRONDISSEUR_FULL) {
                $errors += $this->controleLigne($ligne);
            }
        }

        if (empty($subs)) {
            return $errors;
        }

        foreach ($subs as $sub) {
            foreach ($valeurs as $valeur) {
                $vn           = $valeur->getName();
                $calculs[$vn] += $sub->getValeur($vn)->getValueFinale();
            }
            $errors += $this->controleColonnes($sub);
        }

        foreach ($valeurs as $valeur) {
            $vn = $valeur->getName();
            if (round($calculs[$vn], 2) != $totaux[$vn]) {
                if ($this->arrondisseurMode == FormuleIntervenant::ARRONDISSEUR_FULL || $ligne->getSup()) {
                    $errors++;
                    $valeur->addError('Somme des sous-valeurs de colonne = ' . $calculs[$vn] . " contre " . $totaux[$vn] . " attendu");
                }
            }
        }

        return $errors;
    }



    protected function controleLigne(Ligne $ligne): int
    {
        $errors = 0;

        $total  = $ligne->getValeur(Ligne::TOTAL)->getValueFinale();
        $sTotal = $ligne->getValeur(Ligne::CAT_TYPE_PRIME)->getValueFinale();

        foreach (Ligne::CATEGORIES as $categorie) {
            $sTotal += $ligne->getValeur($categorie)->getValueFinale();

            $catTotal  = $ligne->getValeur($categorie)->getValueFinale();
            $catSTotal = 0.0;

            $catSTotal += $ligne->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT)->getValueFinale();
            $catSTotal += $ligne->getValeur($categorie . Ligne::TYPE_REFERENTIEL)->getValueFinale();
            $catSTotal = round($catSTotal, 2);

            if ($catTotal != $catSTotal) {
                $errors++;
                $ligne->getValeur($categorie)->addError('Total Enseignement + référentiel = ' . $catSTotal . " contre " . $catTotal . " attendu");
            }


            $catEnsTotal  = $ligne->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT)->getValueFinale();
            $casEnsFi     = $ligne->getValeur($categorie . Ligne::TYPE_FI)->getValueFinale();
            $casEnsFa     = $ligne->getValeur($categorie . Ligne::TYPE_FA)->getValueFinale();
            $casEnsFc     = $ligne->getValeur($categorie . Ligne::TYPE_FC)->getValueFinale();
            $catEnsSTotal = round($casEnsFi + $casEnsFa + $casEnsFc, 2);

            if ($catEnsTotal != $catEnsSTotal) {
                $errors++;
                $ligne->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT)->addError('Total FI + FA + FC = ' . $catEnsSTotal . " contre " . $catEnsTotal . " attendu");
            }

        }
        $sTotal = round($sTotal, 2);
        // ajouter les primes au sous-total ???

        if ($total != $sTotal) {
            $errors++;
            $ligne->getValeur(Ligne::TOTAL)->addError('Total Service + HC + Non payable = ' . $sTotal . " contre " . $total . " attendu");
        }

        $errors += $this->controleTotauxVh($ligne);

        return $errors;
    }



    protected function controleTotauxVh(Ligne $ligne): int
    {
        if (!$ligne->getVolumeHoraire()) {
            return 0;
        }

        $erreurs = 0;

        $total    = $ligne->getValeur(Ligne::TOTAL);
        $vh       = $ligne->getVolumeHoraire();
        $pService = $vh->getTauxServiceDu() * $vh->getPonderationServiceDu();
        $pCompl   = $vh->getTauxServiceCompl() * $vh->getPonderationServiceCompl();

        $ponderation = null;

        if (null === $ponderation && $pService == $pCompl){
            $ponderation = $pCompl;
        }

        if (null === $ponderation && $total->getValueFinale() == $ligne->getValeur(Ligne::CAT_SERVICE)->getValueFinale()) {
            $ponderation = $pService;
        }

        if (null === $ponderation && $ligne->getValeur(Ligne::CAT_SERVICE)->getValueFinale() == 0) {
            $ponderation = $pCompl;
        }

        if ($ponderation === null) {
            return 0; // Calcul HETD impossible à refaire en ponderations multiples
        }

        $hetd   = round($vh->getHeures() * $ponderation, 2);
        $sTotal = $total->getValueFinale();


        if ($this->depassementServiceDuSansHC && $sTotal > $hetd && $ponderation == 0.67) {
            // Hack pour contourner pb tableur ?
            $hetd = round($vh->getHeures() * 1, 2);
        }

        if ($sTotal != $hetd) {
            $totalService = $ligne->getSup()->getValeur(Ligne::TOTAL)->getValueFinale();
            if ($sTotal == 0 && $totalService == 0) {
                $hetd = 0; // on passe le HETD à 0, car on est sur une compensation au niveau service (+128 - 128) => 0 HETD dans les 2 cas
            }
        }


        if ($this->depassementServiceDuSansHC) {
            if ($vh->getHeures() > 0 && $sTotal > $hetd) {
                $erreurs++;
                $total->addError('Le total HETD est trop important par rapport au nombre d\'heures saisies (' . $hetd . ')');
            }
            if ($vh->getHeures() < 0 && $sTotal < $hetd) {
                $erreurs++;
                $total->addError('Le total HETD est trop petit par rapport au nombre d\'heures saisies (' . $hetd . ')');
            }
        } else {
            if ($sTotal != $hetd) {
                $erreurs++;
                $total->addError('Le total HETD ne correspond pas au nombre d\'hetd calculées (' . $hetd . ')');
            }
        }

        return $erreurs;
    }



    protected function recalculTotaux(Ligne $ligne)
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

        $this->recalculTotauxHorizontaux($ligne);
        foreach ($vns as $vn) {
            $iVal = $ligne->getValeur($vn);
            $iVal->setValue(0);
            $iVal->resetErrors();
            foreach ($ligne->getSubs() as $service) {
                $this->recalculTotauxHorizontaux($service);
                $sVal = $service->getValeur($vn);
                $sVal->setValue(0);
                $sVal->resetErrors();
                foreach ($service->getSubs() as $vh) {
                    $this->recalculTotauxHorizontaux($vh);
                    $vhValue = $vh->getValeur($vn)->getValueFinale();
                    $iVal->setValue($iVal->getValue() + $vhValue);
                    $sVal->setValue($sVal->getValue() + $vhValue);
                }
            }
        }
    }



    protected function recalculTotauxHorizontaux(Ligne $ligne)
    {
        $total = $ligne->getValeur(Ligne::CAT_TYPE_PRIME)->getValueFinale();
        foreach (Ligne::CATEGORIES as $categorie) {
            // ens = fi + fa + fc
            $ens = $ligne->getValeur($categorie . Ligne::TYPE_FI)->getValueFinale();
            $ens += $ligne->getValeur($categorie . Ligne::TYPE_FA)->getValueFinale();
            $ens += $ligne->getValeur($categorie . Ligne::TYPE_FC)->getValueFinale();
            $ens = round($ens, 2);
            $ligne->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT)->setValue($ens);
            $ligne->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT)->resetErrors();


            //total categorie = ens + referentiel
            $cat = $ligne->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT)->getValueFinale();
            $cat += $ligne->getValeur($categorie . Ligne::TYPE_REFERENTIEL)->getValueFinale();
            $ligne->getValeur($categorie)->setValue($cat);
            $ligne->getValeur($categorie)->resetErrors();

            $total += $cat;
        }
        $ligne->getValeur(Ligne::TOTAL)->setValue($total);
        $ligne->getValeur(Ligne::TOTAL)->resetErrors();
    }
}