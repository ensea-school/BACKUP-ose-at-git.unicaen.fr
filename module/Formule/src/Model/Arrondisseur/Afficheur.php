<?php

namespace Formule\Model\Arrondisseur;

class Afficheur
{
    private Testeur $testeur;



    public function __construct()
    {
        $this->testeur = new Testeur();
    }



    public function afficher(Ligne $ligne)
    {
        $errors = $this->testeur->tester($ligne);
        echo $errors . ' Erreurs';
        $main = $ligne;
        echo '<table class="table table-bordered table-xs">';
        echo '<tr>';
        echo '<th style="min-width:5em"></th><th>Total</th>';
        foreach (Ligne::CATEGORIES as $categorie) {
            if ($main->getValeur($categorie)->getValue() == 0) continue;
            echo '<th>' . $categorie . '</th>';
            if ($main->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT)->getValue() != 0) {
                echo '<th>' . $categorie . 'Enseignement</th>';
            }
            foreach (Ligne::TYPES as $type) {
                if ($main->getValeur($categorie . $type)->getValue() == 0) continue;
                echo '<th>' . $categorie . $type . '</th>';
            }
        }
        if ($main->getValeur(Ligne::CAT_TYPE_PRIME)->getValue() != 0) {
            echo '<th>' . Ligne::CAT_TYPE_PRIME . '</th>';
        }
        echo '</tr>';

        $this->affLigne($ligne, $ligne, null, false);
        echo '<tr><td colspan="13">&nbsp;</td></tr>';


        foreach ($ligne->getSubs() as $sk => $sl) {
            $this->affLigne($sl, $ligne, 'service', true);
            foreach ($sl->getSubs() as $vk => $vl) {
                $this->affLigne($vl, $ligne, '&raquo; vol. h.', false);
            }
            echo '<tr><td colspan="13">&nbsp;</td></tr>';
        }


        echo '</table>';
    }



    protected function affLigne(Ligne $ligne, Ligne $main, ?string $name, bool $service): void
    {
        if ($service) {
            echo '<tr style="background-color: #e9f4ff">';
        } else {
            echo '<tr>';
        }

        echo '<th>' . $name . '</th>';
        $this->affValeur($ligne->getValeur(Ligne::TOTAL));
        foreach (Ligne::CATEGORIES as $categorie) {
            if ($main->getValeur($categorie)->getValue() == 0) continue;
            $this->affValeur($ligne->getValeur($categorie));
            $this->affValeur($ligne->getValeur($categorie . Ligne::TYPE_ENSEIGNEMENT));
            foreach (Ligne::TYPES as $type) {
                if ($main->getValeur($categorie . $type)->getValue() == 0) continue;
                $this->affValeur($ligne->getValeur($categorie . $type));
            }
        }
        if ($main->getValeur(Ligne::CAT_TYPE_PRIME)->getValue() != 0) {
            $this->affValeur($ligne->getValeur(Ligne::CAT_TYPE_PRIME));
        }
        echo '</tr>';
    }



    protected function affValeur(Valeur $valeur): void
    {
        $title = [
            'Valeur de départ : ' . $valeur->getValue(),
            'Valeur finale : ' . $valeur->getValueFinale(),
            'Arrondi : ' . $valeur->getArrondi(),
        ];
        $color = 'transparent';

        if ($valeur->hasControle()) {
            $title[] = 'Valeur de contrôle : ' . $valeur->getControle();
            if ($valeur->isControleOk()) {
                $color = '#C4FFC5';
            } else {
                $color = '#FFC4C4';
            }
        }

        $title = implode("\n", $title);

        $content = $valeur->getValueFinale();

        if (0 != $valeur->getArrondi()) {
            $content .= ' <span style="color:red">' . ($valeur->getArrondi() == 1 ? 'Excès' : 'Troncature') . '</span>';
        }

        echo "<td style=\"background-color:$color\" title=\"$title\">$content</td>";
    }

}