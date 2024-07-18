<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

use Unicaen\OpenDocument\Calc;

$r = new Reverse();
$r->run();

class Expr
{
    public ?string $nom = null;
    public ?string $code = null;
    public ?string $traduction = null;

    public ?string $cellName = null;
    public ?Calc\Cell $cell = null;
    public ?string $formule = null;
}

class Reverse
{
    public $id = 140677;

    public $formule = 'FORMULE_MONTPELLIER';

    public $packaheBlocSep = "\n\n\n";
    public $packageLineBegin = 156;
    public $packageLineEnd = 476;

    public \Formule\Entity\FormuleTableur $tableur;

    public array $result = [];

    /** @var array|Expr[] */
    public array $exprs = [];

    public array $traductions = [
        'vh.structure_code'                                      => 'A25',
        'vh.structure_is_affectation'                            => 'B25="Oui"',
        'vh.structure_is_univ'                                   => 'C25="Oui"',
        'vh.structure_is_exterieure'                             => 'D25="Oui"',
        'vh.service_statutaire'                                  => 'E25="Oui"',
        'ose_formule.intervenant.depassement_service_du_sans_hc' => 'D$8="Oui"',
        'vh.taux_fi'                                             => 'G25',
        'vh.taux_fa'                                             => 'H25',
        'vh.taux_fc'                                             => 'I25',
        'vh.type_intervention_code'                              => 'J25',
        'vh.taux_service_du'                                     => 'AK25',
        'vh.taux_service_compl'                                  => 'AL25',
        'vh.ponderation_service_du'                              => 'M25',
        'vh.ponderation_service_compl'                           => 'N25',
        'vh.heures'                                              => 'O25',
        'i.heures_service_statutaire'                            => 'D$6',
        'i.type_intervenant_code'                                => 'D$3',
        'i.service_du'                                           => 'I$3',
        'ose_formule.intervenant.service_du'                     => 'I$3',
        'GREATEST'                                               => 'MAX',
        'LEAST'                                                  => 'MIN',
        'vh.volume_horaire_ref_id IS NULL'                       => 'J25<>"Référentiel"',
        'vh.volume_horaire_ref_id IS NOT NULL'                   => 'J25="Référentiel"',
        'RETURN '                                                => '',
        'IF '                                                    => 'SI(',
        'THEN'                                                   => ';',
        'ELSE'                                                   => '',
        'END IF;'                                                => ')',
        'intervenant.depassement_service_du_sans_hc'             => 'D$8',
        'calcFnc(\'last\',\'l\')'                                => 'AS$500',
        'cell(\'l\', l-1)'                                       => 'AS24',
        'calcFnc(\'total\',\'o\')'                               => 'AP$17',
    ];


    public array $correspondances = [
        'j'            => 'AK25',
        'k'            => 'AR25',
        'l'            => 'AS25',
        'm'            => 'AU25',
        'n'            => 'AL25',
        'o'            => 'AW25',
        'q'            => 'AN25',
        'r'            => 'AO25',
        'r136'         => 'AO$14',
        'r137'         => 'AO$15',
        's'            => 'AP25',
        's136'         => 'AP$14',
        's137'         => 'AP$15',
        't136'         => 'AQ$14',
        't137'         => 'AQ$15',
        'u'            => 'V25',
        'v'            => 'W25',
        'w'            => 'X25',
        'x'            => 'Y25',
        'y'            => 'Z25',
        'z'            => 'AA25',
        'aa'           => 'AB25',
        'ab'           => 'AC25',
        'ac'           => 'AD25',
        'hc_budg_cell' => '',
        'hc_budg'      => 'AP18',
    ];

    public array $masquer = [
        'k', 'n', 'q', 'r', 'r136', 'r137','s137','s138','t137',
        'z', 'aa','ab',
    ];



    public function loadTableur()
    {
        $tableur = new \Unicaen\OpenDocument\Document();
        $tableur->loadFromFile('/app/data/formules/' . $this->formule . '.ods');
        $this->tableur = new \Formule\Entity\FormuleTableur($tableur->getCalc());
        //  $this->tableur->lire();
    }



    public function loadPackage()
    {
        $c = explode("\n", file_get_contents('/app/data/ddl/package/' . $this->formule . '/body.sql'));
        $i = 0;

        while ($i < $this->packageLineBegin) {
            unset($c[$i]);
            $i++;
        }

        $i = $this->packageLineEnd;
        while (isset($c[$i])) {
            unset($c[$i]);
            $i++;
        }

        $c = implode("\n", $c);
        $blocs = explode($this->packaheBlocSep, $c);

        $this->exprs = [];
        foreach ($blocs as $bloc) {
            $bloc = trim($bloc);
            $whenPos = strpos($bloc, 'WHEN') + 5;
            $thenPos = strpos($bloc, 'THEN') - 1;
            $nameBloc = substr($bloc, $whenPos, $thenPos - $whenPos);
            $nameBloc = substr($nameBloc, strpos($nameBloc, "'") + 1);
            $nom = substr($nameBloc, 0, strpos($nameBloc, "'"));
            if ($nom) {
                $expr = new Expr();
                $expr->nom = $nom;
                $expr->code = $bloc;
                $this->exprs[$nom] = $expr;
            }
        }
    }



    public function calculerCorrespondances()
    {
        $l = 25 - 2;
        $cMin = Calc::letterToNumber('AI');
        $cMax = Calc::letterToNumber('EZ');

        for ($c = $cMin; $c <= $cMax; $c++) {
            $cell = $this->tableur->sheet()->getCellByCoords($c, $l);
            if ($cell) {
                $nom = 't' . $cell->getValue();
                if (array_key_exists($nom, $this->correspondances) && !$this->correspondances[$nom]) {
                    $this->correspondances[$nom] = Calc::numberToLetter($c) . ($l + 2);
                }
            }
        }
    }



    public function traduire(Expr $expr): void
    {
        $code = $expr->code;

        $code = substr($code, strpos($code, 'THEN') + 5);

        foreach ($this->traductions as $o => $n) {
            if (str_contains($o, '"Oui"')) {
                $code = str_replace('NOT ' . $o, str_replace('"Oui"', '"Non"', $n), $code);
            }
            if (str_contains($o, '"Non"')) {
                $code = str_replace('NOT ' . $o, str_replace('"Non"', '"Oui"', $n), $code);
            }
            $code = str_replace($o, $n, $code);
        }

        foreach ($this->correspondances as $o => $n) {
            $o2 = "cell('$o', l)";
            $code = str_replace($o2, $n, $code);

            $o2 = "cell('$o',l)";
            $code = str_replace($o2, $n, $code);

            $o2 = "cell('$o')";
            $code = str_replace($o2, $n, $code);
        }

        // supprimer les espaces & retours chariot
        //$code = str_replace(' ', '', $code);
        //$code = str_replace("\n", '', $code);

        $code = $code = str_replace(";)", ')', $code);


        $code = str_replace(",", ';', $code);

        if (str_ends_with($code, ';')) {
            $code = substr($code, 0, -1);
        }
        $code = '=' . trim($code);

        $expr->traduction = $code;
    }



    public function analyseValeur(string $valeur): float
    {
        if (str_starts_with($valeur, ',')) {
            $valeur = '0' . $valeur;
        }
        $valeur = str_replace(',', '.', $valeur);
        return (float)$valeur;
    }



    public function analyseTrace()
    {
        $this->result = [];

        $trace = trim(OseAdmin::instance()->getBdd()->selectOne('select trace FROM traces WHERE id = ' . $this->id, [], 'TRACE'));

        $trace = explode("[", $trace);
        foreach ($trace as $t) {
            $t = explode('|', $t);
            $nom = $t[1] ?? null;

            if ($nom) {
                $ligne = null;
                switch ($t[0]) {
                    case 'cell':
                        $ligne = $t[2];
                        $valeur = $this->analyseValeur($t[3]);
                        break;
                    case 'calc':
                        $ligne = 'total';
                        $valeur = $this->analyseValeur($t[2]);
                        break;
                }
                if ($ligne !== null) {
                    if (!isset($this->result[$nom])) {
                        $this->result[$nom] = [];
                    }
                    if (isset($this->result[$nom][$ligne]) && $this->result[$nom][$ligne] !== $valeur) {
                        throw new \Exception("Valeur changeante : $nom $ligne $valeur");
                    }
                    $this->result[$nom][$ligne] = $valeur;
                }
            }
        }
    }



    public function getResult(Expr $expr): array
    {
        $res = [];

        if ($expr->cell) {
            $cellCol = Calc::numberToLetter($expr->cell->getCol());
            $abs = $expr->cell->getRow() != 25;

            $ref = $this->result[$expr->nom] ?? [];
            foreach ($ref as $ligne => $val) {
                if ($abs) {
                    $cell = $expr->cell->getName();
                } else {
                    $cell = $cellCol . ($ligne + 4);
                }
                if (!$cell) {
                    $valeur = 0;
                } else {
                    $valeur = $this->tableur->getCellFloatVal($cell);
                }
                $res[$ligne] = [
                    'o' => round($val, 2),
                    'n' => round($valeur, 2),
                ];
            }
        }

        return $res;
    }



    public function afficher()
    {
        echo "<table class='table table-bordered'>";
        echo "<tr>";
        echo "<th>Nom</th>";
        echo "<th>Colonne</th>";
        echo "<th>Pl/SQL</th>";
        echo "<th>Traduction</th>";
        echo "<th>Expression</th>";
        echo "<th>Résultat</th>";
        echo "</tr>";
        foreach ($this->exprs as $expr) {
            if (!in_array($expr->nom, $this->masquer)) {
                $this->afficherExpr($expr);
            }
        }
        echo '</table>';
    }



    public function afficherResult(array $result): string
    {
        $h = '<table class="table table-bordered table-xs" style="font-size:8pt">';
        $h .= '<tr><th>L</th><th>Package</th><th>Tableur</th></tr>';
        foreach ($result as $l => $r) {
            $ligne = (int)$l + 25 - 1;
            $diff = $r['o'] !== null && $r['o'] !== $r['n'];
            $h .= '<tr' . ($diff ? ' style="background-color: red"' : '') . '><th>' . $ligne . '</th><td>' . $r['o'] . '</td><td>' . $r['n'] . '</td></tr>';
        }
        $h .= '</table>';

        return $h;
    }



    public function afficherExpr(Expr $expr)
    {
        $expression = '';
        if ($expr->cell) {
            $expression = $expr->cell->getFormule() ?? '';
            $expression = str_replace('[.', '', $expression);
            $expression = str_replace(']', '', $expression);
            $expression = str_replace('of:=', '', $expression);
        }

        echo "<tr>";
        echo "<th>$expr->nom</th>";
        echo "<th>$expr->cellName</th>";
        echo "<th><pre>" . htmlentities($expr->code) . "</pre></th>";
        echo "<th><pre>" . htmlentities($expr->traduction) . "</pre></th>";
        echo "<th><pre>" . htmlentities($expression) . "</pre></th>";
        echo "<th>" . $this->afficherResult($this->getResult($expr)) . "</th>";
        echo "</tr>";

        //var_dump($bloc);
    }



    public function run()
    {
        $sql = "BEGIN OSE_FORMULE.test(" . $this->id . "); END;";
        OseAdmin::instance()->getBdd()->exec($sql);

        $this->loadTableur();
        $this->loadPackage();
        $this->calculerCorrespondances();
        $this->analyseTrace();
        foreach ($this->exprs as $expr) {
            $expr->cellName = $this->correspondances[$expr->nom] ?? null;
            if ($expr->cellName) {
                $expr->cell = $this->tableur->sheet()->getCell($expr->cellName);
            }
            $this->traduire($expr);
        }

        $this->afficher();
    }
}