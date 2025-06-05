<?php

namespace Formule\Service;


use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;
use Formule\Model\FormuleTableur;
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

    private bool $absCell = false;

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
        'transfoStructureUniv',
        'transfoIfPlus',
        'transfoIfInFunction',
        'transfoSumIf',
        'transfoAbsRange',
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

        if ($cell->getRow() == $this->tableur->lastLine()){
             // la dernière ligne n'a pas à être traduite : c'est la fonction "derniere"
            return '';
        }

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

        $this->absCell = $cell->getRow() < $this->tableur->mainLine();
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



    protected function transfoParse(array &$expr, int $index): void
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
            $this->absCell = true;
        } else {
            $php .= "protected function c_$this->name(int \$l): float\n";
            $this->absCell = false;
        }
        $php .= "{\n";
        $php .= $this->indent($this->returnPhp($this->traductionExpr($this->expr)));
        $php .= "}";

        return $php;
    }



    public function indent(string $php, int $levels = 1): string
    {

        $lines = explode("\n", $php);
        $result = '';
        foreach ($lines as $line) {
            for ($i = 0; $i < $levels; $i++) {
                $result .= "    ";
            }
            $result .= $line . "\n";
        }

        return $result;
    }



    protected function suppressionIsError(array &$expr, int $i): void
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



    protected function formatageNoms(array &$expr, int $i): void
    {
        if (!isset($expr[$i]['name'])) return;

        // On supprime le numéro de ligne si on est sur la ligne principale
        $ml = (string)$this->tableur->mainLine();
        if (str_ends_with($expr[$i]['name'], $ml)) {
            $expr[$i]['name'] = substr($expr[$i]['name'], 0, -strlen($ml));
        }

        if (!$this->absCell) {
            /* Ce mécanisme ne fonctionne que si on est dans les lignes de VH, avec références relatives à la ligne du dessous ou du dessus */
            $mlMoins = (string)($this->tableur->mainLine() - 1);
            if (str_ends_with($expr[$i]['name'], $mlMoins) && !str_ends_with($expr[$i]['name'], '$' . $mlMoins)) {
                $expr[$i]['name'] = substr($expr[$i]['name'], 0, -strlen($mlMoins));
                $expr[$i]['rel'] = -1;
            }
        }

        // On supprime les dollars inutiles
        if (str_contains($expr[$i]['name'], '$')) {
            $expr[$i]['name'] = str_replace('$', '', $expr[$i]['name']);
        }
    }



    protected function traductionVariables(array &$expr, int $i): void
    {
        if (!isset($expr[$i]['type']) || !isset($expr[$i]['name'])) return;

        if ($expr[$i]['type'] != 'variable') return;

        $variable = $this->tableur->variableFromName($expr[$i]['name']);

        if ($variable) {
            $expr[$i]['name'] = $variable;
            $expr[$i]['dataType'] = $this->tableur->variableType($variable);
        }
    }



    protected function detectionVariables(array &$expr, int $i): void
    {
        if (!isset($expr[$i]['type']) || !isset($expr[$i]['name'])) return;

        if ($expr[$i]['type'] != 'cell') return;

        $variable = $this->tableur->variableFromCell($expr[$i]['name']);

        if ($variable) {
            $expr[$i]['type'] = 'variable';
            $expr[$i]['name'] = $variable;
            $expr[$i]['dataType'] = $this->tableur->variableType($variable);
        }
    }



    protected function transfoTestsBool(array &$expr, int $i): void
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



    protected function transfoStructureAffectation(array &$expr, int $i): void
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



    protected function transfoStructureUniv(array &$expr, int $i): void
    {
        //    "vh.structure_code = cell('K10')"            => 'vh.structure_is_univ',
        //    "vh.structure_code <> cell('K10')"           => 'NOT vh.structure_is_univ',

        $transfo = false;

        if ($expr[$i]['type'] == 'variable'
            && $expr[$i]['name'] == 'vh.structureCode'
            && isset($expr[$i + 2])
            && $expr[$i + 1]['type'] == 'op' && ($expr[$i + 1]['name'] == '=' || $expr[$i + 1]['name'] == '<>')
            && $expr[$i + 2]['type'] == 'cell' && $expr[$i + 2]['name'] == 'K10'
        ) {
            $transfo = true;
        }

        if ($transfo) {
            if ($expr[$i + 1]['name'] == '=') {
                $expr[$i]['name'] = 'vh.structureUniv';
                unset($expr[$i + 1]);
                unset($expr[$i + 2]);
            } else {
                $expr[$i + 1] = $expr[$i];
                $expr[$i + 1]['name'] = 'vh.structureUniv';
                $expr[$i] = ['type' => 'php', 'code' => '!'];
                unset($expr[$i + 2]);
            }
        }
    }



    protected function transfoIfPlus(array &$expr, int $i): void
    {
        $transfoBefore = false;
        $transfoAfter = false;

        if ($expr[$i]['type'] == 'function' && $expr[$i]['name'] == 'IF') {
            $ifi = $i;
            if (isset($expr[$i - 1])) {
                $transfoBefore = true;
            }
            if (isset($expr[$i + 1])) {
                $transfoAfter = true;
            }
        }

        if ($transfoBefore) {
            $movedExpr = [];
            for ($j = 0; $j < $i; $j++) {
                $movedExpr[] = $expr[$j];
                unset($expr[$j]);
            }

            $ifOkExpr = $movedExpr;
            foreach ($expr[$ifi]['exprs'][1] as $oke) {
                $ifOkExpr[] = $oke;
            }
            $expr[$ifi]['exprs'][1] = $ifOkExpr;

            if (isset($expr[$ifi]['exprs'][2])) {
                $ifNokExpr = $movedExpr;
                foreach ($expr[$ifi]['exprs'][2] as $oke) {
                    $ifNokExpr[] = $oke;
                }
                $expr[$ifi]['exprs'][2] = $ifNokExpr;
            }
        }

        if ($transfoAfter) {
            while (array_key_exists(++$i, $expr)) {
                $expr[$ifi]['exprs'][1][] = $expr[$i];
                if (isset($expr[$ifi]['exprs'][2])) {
                    $expr[$ifi]['exprs'][2][] = $expr[$i];
                }
                unset($expr[$i]);
            }
        }
    }



    protected function transfoIfInFunction(array &$expr, int $i): void
    {
        if ($expr[$i]['type'] == 'function' && $expr[$i]['name'] != 'IF') {
            $params = $expr[$i]['exprs'];
            $if = null;
            foreach( $params as $pi => $param ) {
                foreach($param as $ppi => $pexpr){
                    if ($pexpr && $pexpr['type'] == 'function' && $pexpr['name'] == 'IF') {
                        if ($if != null) {
                            throw new \Exception('Expression trop complexe et intraduisible en l\'état');
                        }
                        $if = $pexpr;
                        $ifIndex = $pi;
                    }
                }
            }
            if ($if){
                foreach( $if['exprs'] as $eid => $ifRes){
                    // on parse le retour si true et le retour si false, pas la condition
                    if ($eid > 0){
                        $nexpr = $expr;
                        $nexpr[$i]['exprs'][$ifIndex] = $ifRes;
                        $if['exprs'][$eid] = $nexpr;
                    }
                }
                $expr = [0 => $if];
            }
        }
    }



    protected function transfoSumIf(array &$expr, int $i): void
    {
        $term = $expr[$i];

        $transfoCritere = false;
        $transfoBefore = false;
        $transfoAfter = false;

        $ifi = $i;

        if ($term['type'] == 'function' && $term['name'] == 'SUMIF') {
            if (isset($term['exprs'][1])) {
                $transfoCritere = true;
                $critere = $term['exprs'][1];
            }

            if (isset($expr[$i - 1])) {
                $transfoBefore = true;
            }
            if (isset($expr[$i + 1])) {
                $transfoAfter = true;
            }

            $expr[$i]['returnExpr'] = [
                [
                    'type' => 'php',
                    'code' => '$val'
                ],
            ];
        }


        if ($transfoBefore) {
            $movedExpr = [];
            for ($j = 0; $j < $i; $j++) {
                $movedExpr[] = $expr[$j];
                unset($expr[$j]);
            }
            foreach ($expr[$i]['returnExpr'] as $oke) {
                $movedExpr[] = $oke;
            }
            $expr[$i]['returnExpr'] = $movedExpr;
        }

        if ($transfoAfter) {
            while (array_key_exists(++$i, $expr)) {
                $expr[$ifi]['returnExpr'][] = $expr[$i];
                unset($expr[$i]);
            }
        }

        if ($transfoCritere) {

            $ops = ['=', '<>', '>', '<'];
            foreach ($ops as $op) {
                if ($critere[0]['type'] == 'string' && str_starts_with($critere[0]['content'], $op)) {
                    $critere[0]['content'] = substr($critere[0]['content'], strlen($op));
                    if ('' === $critere[0]['content']) {
                        unset($critere[0]);
                        if ($critere[1]['type'] == 'op' && $critere[1]['name'] == '&') {
                            unset($critere[1]);
                        }
                    }
                    array_unshift($critere, ['type' => 'op', 'name' => $op]);


                }
            }

            if ($critere[0]['type'] != 'op') { // ajout du =, valeur par défaut
                $cc = $critere;
                $critere = [['type' => 'op', 'name' => '=']];
                foreach ($cc as $c) {
                    $critere[] = $c;
                }
            }

            $expr[$i]['exprs'][1] = $critere;
        }
    }



    protected function transfoAbsRange(array &$expr, int $i): void
    {
        $term = $expr[$i];

        $transfo = false;

        if ($term['type'] == 'function' && $term['name'] == 'SUM' && isset($term['exprs'][0][0])) {
            $range = $term['exprs'][0][0];
            if (isset($range['type']) && $range['type'] == 'range' && isset($range['rowEnd'])) {
                if ($range['rowEnd'] > 0 && $range['rowEnd'] < $this->tableur->mainLine()) {
                    $transfo = true;
                }
            }
        }

        if ($transfo) {

            $terms = [];
            for ($c = $range['colBegin']; $c <= $range['colEnd']; $c++) {
                for ($r = $range['rowBegin']; $r <= $range['rowEnd']; $r++) {
                    if (!empty($terms)) {
                        $terms[] = [
                            'type' => 'op',
                            'name' => '+',
                        ];
                    }
                    $terms[] = [
                        'type' => 'cell',
                        'name' => Calc::coordsToCellName($c, $r),
                    ];
                }
            }

            $expr[$i] = [
                'type' => 'expr',
                'expr' => $terms,
            ];
        }
    }



    protected function transfoSimplify(array &$expr, int $i): void
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

        /* On supprime les parenthèses inutiles */
        if (isset($expr[$i]['type']) && $expr[$i]['type'] == 'expr' && count($expr) == 1){
            $expr = $expr[$i]['expr'];
        }
    }



    protected function traductionExpr(array &$expr, bool $autoReturn = true): string
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
                } elseif ($term['type'] === 'php') {
                    $php .= $term['code'];
                } elseif ($term['type'] === 'space'){
                    // ne rien faire
                } else {
                    $php .= '[PB TRADUCTION PHP]';
                }
            }
        }

        return $php;
    }



    protected function returnPhp(string $php): string
    {
        if (!str_contains($php, 'return ')) {
            $php = 'return ' . $php;
        }

        if (!str_ends_with($php, ';') && !str_ends_with($php, '}')) {
            $php .= ';';
        }

        return $php;
    }



    protected function traductionSousExpr(array &$expr, int $i): string
    {
        $term = $expr[$i];

        return "(" . $this->traductionExpr($term['expr']) . ")";
    }



    protected function traductionString(array &$expr, int $i): string
    {
        $term = $expr[$i];

        return "'" . str_replace("'", "\'", $term['content']) . "'";
    }



    protected function traductionPhp(array &$expr, int $i): string
    {
        $term = $expr[$i];

        return $term['code'];
    }



    protected function traductionNumber(array &$expr, int $i): string
    {
        $term = $expr[$i];

        return (string)$term['value'];
    }



    protected function traductionOperator(array &$expr, int $i): string
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



    protected function traductionRange(array &$expr, int $i): string
    {
        // normalement, on ne passe jamais par ici : les RANGE ne sont utilisés qu'avec traductionFunctionRange
        $term = $expr[$i];

        return '[PB TRADUCTION RANGE]';
    }



    protected function traductionCell(array &$expr, int $i): string
    {
        $term = $expr[$i];
        ['col' => $col, 'row' => $row] = Calc::cellNameToCoords($term['name']);

        $ml = $this->tableur->mainLine();

        $col = Calc::numberToLetter($col);

        if ($row == 0) {
            $rel = isset($term['rel']) ? $term['rel'] : 0;
            if ($rel > 0) {
                $rel = '+' . (string)$rel;
            } elseif ($rel < 0) {
                $rel = (string)$rel;
            } else {
                $rel = '';
            }
            return "\$this->c('$col',\$l$rel)";
        } elseif ($row < $ml) {
            return "\$this->cg('$col$row')";
        } elseif($row == $this->tableur->lastLine()) {
            return "\$this->derniere('$col')";
        } else {
            $rowDiff = $row - $ml;
            return "\$this->c('$col',\$l+$rowDiff)";
        }
    }



    protected function traductionVariable(array &$expr, int $i): string
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

        if ($name == 'i.typeIntervenant') {
            $variable .= '->getCode()';
        }
        if ($name == 'i.typeVolumeHoraire') {
            $variable .= '->getCode()';
        }

        return $variable;
    }



    protected function traductionFunction(array &$expr, int $i): string
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
            'NOT'               => 'traductionFunctionNot',
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



    protected function traductionFunctionRange(array &$expr, int $i): string
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

        if ($begin['row'] == 0 && $end['row'] == 0){ // range infini
            $begin['row'] = $this->tableur->mainLine();
            $end['row'] = 99999999999;
        }

        if ($begin['col'] === $end['col'] && $begin['row'] <= $this->tableur->mainLine() && $end['row'] >= 500) {
            $col = Calc::numberToLetter($begin['col']);

            return "\$this->$name('$col')";
        }

        return '[PB TRADUCTION FUNCTION RANGE]';
    }



    protected function traductionFunctionIf(array &$expr, int $i): string
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



    protected function traductionFunctionIfs(array &$expr, int $i): string
    {
        $term = $expr[$i];
        $php = '';

        $exprs = $term['exprs'];

        $index = 0;
        while (array_key_exists($index, $exprs)) {
            $test = $exprs[$index];
            $val = $exprs[$index + 1];

            if ($php != ''){
                $php .= 'else';
            }

            $php .= 'if (' . $this->traductionExpr($test) . "){\n";
            $php .= $this->indent($this->returnPhp($this->traductionExpr($val)));
            $php .= '}';
            $index += 2;
        }

        return $php;
    }



    protected function traductionFunctionAnd(array &$expr, int $i): string
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



    protected function traductionFunctionOr(array &$expr, int $i): string
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



    protected function traductionFunctionIsBlank(array &$expr, int $i): string
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



    protected function traductionFunctionSumIf(array &$expr, int $i): string
    {
        $term = $expr[$i];

        $plage = $term['exprs'][0][0];
        $critere = $term['exprs'][1];
        if (isset($term['exprs'][2][0])) {
            $plageSomme = $term['exprs'][2][0];
        } else {
            $plageSomme = $plage;
        }


        $php = "\$val = 0;\n";
        for ($c = 0; $c <= ($plage['colEnd'] - $plage['colBegin']); $c++) {
            $col = Calc::numberToLetter($plage['colBegin'] + $c);
            $colDest = Calc::numberToLetter($plageSomme['colBegin'] + $c);

            $iftest = $critere;
            array_unshift($iftest, ['type' => 'cell', 'name' => $col . $this->tableur->mainLine()]);

            $this->transformer($iftest);
            $iftest = $this->traductionExpr($iftest);

            $sumExpr = [[
                'type' => 'cell',
                'name' => $colDest,
            ]];
            $this->transformer($sumExpr);

            $php .= 'foreach ($this->volumesHoraires as $l => $volumesHoraire) {' . "\n";
            $php .= "  if ($iftest){\n";
            $php .= "    \$val += ".$this->traductionExpr($sumExpr).";\n";
            $php .= "  }\n";
            $php .= "}\n";
        }

        $php .= 'return ' . $this->traductionExpr($term['returnExpr']);

//        echo '<pre>' . htmlentities($php) . '</pre>';

        return $php;
    }



    protected function traductionFunctionMid(array &$expr, int $i): string
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



    protected function traductionFunctionLeft(array &$expr, int $i): string
    {
        $term = $expr[$i];

        $string = $this->traductionExpr($term['exprs'][0]);
        $length = $this->traductionExpr($term['exprs'][1]);

        $php = "substr($string ?? '', 0, $length)";

        return $php;
    }



    protected function traductionFunctionNot(array &$expr, int $i): string
    {
        $term = $expr[$i];

        $expr = $this->traductionExpr($term['exprs'][0]);

        $php = "!(".$expr.')';

        return $php;
    }

}