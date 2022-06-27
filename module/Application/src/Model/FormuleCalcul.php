<?php

namespace Application\Model;

use Unicaen\OpenDocument\Document;
use Unicaen\OpenDocument\Calc\Sheet;
use Unicaen\OpenDocument\Calc;
use UnicaenApp\Util;

class FormuleCalcul
{
    private Sheet $sheet;

    /* Ligne principale : 20 par convention */
    private int     $mainLine = 20;

    private ?string $name     = null;



    public function __construct(?string $filename = null, ?string $name = null)
    {
        if ($filename) {
            $this->loadFromfile($filename);
        }
        if ($name) {
            $this->name = $name;
        }
    }



    public function loadFromfile(string $filename)
    {
        $document = new Document();
        $document->loadFromFile($filename);

        $this->sheet = $document->getCalc()->getSheet(1);
    }



    public function getSheet(): Sheet
    {
        return $this->sheet;
    }



    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }



    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }



    public function getCellsPos(): array
    {
        $cellsPos = [];

        $vhColsRefs = [
            'structure_code'            => 'vh.structure_code',
            'structure_is_affectation'  => 'vh.structure_is_affectation',
            'structure_is_univ'         => 'vh.structure_is_univ',
            'structure_is_exterieure'   => 'vh.structure_is_exterieur',
            'service_statutaire'        => 'vh.service_statutaire',
            'taux_fi'                   => 'vh.taux_fi',
            'taux_fa'                   => 'vh.taux_fa',
            'taux_fc'                   => 'vh.taux_fc',
            'type_intervention_code'    => 'vh.type_intervention_code',
            'taux_service_du'           => 'vh.taux_service_du',
            'taux_service_compl'        => 'vh.taux_service_compl',
            'ponderation_service_du'    => 'vh.ponderation_service_du',
            'ponderation_service_compl' => 'vh.ponderation_service_compl',
            'heures'                    => 'vh.heures',
            'p1'                        => 'vh.param_1',
            'p2'                        => 'vh.param_2',
            'p3'                        => 'vh.param_3',
            'p4'                        => 'vh.param_4',
            'p5'                        => 'vh.param_5',
            'service_fi'                => 'vh.service_fi',
            'service_fa'                => 'vh.service_fa',
            'service_fc'                => 'vh.service_fc',
            'service_referentiel'       => 'vh.service_referentiel',
            'heures_compl_fi'           => 'vh.heures_compl_fi',
            'heures_compl_fa'           => 'vh.heures_compl_fa',
            'heures_compl_fc'           => 'vh.heures_compl_fc',
            'heures_compl_fc_majorees'  => 'vh.heures_compl_fc_majorees',
            'heures_compl_referentiel'  => 'vh.heures_compl_referentiel',
        ];

        $maxCol = Calc::letterToNumber('AC');

        for ($colNum = 1; $colNum <= $maxCol; $colNum++) {
            $cell   = $this->getSheet()->getCellByCoords($colNum, $this->mainLine - 1);
            $colLib = Util::reduce($cell?->getContent() ?? '');
            if (array_key_exists($colLib, $vhColsRefs)) {
                $cellsPos[$vhColsRefs[$colLib]] = Calc::numberToLetter($colNum);
            }
        }

        $iColsRefs = [
            'type_intervenant_code'          => 'i.type_intervenant_code',
            'structure_code'                 => 'i.structure_code',
            'type_volume_horaire_code'       => 'i.type_volume_horaire_code',
            'heures_service_statutaire'      => 'i.heures_service_statutaire',
            'heures_service_modifie'         => 'i.heures_service_modifie',
            'depassement_service_du_sans_hc' => 'i.depassement_service_du_sans_hc',
            'param_1'                        => 'i.param_1',
            'param_2'                        => 'i.param_2',
            'param_3'                        => 'i.param_3',
            'param_4'                        => 'i.param_4',
            'param_5'                        => 'i.param_5',
        ];
        $col       = 3; // C

        for ($ligne = 3; $ligne < 15; $ligne++) {
            $cell   = $this->getSheet()->getCellByCoords($col, $ligne);
            $colLib = Util::reduce($cell?->getContent() ?? '');
            if (array_key_exists($colLib, $iColsRefs)) {
                $cellsPos[$iColsRefs[$colLib]] = 'D' . $ligne;
            }
        }

        return $cellsPos;
    }



    public function getData(): array
    {
        $data = [
            'volumes-horaires' => [],
        ];

        $cellsPos = $this->getCellsPos();
        $s        = $this->getSheet();

        $types = [
            'vh.structure_code'                => 'string',
            'vh.structure_is_affectation'      => 'bool',
            'vh.structure_is_univ'             => 'bool',
            'vh.structure_is_exterieur'        => 'bool',
            'vh.service_statutaire'            => 'bool',
            'vh.taux_fi'                       => 'pourc',
            'vh.taux_fa'                       => 'pourc',
            'vh.taux_fc'                       => 'pourc',
            'vh.type_intervention_code'        => 'string',
            'vh.taux_service_du'               => 'float',
            'vh.taux_service_compl'            => 'float',
            'vh.ponderation_service_du'        => 'float',
            'vh.ponderation_service_compl'     => 'float',
            'vh.heures'                        => 'float',
            'vh.param_1'                       => 'string',
            'vh.param_2'                       => 'string',
            'vh.param_3'                       => 'string',
            'vh.param_4'                       => 'string',
            'vh.param_5'                       => 'string',
            'vh.service_fi'                    => 'float',
            'vh.service_fa'                    => 'float',
            'vh.service_fc'                    => 'float',
            'vh.service_referentiel'           => 'float',
            'vh.heures_compl_fi'               => 'float',
            'vh.heures_compl_fa'               => 'float',
            'vh.heures_compl_fc'               => 'float',
            'vh.heures_compl_fc_majorees'      => 'float',
            'vh.heures_compl_referentiel'      => 'float',
            'i.type_intervenant_code'          => 'string',
            'i.structure_code'                 => 'string',
            'i.type_volume_horaire_code'       => 'string',
            'i.heures_service_statutaire'      => 'float',
            'i.heures_service_modifie'         => 'float',
            'i.depassement_service_du_sans_hc' => 'bool',
            'i.param_1'                        => 'string',
            'i.param_2'                        => 'string',
            'i.param_3'                        => 'string',
            'i.param_4'                        => 'string',
            'i.param_5'                        => 'string',
        ];

        foreach ($cellsPos as $variable => $cellName) {
            if (str_starts_with($variable, 'i.')) {
                $val = $s->getCell($cellsPos[$variable])->getContent();
                switch ($types[$variable]) {
                    case 'bool':
                        $val = 'oui' == strtolower($val);
                    break;
                    case 'float';
                        $val = (float)$val;
                    break;
                    case 'pourc';
                        $val = trim(str_replace('%', '', $val));
                        $val = (float)$val / 100;
                    break;
                }
                $data[$variable] = $val;
            }
        }

        for ($line = $this->mainLine; $line <= $this->mainLine + 500; $line++) {
            $structureCodeCell = $s->getCell($cellsPos['vh.structure_code'] . $line);
            if (!$structureCodeCell) break;

            $heuresCell = $s->getCell($cellsPos['vh.heures'] . $line);
            if (!$heuresCell) break;

            $structureCode = $structureCodeCell->getContent();
            $heures        = (float)$s->getCell($cellsPos['vh.heures'] . $line)->getContent();
            if ($structureCode == '' && $heures == 0) break;

            foreach ($cellsPos as $variable => $cellName) {
                if (str_starts_with($variable, 'vh.')) {
                    $val = $s->getCell($cellsPos[$variable] . $line)->getContent();
                    switch ($types[$variable]) {
                        case 'bool':
                            $val = 'oui' == strtolower($val);
                        break;
                        case 'float';
                            $val = (float)$val;
                        break;
                        case 'pourc';
                            $val = trim(str_replace('%', '', $val));
                            $val = (float)$val / 100;
                        break;
                    }
                    $data['volumes-horaires'][$line][$variable] = $val;
                }
            }
        }

        return $data;
    }



    public function makePackageDef(): string
    {
        $def = "CREATE OR REPLACE PACKAGE FORMULE_" . $this->getName() . " AS

  PROCEDURE CALCUL_RESULTAT;

  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT;

  FUNCTION INTERVENANT_QUERY RETURN CLOB;
  FUNCTION VOLUME_HORAIRE_QUERY RETURN CLOB;

END FORMULE_" . $this->getName() . ";";

        return $def;
    }



    public function makePackageBody(): string
    {
        $s        = $this->getSheet();
        $formules = [];

        $cellsPos = $this->getCellsPos();

        $minCol = Calc::letterToNumber($cellsPos['vh.service_fi']);

        for ($col = $minCol; $col <= $s->getMaxCol(); $col++) {
            for ($row = 1; $row <= $this->mainLine; $row++) {
                $cell = $s->getCellByCoords($col, $row);
                if ($formule = $cell?->getFormule()) {
                    $formules[Calc::coordsToCellName($col, $row)] = $cell;
                }
            }
        }
        $cells = '';

        foreach ($formules as $name => $cell) {
            $expr = $cell->getFormuleExpr();

            $mls = (string)$this->mainLine;
            if (str_ends_with($name, $mls)) {
                $name = substr($name, 0, -strlen($mls));
            }

            $cellPlsql = "-- $name" . substr($cell->getFormule(), 3) . "\n";
            $cellPlsql .= "WHEN c = '$name' AND v >= 1 THEN\n";
            $cellPlsql .= $this->indent($this->exprToPlSql($expr)) . "\n\n";

            $cells .= $this->indent($cellPlsql, 3);
        }

        $body = file_get_contents(getcwd() . '/data/formule.sql');
        $body = str_replace('<--CELLS-->', $cells, $body);
        $body = str_replace('<--NAME-->', $this->getName(), $body);
        foreach ($cellsPos as $param => $cell) {
            $body = str_replace("<--$param-->", "'" . $cell . "'", $body);
        }

        return $body;
    }



    private function exprToPlSql(array $expr): string
    {
        $this->preTraitementExpr($expr);
        $this->preTraitementExpr23($expr);
        $this->preTraitementExpr23($expr);

        $plsql = $this->traductionExpr($expr);
        $plsql = $this->returnPlsql($plsql);

        return $plsql;
    }



    private function returnPlsql(string $plsql): string
    {
        if (!(str_starts_with($plsql, 'IF') || str_starts_with($plsql, 'RETURN'))) {
            $plsql = 'RETURN ' . $plsql;
        }

        if (!str_ends_with($plsql, ';')) {
            $plsql .= ';';
        }

        return $plsql;
    }



    private function preTraitementExpr(array &$expr)
    {
        // Première passe de traitement
        foreach ($expr as $i => $item) {
            if (isset($expr[$i])) {
                if ($item['type'] === 'function' && !empty($item['exprs'])) {
                    foreach ($item['exprs'] as $itexp => $sitem) {
                        $this->preTraitementExpr($expr[$i]['exprs'][$itexp]);
                    }
                } elseif ($item['type'] === 'expr' && !empty($item['expr'])) {
                    $this->preTraitementExpr($expr[$i]['expr']);
                }

                switch ($item['type']) {
                    case 'function':
                        $this->preTraitementFunction($expr, $i);
                    break;
                    case 'cell':
                        $this->preTraitementCell($expr, $i);
                    break;
                }
            }
        }
    }



    private function preTraitementExpr23(array &$expr)
    {
        // Deuxième et troisième passe de prétraitement
        foreach ($expr as $i => $item) {
            if (isset($expr[$i])) {
                if ($item['type'] === 'function' && !empty($item['exprs'])) {
                    foreach ($item['exprs'] as $itexp => $sitem) {
                        $this->preTraitementExpr23($expr[$i]['exprs'][$itexp]);
                    }
                } elseif ($item['type'] === 'expr' && !empty($item['expr'])) {
                    $this->preTraitementExpr23($expr[$i]['expr']);
                }

                switch ($item['type']) {
                    case 'op':
                        $this->preTraitementOp($expr, $i);
                    break;
                }
            }
        }
    }



    private function preTraitementFunction(array &$expr, int $i)
    {
        // on supprime les ISERROR dans les tests
        if ($expr[$i]['name'] === 'IF') {
            $tfi = $expr[$i]['exprs'][0][0];
            if ($tfi['type'] === 'function' && $tfi['name'] === 'ISERROR') {
                $expr[$i] = $expr[$i]['exprs'][2][0];
                //$expr = array_splice($expr, $i, 1, $expr[$i]['exprs'][2]);
                //$expr[$i] = $expr[$i]['exprs'][2];

                return;
            }
        }

        // report des opération faites avant et après les IF à l'intérieur des expressions de résultat du IF
        if ($expr[$i]['name'] === 'IF' && count($expr) > 1) {
            $oriThen = $expr[$i]['exprs'][1];
            if (count($oriThen) > 1) {
                $oriThen = ['type' => 'expr', 'expr' => $oriThen];
            } else {
                $oriThen = $oriThen[0];
            }
            $then = [];

            if (isset($expr[$i]['exprs'][2])) {
                $oriElse = $expr[$i]['exprs'][2];
                if (count($oriElse) > 1) {
                    $oriElse = ['type' => 'expr', 'expr' => $oriElse];
                } else {
                    $oriElse = $oriElse[0];
                }
                $else = [];
            }
            $hasElse = isset($oriElse);

            foreach ($expr as $a => $aitem) {
                if ($a != $i) {
                    $then[] = $aitem;
                    if ($hasElse) {
                        $else[] = $aitem;
                    }
                    unset($expr[$a]);
                } else {
                    $then[] = $oriThen;
                    if ($hasElse) {
                        $else[] = $oriElse;
                    }
                }
            }

            $expr[$i]['exprs'][1] = $then;
            if ($hasElse) {
                $expr[$i]['exprs'][2] = $else;
            }
        }
    }



    private function preTraitementCell(array &$expr, int $i)
    {
        // On retire les $ qui sont inutiles pour la conversion en Pl/SQL
        $expr[$i]['name'] = str_replace('$', '', $expr[$i]['name']);
    }



    private function isNumber0(array $term)
    {
        if ($term['type'] === 'number') {
            if ($term['value'] === 0) {
                return true;
            }
        }

        return false;
    }



    private function isNumber1(array $term)
    {
        if ($term['type'] === 'number') {
            if ($term['value'] === 1) {
                return true;
            }
        }

        return false;
    }



    private function preTraitementOp(array &$expr, int $i)
    {
        if ($expr[$i]['name'] === '*') {
            if ($this->isNumber0($expr[$i - 1])) {
                unset($expr[$i]);
                unset($expr[$i + 1]);
                $expr = array_values($expr);
            } elseif ($this->isNumber0($expr[$i + 1])) {
                unset($expr[$i - 1]);
                unset($expr[$i]);
                $expr = array_values($expr);
            } elseif ($this->isNumber1($expr[$i - 1])) {
                unset($expr[$i - 1]);
                unset($expr[$i]);
                $expr = array_values($expr);
            } elseif ($this->isNumber1($expr[$i + 1])) {
                unset($expr[$i]);
                unset($expr[$i + 1]);
                $expr = array_values($expr);
            }
        } elseif ($expr[$i]['name'] === '/') {
            if ($this->isNumber0($expr[$i - 1])) {
                unset($expr[$i]);
                unset($expr[$i + 1]);
                $expr = array_values($expr);
            }
        } elseif ($expr[$i]['name'] === '+' || $expr[$i]['name'] === '-') {
            if ($this->isNumber0($expr[$i - 1])) {
                unset($expr[$i - 1]);
                unset($expr[$i]);
                $expr = array_values($expr);
            } elseif ($this->isNumber0($expr[$i + 1])) {
                unset($expr[$i]);
                unset($expr[$i + 1]);
                $expr = array_values($expr);
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
        ];


        $plsql = '';

        $isIf = false;
        foreach ($expr as $i => $term) {
            if ($term['type'] === 'function' && $term['name'] === 'IF') {
                $isIf = true;
            }
            if (array_key_exists($term['type'], $methods)) {
                $plsql .= $this->{$methods[$term['type']]}($expr[$i]);
            } else {
                $plsql .= '[PB TRADUCTION]';
            }
        }

        return $plsql;
    }



    private function traductionSousExpr(array &$term): string
    {
        return "(" . $this->traductionExpr($term['expr']) . ")";
    }



    private function traductionString(array &$term): string
    {
        return "'" . str_replace("'", "''", $term['content']) . "'";
    }



    private function traductionNumber(array &$term): string
    {
        return (string)$term['value'];
    }



    private function traductionOperator(array &$term): string
    {
        return ' ' . $term['name'] . ' ';
    }



    private function traductionRange(array &$term): string
    {
        return '[TODO]';
    }



    private function traductionCell(array &$term): string
    {
        ['col' => $col, 'row' => $row] = Calc::cellNameToCoords($term['name']);

        $col = Calc::numberToLetter($col);

        if ($row === $this->mainLine) {
            return "cell('$col',l)";
        } else {
            return "cell('" . $term['name'] . "')";
        }
    }



    private function traductionVariable(array &$term): string
    {
        return $term['name'];
    }



    private function traductionFunction(array &$term): string
    {
        $functions = [
            'IF' => 'traductionFunctionIf',
        ];

        if (array_key_exists($term['name'], $functions)) {
            return $this->{$functions[$term['name']]}($term);
        }

        $plsql = $term['name'] . '(';
        if (!empty($term['exprs'])) {
            $plExprs = [];
            foreach ($term['exprs'] as $e => $fExpr) {
                $plExprs[$e] = $this->traductionExpr($fExpr);
            }
            $plsql .= implode(', ', $plExprs);
        }
        $plsql .= ')';

        return $plsql;
    }



    private function traductionFunctionIf(array $term): string
    {
        $cond = $term['exprs'][0];
        $then = $term['exprs'][1];
        if (isset($term['exprs'][2])) {
            $else = $term['exprs'][2];
        } else {
            $else = null;
        }

        $plsql = 'IF ' . $this->traductionExpr($cond, false) . " THEN\n";
        $plsql .= $this->indent($this->returnPlsql($this->traductionExpr($then)));
        if ($else) {
            $plsql .= "ELSE\n";
            $plsql .= $this->indent($this->returnPlsql($this->traductionExpr($else)));
        }
        $plsql .= 'END';

        return $plsql;
    }



    private function indent(string $plsql, int $levels = 1): string
    {

        $lines  = explode("\n", $plsql);
        $result = '';
        foreach ($lines as $line) {
            for ($i = 0; $i < $levels; $i++) {
                $result .= "  ";
            }
            $result .= $line . "\n";
        }

        return $result;
    }
}