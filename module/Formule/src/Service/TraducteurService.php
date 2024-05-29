<?php

namespace Formule\Service;


use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleTableur;
use Formule\Entity\FormuleVolumeHoraire;
use Unicaen\OpenDocument\Calc;

/**
 * Description of TraducteurService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TraducteurService
{
    private FormuleTableur $tableur;

    private Calc\Cell $cell;

    private string $name;

    private string $tableurExpr;

    private bool $debug = false;

    private array $expr;

    private array $transfoActions = [
        'suppressionIsError',
        'formatageNoms',
        'traductionVariables',
        'detectionVariables',
        'transfoTestsBool',
        'transfoStructureAffectation',
        'transfoIfPlus',
        'transfoSimplify',
    ];

    private string $transfoAction;



    public function setDebug(bool $debug): TraducteurService
    {
        $this->debug = $debug;
        return $this;
    }



    public function traduire(FormuleTableur $tableur, Calc\Cell $cell): string
    {
        $this->tableur = $tableur;
        $this->cell = $cell;

        $mls = (string)$this->tableur->mainLine();
        $this->name = $this->cell->getName();
        if (str_ends_with($this->name, $mls)) {
            $this->name = substr($this->name, 0, -strlen($mls));
        }

        $this->tableurExpr = substr($this->cell->getFormule() ?? '', 3) ?? $this->cell->getValue();
        $expr = $this->cell->getFormuleExpr();
        if ($expr) {
            $this->expr = $expr;
        } else {
            throw new \Exception('La cellule ' . $this->cell->getName() . ' est vide. Or elle est utilisée dans une ou plusieurs expressions');
        }

        $this->transformer($this->expr);
        $php = $this->convertir();

        if ($this->debug) {
            echo '<h2>' . $this->name . '</h2>';
            echo Calc\Display::formuleExpr($this->cell->getFormuleExpr());
            echo Calc\Display::formuleExpr($this->expr);
            phpDump($php);
        }

        return $php;
    }



    protected function transformer(array &$expr): void
    {
        foreach ($this->transfoActions as $transfoAction) {
            $this->transfoAction = $transfoAction;
            foreach ($expr as $i => $sexpr) {
                if (array_key_exists($i, $expr)) {
                    $this->transfoParse($expr, $i);
                }
            }
        }
    }



    private function transfoParse(array &$expr, int $index): void
    {
        // si ce n'est pas une expression, alors on passe
        $isExpr = !isset($expr[$index]['type']);

        if ($isExpr) {
            $sIndex = 0;
            while ($sIndex < count($expr[$index] ?? [])) {
                $this->transfoParse($expr[$index], $sIndex);
                $sIndex++;
            }
        } else {
            $this->{$this->transfoAction}($expr, $index);
            if (isset($expr[$index]['exprs'])) {
                $sIndex = 0;
                while ($sIndex < count($expr[$index]['exprs'])) {
                    $this->transfoParse($expr[$index]['exprs'], $sIndex);
                    $sIndex++;
                }
            }
            if (isset($expr[$index]['expr'])) {
                $sIndex = 0;
                while ($sIndex < count($expr[$index]['expr'])) {
                    $this->transfoParse($expr[$index]['expr'], $sIndex);
                    $sIndex++;
                }
            }
        }
    }



    protected function convertir(): string
    {
        $php = "// $this->name" . $this->tableurExpr . "\n";
        if ($this->name == $this->cell->getName()) {
            $php .= "protected function c_$this->name(): float\n";
        } else {
            $php .= "protected function c_$this->name(int \$l): float\n";
        }
        $php .= "{\n";
        $php .= $this->indent($this->returnPhp($this->traductionExpr($this->expr)));
        $php .= "}\n";

        return $php;
    }



    public function indent(string $php, int $levels = 1): string
    {

        $lines = explode("\n", $php);
        $result = '';
        foreach ($lines as $line) {
            for ($i = 0; $i < $levels; $i++) {
                $result .= "  ";
            }
            $result .= $line . "\n";
        }

        return $result;
    }



    private function suppressionIsError(array &$expr, int $i): void
    {
        // on supprime les ISERROR dans les tests
        if (isset($expr[$i]['name']) && $expr[$i]['name'] === 'IF') {
            $tfi = $expr[$i]['exprs'][0][0];
            if ($tfi['type'] === 'function' && $tfi['name'] === 'ISERROR') {
                if (1 === count($expr[$i]['exprs'][2])) {
                    $expr[$i] = $expr[$i]['exprs'][2][0];
                } else {
                    $expr[$i] = ['type' => 'expr', 'expr' => $expr[$i]['exprs'][2]];
                }

                return;
            }
        }
    }



    private function formatageNoms(array &$expr, int $i): void
    {
        if (!isset($expr[$i]['name'])) return;

        // On supprime les dollars inutiles
        if (str_contains($expr[$i]['name'], '$')) {
            $expr[$i]['name'] = str_replace('$', '', $expr[$i]['name']);
        }

        // On supprime le numéro de ligne si on est sur la ligne principale
        $ml = (string)$this->tableur->mainLine();
        if (str_ends_with($expr[$i]['name'], $ml)) {
            $expr[$i]['name'] = substr($expr[$i]['name'], 0, -strlen($ml));
        }
    }



    private function traductionVariables(array &$expr, int $i): void
    {
        if (!isset($expr[$i]['type']) || !isset($expr[$i]['name'])) return;

        if ($expr[$i]['type'] != 'variable') return;

        $variable = $this->tableur->variableFromName($expr[$i]['name']);

        if ($variable) {
            $expr[$i]['name'] = $variable;
            $expr[$i]['dataType'] = $this->tableur->variableType($variable);
        }
    }



    private function detectionVariables(array &$expr, int $i): void
    {
        if (!isset($expr[$i]['type']) || !isset($expr[$i]['name'])) return;

        if ($expr[$i]['type'] != 'cell') return;

        $variable = $this->tableur->variableFromCol($expr[$i]['name']);

        if ($variable) {
            $expr[$i]['type'] = 'variable';
            $expr[$i]['name'] = $variable;
            $expr[$i]['dataType'] = $this->tableur->variableType($variable);
        }
    }



    private function transfoTestsBool(array &$expr, int $i): void
    {
        if ($expr[$i]['type'] == 'variable'
            && $expr[$i]['name'] == 'vh.typeInterventionCode'
            && isset($expr[$i + 2])
            && $expr[$i + 1]['type'] == 'op' && $expr[$i + 1]['name'] == '='
            && $expr[$i + 2]['type'] == 'string' && $expr[$i + 2]['content'] == 'Référentiel'
        ) {
            $expr[$i]['name'] = 'vh.volumeHoraireReferentiel';
            unset($expr[$i + 1]);
            unset($expr[$i + 2]);
        }

        if ($expr[$i]['type'] == 'variable'
            && $expr[$i]['name'] == 'vh.typeInterventionCode'
            && isset($expr[$i + 2])
            && $expr[$i + 1]['type'] == 'op' && $expr[$i + 1]['name'] == '<>'
            && $expr[$i + 2]['type'] == 'string' && $expr[$i + 2]['content'] == 'Référentiel'
        ) {
            $expr[$i]['name'] = 'vh.volumeHoraire';
            unset($expr[$i + 1]);
            unset($expr[$i + 2]);
        }


        if ($expr[$i]['type'] == 'variable'
            && isset($expr[$i + 2])
            && $expr[$i + 1]['type'] == 'op'
            && $expr[$i + 2]['type'] == 'string'
        ) {
            $vType = $this->tableur->variableType($expr[$i]['name']);
            if ('bool' == $vType) {
                $op = $expr[$i + 1]['name'];
                if (in_array($op, ['=', '<>'])) {
                    $val = strtolower($expr[$i + 2]['content']);
                    $val = $val == 'oui' || $val == '1' || $val == 'o';
                    if ($op == '<>') {
                        $val = !$val;
                    }
                    if ($val) {
                        unset($expr[$i + 1]);
                        unset($expr[$i + 2]);
                    } else {
                        $expr[$i + 1] = $expr[$i];
                        $expr[$i] = ['type' => 'php', 'code' => '!'];
                        unset($expr[$i + 2]);
                    }
                }
            }
        }
    }



    private function transfoStructureAffectation(array &$expr, int $i): void
    {
        $transfo = false;

        if ($expr[$i]['type'] == 'variable'
            && $expr[$i]['name'] == 'vh.structureCode'
            && isset($expr[$i + 2])
            && $expr[$i + 1]['type'] == 'op' && ($expr[$i + 1]['name'] == '=' || $expr[$i + 1]['name'] == '<>')
            && $expr[$i + 2]['type'] == 'variable' && $expr[$i + 2]['name'] == 'i.structureCode'
        ) {
            $transfo = true;
        }

        if ($expr[$i]['type'] == 'variable'
            && $expr[$i]['name'] == 'i.structureCode'
            && isset($expr[$i + 2])
            && $expr[$i + 1]['type'] == 'op' && ($expr[$i + 1]['name'] == '=' || $expr[$i + 1]['name'] == '<>')
            && $expr[$i + 2]['type'] == 'variable' && $expr[$i + 2]['name'] == 'vh.structureCode'
        ) {
            $transfo = true;
        }

        if ($transfo) {
            $op = $expr[$i + 1]['name'];
            if ('=' == $op) {
                unset($expr[$i]);
            } else {
                $expr[$i] = ['type' => 'php', 'code' => '!'];
            }
            $expr[$i + 1] = ['type' => 'variable', 'name' => 'vh.structureAffectation'];
            unset($expr[$i + 2]);
        }
    }



    private function transfoIfPlus(array &$expr, int $i): void
    {
        $transfo = false;

        if ($expr[$i]['type'] == 'function' && $expr[$i]['name'] == 'IF') {
            $ifi = $i;
            if (isset($expr[$i + 1])) {
                $transfo = true;
            }
        }

        if ($transfo) {
            while (array_key_exists(++$i, $expr)) {
                $expr[$ifi]['exprs'][1][] = $expr[$i];
                if (isset($expr[$ifi]['exprs'][2])) {
                    $expr[$ifi]['exprs'][2][] = $expr[$i];
                }
                unset($expr[$i]);
            }
        }
    }



    private function transfoSimplify(array &$expr, int $i): void
    {
        /*  on simplifie les 0*machin, 1*machin, machin*0, machin*1 */
        if ($i > 0 && $expr[$i]['type'] == 'op' && $expr[$i]['name'] == '*') {
            if ($expr[$i - 1]['type'] == 'number') {
                if ($expr[$i - 1]['value'] == 0) {
                    unset($expr[$i]);
                    unset($expr[$i + 1]);
                } elseif ($expr[$i - 1]['value'] == 1) {
                    unset($expr[$i - 1]);
                    unset($expr[$i]);
                }
            } elseif ($expr[$i + 1]['type'] == 'number') {
                if ($expr[$i + 1]['value'] == 0) {
                    unset($expr[$i - 1]);
                    unset($expr[$i]);
                } elseif ($expr[$i + 1]['value'] == 1) {
                    unset($expr[$i]);
                    unset($expr[$i + 1]);
                }
            }
        }
    }



    private function traductionExpr(array &$expr, bool $autoReturn = true): string
    {
        $methods = [
            'expr'     => 'traductionSousExpr',
            'function' => 'traductionFunction',
            'string'   => 'traductionString',
            'number'   => 'traductionNumber',
            'op'       => 'traductionOperator',
            'range'    => 'traductionRange',
            'cell'     => 'traductionCell',
            'variable' => 'traductionVariable',
            'php'      => 'traductionPhp',
        ];


        $php = '';

        $isIf = false;
        foreach ($expr as $i => $term) {
            if ($term !== null) {
                if ($term['type'] === 'function' && $term['name'] === 'IF') {
                    $isIf = true;
                }
                if (array_key_exists($term['type'], $methods)) {
                    $php .= $this->{$methods[$term['type']]}($expr, $i);
                } elseif ($term['type'] === 'plsql') {
                    $php .= $term['code'];
                } else {
                    $php .= '[PB TRADUCTION]';
                }
            }
        }

        return $php;
    }



    private function returnPhp(string $php): string
    {
        if (!str_contains($php, 'return ')) {
            $php = 'return ' . $php;
        }

        if (!str_ends_with($php, ';') && !str_ends_with($php, '}')) {
            $php .= ';';
        }

        return $php;
    }



    private function traductionSousExpr(array &$expr, int $i): string
    {
        $term = $expr[$i];

        return "(" . $this->traductionExpr($term['expr']) . ")";
    }



    private function traductionString(array &$expr, int $i): string
    {
        $term = $expr[$i];

        return "'" . str_replace("'", "\'", $term['content']) . "'";
    }



    private function traductionPhp(array &$expr, int $i): string
    {
        $term = $expr[$i];

        return $term['code'];
    }



    private function traductionNumber(array &$expr, int $i): string
    {
        $term = $expr[$i];

        return (string)$term['value'];
    }



    private function traductionOperator(array &$expr, int $i): string
    {
        $term = $expr[$i];

        $trads = [
            '&' => '||',
            '%' => '/ 100',
            '=' => '==',
        ];

        $op = $term['name'];
        if (isset($trads[$op])) {
            $op = $trads[$op];
        }

        return ' ' . $op . ' ';
    }



    private function traductionRange(array &$expr, int $i): string
    {
        // normalement, on ne passe jamais par ici : les RANGE ne sont utilisés qu'avec traductionFunctionRange
        $term = $expr[$i];

        return '[PB TRADUCTION]';
    }



    public function traductionCell(array &$expr, int $i): string
    {
        $term = $expr[$i];
        ['col' => $col, 'row' => $row] = Calc::cellNameToCoords($term['name']);

        $ml = $this->tableur->mainLine();

        $col = Calc::numberToLetter($col);

        if ($row == 0) {
            return "\$this->c('$col',\$l)";
        } elseif ($row < $ml) {
            return "\$this->cg('$col$row')";
        } else {
            $rowDiff = $row - $ml;
            return "\$this->c('$col',\$l+$rowDiff)";
        }
    }



    private function traductionVariable(array &$expr, int $i): string
    {
        $name = $expr[$i]['name'];
        $accesseurs = ['get', 'has', 'is'];

        if (str_starts_with($name, 'i.')) {
            foreach ($accesseurs as $accesseur) {
                $method = $accesseur . ucfirst(substr($name, 2));
                if (method_exists(FormuleIntervenant::class, $method)) {
                    $variable = '$this->intervenant()->' . $method . '()';
                }
            }
        } elseif (str_starts_with($name, 'vh.')) {
            foreach ($accesseurs as $accesseur) {
                $method = $accesseur . ucfirst(substr($name, 3));
                if (method_exists(FormuleVolumeHoraire::class, $method)) {
                    $variable = '$this->volumeHoraire($l)->' . $method . '()';
                }
            }
        } elseif ($name == 'i_service_du') {
            $variable = '$this->intervenant()->getServiceDu()';
        } else {
            $targetExpr = [$this->tableur->tableur()->getAliasTarget($name)];
            $variable = $this->traductionExpr($targetExpr);
        }

        return $variable;
    }



    private function traductionFunction(array &$expr, int $i): string
    {
        $term = $expr[$i];
        $functions = [
            'IF'                => 'traductionFunctionIf',
            'AND'               => 'traductionFunctionAnd',
            'OR'                => 'traductionFunctionOr',
            'ISBLANK'           => 'traductionFunctionIsBlank',
            'SUMIF'             => 'traductionFunctionSumIf',
            'MID'               => 'traductionFunctionMid',
            'COM.MICROSOFT.IFS' => 'traductionFunctionIfs',
            'LEFT'              => 'traductionFunctionLeft',
        ];

        if (array_key_exists($term['name'], $functions)) {
            return $this->{$functions[$term['name']]}($expr, $i);
        }

        if (isset($term['exprs']) && 1 === count($term['exprs']) && 1 === count($term['exprs'][0]) && $term['exprs'][0][0]['type'] === 'range') {
            return $this->traductionFunctionRange($expr, $i);
        }

        $tradNames = [
            'MIN' => 'min',
            'MAX' => 'max',
        ];

        if (isset($tradNames[$term['name']])) {
            $php = $tradNames[$term['name']] . '(';
        } else {
            $php = $term['name'] . '(';
        }

        if (!empty($term['exprs'])) {
            $plExprs = [];
            foreach ($term['exprs'] as $e => $fExpr) {
                $plExprs[$e] = $this->traductionExpr($fExpr);
            }
            $php .= implode(', ', $plExprs);
        }
        $php .= ')';

        return $php;
    }



    private function traductionFunctionRange(array &$expr, int $i): string
    {
        $functions = [
            'MAX' => 'max',
            'SUM' => 'somme',
        ];

        $term = $expr[$i];
        $range = $term['exprs'][0][0];

        $name = $functions[$term['name']] ?? $term['name'];

        $begin = Calc::cellNameToCoords($range['begin']);
        $end = Calc::cellNameToCoords($range['end']);

        if ($begin['col'] === $end['col'] && $begin['row'] <= $this->tableur->mainLine() && $end['row'] >= 500) {
            $col = Calc::numberToLetter($begin['col']);

            return "\$this->$name('$col')";
        }

        return '[PB TRADUCTION]';
    }



    private function traductionFunctionIf(array &$expr, int $i): string
    {
        $term = $expr[$i];
        $cond = $term['exprs'][0];
        $then = $term['exprs'][1];
        if (isset($term['exprs'][2])) {
            $else = $term['exprs'][2];
        } else {
            $else = null;
        }

        $php = 'if (' . $this->traductionExpr($cond, false) . "){\n";
        $php .= $this->indent($this->returnPhp($this->traductionExpr($then)));
        if ($else) {
            $php .= "} else {\n";
            $php .= $this->indent($this->returnPhp($this->traductionExpr($else)));
        }
        $php .= '}';

        return $php;
    }



    private function traductionFunctionIfs(array &$expr, int $i): string
    {
        $term = $expr[$i];
        $php = 'CASE' . "\n";

        $exprs = $term['exprs'];

        $index = 0;
        while (array_key_exists($index, $exprs)) {
            $test = $exprs[$index];
            $val = $exprs[$index + 1];

            $php .= '  WHEN ' . $this->traductionExpr($test) . ' THEN ' . $this->traductionExpr($val) . "\n";
            $index += 2;
        }

        $php .= 'END;';

        return $php;
    }



    private function traductionFunctionAnd(array &$expr, int $i): string
    {
        $term = $expr[$i];
        $php = '';

        if (!empty($term['exprs'])) {
            $plExprs = [];
            foreach ($term['exprs'] as $e => $fExpr) {
                $fExpr[] = null;
                $plExprs[$e] = $this->traductionExpr($fExpr);
            }
            $php .= implode(' && ', $plExprs);
        }

        if (count($expr) === 1) {
            return $php;
        } else {
            return '(' . $php . ')';
        }
    }



    private function traductionFunctionOr(array &$expr, int $i): string
    {
        $term = $expr[$i];
        $php = '';

        if (!empty($term['exprs'])) {
            $plExprs = [];
            foreach ($term['exprs'] as $e => $fExpr) {
                $fExpr[] = null;
                $plExprs[$e] = $this->traductionExpr($fExpr);
            }
            $php .= implode(' || ', $plExprs);
        }

        if (count($expr) === 1) {
            return $php;
        } else {
            return '(' . $php . ')';
        }
    }



    private function traductionFunctionIsBlank(array &$expr, int $i): string
    {
        $term = $expr[$i];
        $test = $term['exprs'][0];

        if (1 === count($test)) {
            $php = $this->traductionExpr($test) . ' == null';
        } elseif (count($test) > 1) {
            $php = '(' . $this->traductionExpr($test) . ') == null';
        }

        return $php;
    }



    private function traductionFunctionSumIf(array &$expr, int $i): string
    {
        $term = $expr[$i];

        $plage = $term['exprs'][0][0];
        $critere = $term['exprs'][1];
        if (isset($term['exprs'][2][0])) {
            $plageSomme = $term['exprs'][2][0];
        } else {
            $plageSomme = $plage;
        }

        if ($critere[0]['type'] == 'string' && str_starts_with($critere[0]['content'], '=')) {
            $critere[0]['content'] = substr($critere[0]['content'], 1);
            array_unshift($critere, ['type' => 'op', 'name' => '=']);
        }
        if ($critere[0]['type'] != 'op') { // ajout du =, valeur par défaut
            $cc = $critere;
            $critere = [['type' => 'op', 'name' => '=']];
            foreach ($cc as $c) {
                $critere[] = $c;
            }
        }


        $php = "\$val = 0;\n";
        for ($c = 0; $c <= ($plage['colEnd'] - $plage['colBegin']); $c++) {
            $col = Calc::numberToLetter($plage['colBegin'] + $c);
            $colDest = Calc::numberToLetter($plageSomme['colBegin'] + $c);

            $rowBegin = $plage['rowBegin'] - $this->tableur->mainLine();
            if ($rowBegin === 0) {
                $rowBegin = 'l';
            } else {
                $rowBegin = '1 + ' . $rowBegin;
            }

            if ($plage['rowEnd'] >= 500) {
                $rowEnd = 'ose_formule.volumes_horaires.length';
            } else {
                $rowEnd = (string)($plage['rowEnd'] - $this->tableur->mainLine());
            }

            $iftest = $critere;
            array_unshift($iftest, ['type' => 'cell', 'name' => $col.$this->tableur->mainLine()]);

            $this->transformer($iftest);
            $iftest = $this->traductionExpr($iftest);

            $php .= '$volumesHoraires = $this->intervenant->getVolumesHoraires();'."\n";
            $php .= 'foreach ($volumesHoraires as $l => $volumesHoraire) {'."\n";
            $php .= "  if ($iftest){\n";
            $php .= "    \$val += \$this->c('$colDest',\$l);\n";
            $php .= "  }\n";
            $php .= "}\n";
        }

        if (isset($term['valExpr'])) {
            $php .= 'return ' . $this->traductionExpr($term['valExpr']);
        } else {
            $php .= 'return $val;';
        }

//        echo '<pre>' . htmlentities($php) . '</pre>';

        return $php;
    }



    private function traductionFunctionMid(array &$expr, int $i): string
    {
        $term = $expr[$i];
        $php = 'substr(';

        $phpExprs = [];
        foreach ($term['exprs'] as $e => $fExpr) {
            $fExpr[] = null;
            $phpExprs[$e] = $this->traductionExpr($fExpr);
        }

        // pour éviter un message sur chaine null
        $phpExprs[0] .= " ?? ''";

        // en PHP les indexs débutent à 0, pas 1
        if ((string)(int)$phpExprs[1] === $phpExprs[1]) {
            $phpExprs[1] = (string)((int)$phpExprs[1] - 1);
        } else {
            $phpExprs[1] .= '-1';
        }

        $php .= implode(', ', $phpExprs);

        $php .= ')';

        return $php;
    }



    private function traductionFunctionLeft(array &$expr, int $i): string
    {
        $term = $expr[$i];

        $string = $this->traductionExpr($term['exprs'][0]);
        $length = $this->traductionExpr($term['exprs'][1]);

        $php = "substr($string ?? '', 0, $length)";

        return $php;
    }

}