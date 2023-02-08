<?php

namespace Application\Model;

use Unicaen\OpenDocument\Calc\Formule;
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

    private array   $cellsPos = [];

    private string  $currentCellName;



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

        $this->sheet    = $document->getCalc()->getSheet(1);
        $this->cellsPos = [];
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
        if (!empty($this->cellsPos)) {
            return $this->cellsPos;
        }

        $vhColsRefs = [
            'structure_code'            => 'vh.structure_code',
            'structure_is_affectation'  => 'vh.structure_is_affectation',
            'structure_is_univ'         => 'vh.structure_is_univ',
            'structure_is_exterieure'   => 'vh.structure_is_exterieur',
            'service_statutaire'        => 'vh.service_statutaire',
            'taux_fi'                   => 'vh.taux_fi',
            'taux_fa'                   => 'vh.taux_fa',
            'taux_fc'                   => 'vh.taux_fc',
            'type_volume_horaire_code'  => 'vh.type_volume_horaire_code',
            'type_intervention_code'    => 'vh.type_intervention_code',
            'taux_service_du'           => 'vh.taux_service_du',
            'taux_service_compl'        => 'vh.taux_service_compl',
            'ponderation_service_du'    => 'vh.ponderation_service_du',
            'ponderation_service_compl' => 'vh.ponderation_service_compl',
            'heures'                    => 'vh.heures',
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
                $this->cellsPos[$vhColsRefs[$colLib]] = Calc::numberToLetter($colNum);
            }
        }

        for ($colNum = 1; $colNum <= $maxCol; $colNum++) {
            $cell   = $this->getSheet()->getCellByCoords($colNum, $this->mainLine - 2);
            $colLib = Util::reduce($cell?->getContent() ?? '');
            if (str_contains($colLib, Util::reduce('specifique'))) {
                for ($pnum = 1; $pnum <= 5; $pnum++) {
                    $cell = $this->getSheet()->getCellByCoords($colNum - 1 + $pnum, $this->mainLine - 1);
                    $name = $cell?->getContent();
                    if (!in_array($name, ['P1', 'P2', 'P3', 'P4', 'P5'])) {
                        $this->cellsPos['vh.param_' . $pnum] = Calc::numberToLetter($colNum - 1 + $pnum);
                    }
                }
                break;
            }
        }

        $iColsRefs = [
            'type_intervenant_code'          => 'i.type_intervenant_code',
            'structure_code'                 => 'i.structure_code',
            'heures_service_statutaire'      => 'i.heures_service_statutaire',
            'heures_service_modifie'         => 'i.heures_service_modifie',
            'depassement_service_du_sans_hc' => 'i.depassement_service_du_sans_hc',
        ];
        $col       = 3; // C

        for ($ligne = 3; $ligne < 15; $ligne++) {
            $cell   = $this->getSheet()->getCellByCoords($col, $ligne);
            $colLib = Util::reduce($cell?->getContent() ?? '');
            if (array_key_exists($colLib, $iColsRefs)) {
                $this->cellsPos[$iColsRefs[$colLib]] = 'D' . $ligne;
            }
        }

        ['col' => $null, 'row' => $dsdshc] = Calc::cellNameToCoords($this->cellsPos['i.depassement_service_du_sans_hc']);

        for ($pnum = 1; $pnum <= 5; $pnum++) {
            $cell = $this->getSheet()->getCellByCoords($col + 1, $dsdshc + $pnum);
            if ($cell?->getContent()) {
                $this->cellsPos['i.param_' . $pnum] = Calc::coordsToCellName($col + 1, $dsdshc + $pnum);
            }
        }

        return $this->cellsPos;
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
            'vh.type_volume_horaire_code'      => 'string',
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



    public function getParams(): array
    {
        $params   = [];
        $cellsPos = $this->getCellsPos();

        for ($i = 1; $i <= 5; $i++) {
            if (isset($cellsPos['i.param_' . $i])) {
                $lcp = Calc::cellNameToCoords($cellsPos['i.param_' . $i]);
                $lcp['col']--;
                $cell                    = $this->getSheet()->getCellByCoords($lcp['col'], $lcp['row']);
                $params['i.param_' . $i] = $cell->getContent();
            }
            if (isset($cellsPos['vh.param_' . $i])) {
                $lcp                      = Calc::cellNameToCoords($cellsPos['vh.param_' . $i]);
                $lcp['row']               = $this->mainLine - 1;
                $cell                     = $this->getSheet()->getCellByCoords($lcp['col'], $lcp['row']);
                $params['vh.param_' . $i] = $cell->getContent();
            }
        }

        return $params;
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



    public function getFormuleCells(): array
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


        /** @var Calc\Cell[] $formules */
        foreach ($formules as $name => $cell) {
            /* On recherche les cases supplémentaires qui manqueraient */
            $mls = (string)$this->mainLine;
            if (str_ends_with($name, $mls)) {
                $name = substr($name, 0, -strlen($mls));
            }

            $deps = $cell->getDeps();
            foreach ($deps as $dep) {
                $dep = str_replace('$', '', $dep);
                if (!array_key_exists($dep, $formules)) {
                    $depCoords = Calc::cellNameToCoords($dep);
                    if ($depCoords['col'] > 11) { // > K
                        $found = false;

                        foreach ($cellsPos as $cp) {
                            if ($cp . $mls === $dep) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $cellDep        = $this->getSheet()->getCell($dep);
                            $formules[$dep] = $cellDep;
                        }
                    }
                }
            }
        }

        return $formules;
    }



    public function makePackageBody(?string $intervenantQuery = null, ?string $volumeHoraireQuery = null): string
    {
        if (!$intervenantQuery) {
            $intervenantQuery = 'SELECT
      fi.*,
      NULL param_1,
      NULL param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_INTERVENANT fi';
        }

        if (!$volumeHoraireQuery) {
            $volumeHoraireQuery = 'SELECT
      fvh.*,
      NULL param_1,
      NULL param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_VOLUME_HORAIRE fvh
    ORDER BY
      ordre';
        }

        $s        = $this->getSheet();
        $formules = $this->getFormuleCells();

        $cells = "\n\n\n";

        foreach ($formules as $name => $cell) {
            $this->currentCellName = $name;

            $expr = $cell->getFormuleExpr();
            $this->exprInit($expr);

            $mls = (string)$this->mainLine;
            if (str_ends_with($name, $mls)) {
                $name = substr($name, 0, -strlen($mls));
            }

            $cellPlsql = "-- $name" . (substr($cell->getFormule(), 3) ?? $cell->getValue()) . "\n";
            $cellPlsql .= "WHEN '$name' THEN\n";
            $cellPlsql .= $this->indent($this->exprToPlSql($expr)) . "\n\n";

            $cells .= $this->indent($cellPlsql, 3);
        }

        $body     = file_get_contents(getcwd() . '/data/formule.sql');
        $body     = str_replace('<--DECALAGE-->', $this->mainLine - 1, $body);
        $body     = str_replace('<--CELLS-->', $cells, $body);
        $body     = str_replace('<--NAME-->', $this->getName(), $body);
        $body     = str_replace('<--INTERVENANT_QUERY-->', $intervenantQuery, $body);
        $body     = str_replace('<--VOLUME_HORAIRE_QUERY-->', $volumeHoraireQuery, $body);
        $cellsPos = $this->getCellsPos();
        foreach ($cellsPos as $param => $cell) {
            $body = str_replace("<--$param-->", "'" . $cell . "'", $body);
        }

        return $body;
    }



    public function testFormule(string $cellName, string $formule)
    {
        $this->currentCellName = $cellName;

        $tableur = $formule;
        $formule = new Formule($tableur);

        $expr = $formule->analyse();
        $this->exprInit($expr);

        $plsql = $this->exprToPlSql($expr);
        \UnicaenCode\Util::highlight($plsql, 'plsql', true, ['show-line-numbers' => true]);

        $formule->displayTerms();
        $formule->displayExprs();
    }



    private function exprInit(array &$expr)
    {
        if ($this->exprIsTest($expr)) {
            $expr = $this->exprTestToIf($expr);
        }
    }



    private function exprIsTest(array &$expr): bool
    {
        if (count($expr) === 1 && $expr[0]['type'] === 'function') {
            switch ($expr[0]['name']) {
                case 'AND':
                    return true;
                case 'OR':
                    return true;
                case 'NOT':
                    return true;
            }

            return false;
        }

        foreach ($expr as $term) {
            if ($term['type'] === 'op') {
                switch ($term['name']) {
                    case '=';
                        return true;
                    case '>=';
                        return true;
                    case '<=';
                        return true;
                    case '<>';
                        return true;
                    case '>';
                        return true;
                    case '<';
                        return true;
                }
            }
        }

        return false;
    }



    private function exprTestToIf(array $expr): array
    {
        return [
            [
                'type'  => 'function',
                'name'  => 'IF',
                'exprs' => [
                    $expr,
                    [
                        [
                            'type'  => 'number',
                            'value' => 1.0,
                        ],
                    ],
                    [
                        [
                            'type'  => 'number',
                            'value' => 0,
                        ],
                    ],
                ],
            ],
        ];
    }



    private function exprToTest(array $expr): array
    {
        $res = [];
        if (count($expr) === 1) {
            $res = $expr;
        } else {
            $res = [['type' => 'expr', 'expr' => $expr]];
        }


        $res[] = [
            'type' => 'op',
            'name' => '=',
        ];
        $res[] = [
            'type'  => 'number',
            'value' => 1.0,
        ];

        return $res;
    }



    private function exprToPlSql(array $expr): string
    {
        $this->preTraitementExpr($expr);
        $this->preTraitementExpr($expr);
        $this->preTraitementExpr23($expr);
        $this->preTraitementExpr23($expr);

        $plsql = $this->traductionExpr($expr);
        $plsql = $this->returnPlsql($plsql);

        return $plsql;
    }



    private function returnPlsql(string $plsql): string
    {
        if (!(str_starts_with($plsql, 'IF') || str_starts_with($plsql, 'RETURN') || str_contains($plsql, 'sumIfRow'))) {
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
                    case 'range':
                        $this->preTraitementRange($expr, $i);
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

        // report des opération faites avant et après les SUMIF à l'intérieur des expressions de résultat du SUMIF
        if ($expr[$i]['name'] === 'SUMIF' && count($expr) > 1) {
            $expr[$i]['valExpr'] = [];
            foreach ($expr as $a => $aitem) {
                if ($a != $i) {
                    $expr[$i]['valExpr'][] = $aitem;
                    unset($expr[$a]);
                } else {
                    $expr[$i]['valExpr'][] = ['type' => 'plsql', 'code' => 'val'];
                }
            }
        }
    }



    private function preTraitementCell(array &$expr, int $i)
    {
        // On retire les $ qui sont inutiles pour la conversion en Pl/SQL
        $an               = $expr[$i]['name'];
        $expr[$i]['name'] = str_replace('$', '', $expr[$i]['name']);

        // On voit comme absolue toute référence fixe, CAD si ligne < 20, pour éviter des soucis par la suite
        ['col' => $col, 'row' => $row] = Calc::cellNameToCoords($this->currentCellName);
        if ($row < 20) {
            $an = str_replace('19', '$19', $an);
            $an = str_replace('$$', '$', $an);
        }

        if (!isset($expr[$i]['absName'])) {
            $expr[$i]['absName'] = $an;
        }
    }



    private function preTraitementRange(array &$expr, int $i)
    {
        // On retire les $ qui sont inutiles pour la conversion en Pl/SQL
        $expr[$i]['begin'] = str_replace('$', '', $expr[$i]['begin']);
        $expr[$i]['end']   = str_replace('$', '', $expr[$i]['end']);
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
            if ($term['value'] === 1.0) {
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
            if ($term !== null) {
                if ($term['type'] === 'function' && $term['name'] === 'IF') {
                    $isIf = true;
                }
                if (array_key_exists($term['type'], $methods)) {
                    $plsql .= $this->{$methods[$term['type']]}($expr, $i);
                } elseif ($term['type'] === 'plsql') {
                    $plsql .= $term['code'];
                } else {
                    $plsql .= '[PB TRADUCTION]';
                }
            }
        }

        return $this->postReplace($plsql);
    }



    private function traductionSousExpr(array &$expr, int $i): string
    {
        $term = $expr[$i];

        return "(" . $this->traductionExpr($term['expr']) . ")";
    }



    private function traductionString(array &$expr, int $i): string
    {
        $term = $expr[$i];

        return "'" . str_replace("'", "''", $term['content']) . "'";
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

        $col = Calc::numberToLetter($col);

        $absName = $term['absName'] ?? $term['name'];
        if (str_starts_with($absName, '$')) {
            $absName = substr($absName, 1); // on élimine le premier $ lié à la colonne
        }

        $absolute = str_contains($absName, '$'); // si on en trouve un autre => ligne relative!!

        if ($row === $this->mainLine) {
            $cellsPos = $this->getCellsPos();
            foreach ($cellsPos as $variable => $column) {
                if ($col === $column) {
                    return $variable;
                }
            }
        }

        if ($row < $this->mainLine - 1 || $absolute) {
            return "cell('" . $term['name'] . "')";
        } else {
            $rowDiff = $row - $this->mainLine;

            if ($rowDiff < 0) {
                return "cell('$col',l$rowDiff)";
            } elseif ($rowDiff == 0) {
                return "cell('$col',l)";
            } else {
                return "cell('$col',l+$rowDiff)";
            }
        }
    }



    private function traductionVariable(array &$expr, int $i): string
    {
        $term        = $expr[$i];
        $traductions = [
            'i_type_intervenant_code'          => 'i.type_intervenant_code',
            'i_structure_code'                 => 'i.structure_code',
            'i_type_volume_horaire_code'       => 'vh.type_volume_horaire_code',
            'i_heures_decharge'                => 'i.heures_service_statutaire',
            'i_heures_service_modifie'         => 'i.heures_service_modifie',
            'i_depassement_service_du_sans_hc' => 'i.depassement_service_du_sans_hc',
            'i_service_du'                     => 'i.service_du',
            'i_param_1'                        => 'i.param_1',
            'i_param_2'                        => 'i.param_2',
            'i_param_3'                        => 'i.param_3',
            'i_param_4'                        => 'i.param_4',
            'i_param_5'                        => 'i.param_5',
        ];

        if (isset($traductions[$term['name']])) {
            return $traductions[$term['name']];
        }

        return $term['name'];
    }



    private function traductionFunction(array &$expr, int $i): string
    {
        $term      = $expr[$i];
        $functions = [
            'IF'      => 'traductionFunctionIf',
            'AND'     => 'traductionFunctionAnd',
            'OR'      => 'traductionFunctionOr',
            'ISBLANK' => 'traductionFunctionIsBlank',
            'SUMIF'   => 'traductionFunctionSumIf',
            'MID'     => 'traductionFunctionMid',
        ];

        if (array_key_exists($term['name'], $functions)) {
            return $this->{$functions[$term['name']]}($expr, $i);
        }

        if (isset($term['exprs']) && 1 === count($term['exprs']) && 1 === count($term['exprs'][0]) && $term['exprs'][0][0]['type'] === 'range') {
            return $this->traductionFunctionRange($expr, $i);
        }

        $tradNames = [
            'MIN' => 'LEAST',
            'MAX' => 'GREATEST',
        ];

        if (isset($tradNames[$term['name']])) {
            $plsql = $tradNames[$term['name']] . '(';
        } else {
            $plsql = $term['name'] . '(';
        }

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



    private function traductionFunctionRange(array &$expr, int $i): string
    {
        $functions = [
            'MAX' => 'max',
            'SUM' => 'somme',
        ];

        $term  = $expr[$i];
        $range = $term['exprs'][0][0];

        $name = $functions[$term['name']] ?? $term['name'];

        $begin = Calc::cellNameToCoords($range['begin']);
        $end   = Calc::cellNameToCoords($range['end']);

        if ($begin['col'] === $end['col'] && $begin['row'] <= $this->mainLine && $end['row'] > 500) {
            $col = Calc::numberToLetter($begin['col']);

            return "calcFnc('$name','$col')";
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

        if (!$this->exprIsTest($cond)) {
            $cond = $this->exprToTest($cond);
        }

        $plsql = 'IF ' . $this->traductionExpr($cond, false) . " THEN\n";
        $plsql .= $this->indent($this->returnPlsql($this->traductionExpr($then)));
        if ($else) {
            $plsql .= "ELSE\n";
            $plsql .= $this->indent($this->returnPlsql($this->traductionExpr($else)));
        }
        $plsql .= 'END IF';

        return $plsql;
    }



    private function traductionFunctionAnd(array &$expr, int $i): string
    {
        $term  = $expr[$i];
        $plsql = '';

        if (!empty($term['exprs'])) {
            $plExprs = [];
            foreach ($term['exprs'] as $e => $fExpr) {
                $fExpr[]     = null;
                $plExprs[$e] = $this->traductionExpr($fExpr);
            }
            $plsql .= implode(' AND ', $plExprs);
        }

        if (count($expr) === 1) {
            return $plsql;
        } else {
            return '(' . $plsql . ')';
        }
    }



    private function traductionFunctionOr(array &$expr, int $i): string
    {
        $term  = $expr[$i];
        $plsql = '';

        if (!empty($term['exprs'])) {
            $plExprs = [];
            foreach ($term['exprs'] as $e => $fExpr) {
                $fExpr[]     = null;
                $plExprs[$e] = $this->traductionExpr($fExpr);
            }
            $plsql .= implode(' OR ', $plExprs);
        }

        if (count($expr) === 1) {
            return $plsql;
        } else {
            return '(' . $plsql . ')';
        }
    }



    private function traductionFunctionIsBlank(array &$expr, int $i): string
    {
        $term = $expr[$i];
        $test = $term['exprs'][0];

        if (1 === count($test)) {
            $plsql = $this->traductionExpr($test) . ' IS NULL';
        } elseif (count($test) > 1) {
            $plsql = '(' . $this->traductionExpr($test) . ') IS NULL';
        }

        return $plsql;
    }



    private function traductionFunctionSumIf(array &$expr, int $i): string
    {
        $term = $expr[$i];

//SUMIF([.AD21:.AD$500];[.AD20];[.M21:.$M500])
        $plage   = $term['exprs'][0][0];
        $critere = $term['exprs'][1];
        if (isset($term['exprs'][2][0])) {
            $plageSomme = $term['exprs'][2][0];
        } else {
            $plageSomme = $plage;
        }

        if ($critere[0]['type'] != 'op') { // ajout du =, valeuir par défaut
            $cc      = $critere;
            $critere = [['type' => 'op', 'name' => '=']];
            foreach ($cc as $c) {
                $critere[] = $c;
            }
        }

//        var_dump($plage);
//        var_dump($critere);
//        var_dump($plageSomme);

        $plsql = "val := 0;\n";
        for ($c = 0; $c <= ($plage['colEnd'] - $plage['colBegin']); $c++) {
            $col     = Calc::numberToLetter($plage['colBegin'] + $c);
            $colDest = Calc::numberToLetter($plageSomme['colBegin'] + $c);

            $rowBegin = $plage['rowBegin'] - $this->mainLine;
            if ($rowBegin === 0) {
                $rowBegin = 'l';
            } else {
                $rowBegin = 'l + ' . $rowBegin;
            }

            if ($plage['rowEnd'] >= 500) {
                $rowEnd = 'ose_formule.volumes_horaires.length';
            } else {
                $rowEnd = (string)($plage['rowEnd'] - $this->mainLine);
            }

            $plsql .= "FOR sumIfRow IN $rowBegin .. $rowEnd LOOP\n";
            $plsql .= "  IF cell('" . $col . "',sumIfRow)" . $this->traductionExpr($critere) . " THEN\n";
            $plsql .= "    val := val + cell('" . $colDest . "',sumIfRow);\n";
            $plsql .= "  END IF;\n";
            $plsql .= "END LOOP;\n";
        }

        $plsql .= 'RETURN ' . $this->traductionExpr($term['valExpr']);

//        echo '<pre>' . htmlentities($plsql) . '</pre>';

        return $plsql;
    }



    private function traductionFunctionMid(array &$expr, int $i): string
    {
        $term  = $expr[$i];
        $plsql = 'SUBSTR(';

        $plExprs = [];
        foreach ($term['exprs'] as $e => $fExpr) {
            $fExpr[]     = null;
            $plExprs[$e] = $this->traductionExpr($fExpr);
        }
        $plsql .= implode(', ', $plExprs);

        $plsql .= ')';

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



    private function postReplace(string $plsql): string
    {
        $boolSar = [
            'vh.service_statutaire',
            'vh.structure_is_exterieur',
            'vh.structure_is_affectation',
            'vh.structure_is_univ',
            'i.depassement_service_du_sans_hc',
        ];

        $sar = [
            "vh.type_intervention_code = 'Référentiel'"  => 'vh.volume_horaire_ref_id IS NOT NULL',
            "vh.type_intervention_code <> 'Référentiel'" => 'vh.volume_horaire_ref_id IS NULL',
            "vh.structure_code = cell('K10')"            => 'vh.structure_is_univ',
            "vh.structure_code <> cell('K10')"           => 'NOT vh.structure_is_univ',
            "vh.structure_code = i.structure_code"       => 'vh.structure_is_affectation',
            "i.structure_code = vh.structure_code"       => 'vh.structure_is_affectation',
            "vh.structure_code <> i.structure_code"      => 'NOT vh.structure_is_affectation',
            "i.structure_code <> vh.structure_code"      => 'NOT vh.structure_is_affectation',
        ];

        foreach ($boolSar as $variable) {
            $sar[$variable . " = 'Oui'"]  = $variable;
            $sar[$variable . " <> 'Oui'"] = 'NOT ' . $variable;
            $sar[$variable . " = 'Non'"]  = 'NOT ' . $variable;
            $sar[$variable . " <> 'Non'"] = $variable;
        }

        $result = str_replace(array_keys($sar), array_values($sar), $plsql);

        return $result;
    }
}