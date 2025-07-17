<?php

namespace Formule\Model;


use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\Db\Formule;
use Formule\Entity\Db\FormuleTestIntervenant;
use Formule\Entity\Db\FormuleTestVolumeHoraire;
use Intervenant\Entity\Db\TypeIntervenant;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use Unicaen\OpenDocument\Calc;
use Unicaen\OpenDocument\Calc\Sheet;
use UnicaenApp\Util;

class FormuleTableur
{
    use ContextServiceAwareTrait;

    private Calc $tableur;
    private Sheet $sheet;

    private int $mainLine = 20;
    private int $lastLine = 500;
    private int $version = 0;
    private bool $lu = false;

    //@formatter:off
    private array $variables = [
        'i.typeIntervenant'            => ['name' => 'type_intervenant_code',          'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.structureCode'              => ['name' => 'structure_code',                 'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.typeVolumeHoraire'          => ['name' => 'type_volume_horaire_code',       'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.heuresServiceStatutaire'    => ['name' => ['heures_service_statutaire','heures_decharge'],      'type' => 'float',  'vmin' => 1, 'result' => false],
        'i.heuresServiceModifie'       => ['name' => 'heures_service_modifie',         'type' => 'float',  'vmin' => 1, 'result' => false],
        'i.serviceDu'                  => ['name' => 'i_service_du',                   'type' => 'float',  'vmin' => 1, 'result' => false],
        'i.depassementServiceDuSansHC' => ['name' => 'depassement_service_du_sans_hc', 'type' => 'bool',   'vmin' => 1, 'result' => false],
        'i.param1'                     => ['name' => ['p1', 'param_1'],                'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.param2'                     => ['name' => ['p2', 'param_2'],                'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.param3'                     => ['name' => ['p3', 'param_3'],                'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.param4'                     => ['name' => ['p4', 'param_4'],                'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.param5'                     => ['name' => ['p5', 'param_5'],                'type' => 'string', 'vmin' => 1, 'result' => false],

        'vh.structureCode'               => ['name' => 'structure_code',               'type' => 'string', 'vmin' => 1, 'result' => false],
        'vh.structureAffectation'        => ['name' => 'structure_is_affectation',     'type' => 'bool',   'vmin' => 1, 'result' => false],
        'vh.structureUniv'               => ['name' => 'structure_is_univ',            'type' => 'bool',   'vmin' => 1, 'result' => false],
        'vh.structureExterieur'          => ['name' => 'structure_is_exterieure',      'type' => 'bool',   'vmin' => 2, 'result' => false],
        'vh.serviceStatutaire'           => ['name' => 'service_statutaire',           'type' => 'bool',   'vmin' => 1, 'result' => false],
        'vh.nonPayable'                  => ['name' => 'heures_non_payables',          'type' => 'bool',   'vmin' => 3, 'result' => false],
        'vh.tauxFi'                      => ['name' => 'taux_fi',                      'type' => 'pourc',  'vmin' => 1, 'result' => false],
        'vh.tauxFa'                      => ['name' => 'taux_fa',                      'type' => 'pourc',  'vmin' => 1, 'result' => false],
        'vh.tauxFc'                      => ['name' => 'taux_fc',                      'type' => 'pourc',  'vmin' => 1, 'result' => false],
        'vh.typeInterventionCode'        => ['name' => 'type_intervention_code',       'type' => 'string', 'vmin' => 1, 'result' => false],
        'vh.tauxServiceDu'               => ['name' => 'taux_service_du',              'type' => 'float',  'vmin' => 1, 'result' => false],
        'vh.tauxServiceCompl'            => ['name' => 'taux_service_compl',           'type' => 'float',  'vmin' => 1, 'result' => false],
        'vh.ponderationServiceDu'        => ['name' => 'ponderation_service_du',       'type' => 'float',  'vmin' => 1, 'result' => false],
        'vh.ponderationServiceCompl'     => ['name' => 'ponderation_service_compl',    'type' => 'float',  'vmin' => 1, 'result' => false],
        'vh.heures'                      => ['name' => 'heures',                       'type' => 'float',  'vmin' => 1, 'result' => false],
        'vh.param1'                      => ['name' => ['p1', 'param_1'],              'type' => 'string', 'vmin' => 1, 'result' => false],
        'vh.param2'                      => ['name' => ['p2', 'param_2'],              'type' => 'string', 'vmin' => 1, 'result' => false],
        'vh.param3'                      => ['name' => ['p3', 'param_3'],              'type' => 'string', 'vmin' => 1, 'result' => false],
        'vh.param4'                      => ['name' => ['p4', 'param_4'],              'type' => 'string', 'vmin' => 1, 'result' => false],
        'vh.param5'                      => ['name' => ['p5', 'param_5'],              'type' => 'string', 'vmin' => 1, 'result' => false],
        'vh.heuresServiceFi'             => ['name' => 'service_fi',                   'type' => 'float',  'vmin' => 1, 'result' => true ],
        'vh.heuresServiceFa'             => ['name' => 'service_fa',                   'type' => 'float',  'vmin' => 1, 'result' => true ],
        'vh.heuresServiceFc'             => ['name' => 'service_fc',                   'type' => 'float',  'vmin' => 1, 'result' => true ],
        'vh.heuresServiceReferentiel'    => ['name' => 'service_referentiel',          'type' => 'float',  'vmin' => 1, 'result' => true ],
        'vh.heuresNonPayableFi'          => ['name' => 'heures_np_fi',                 'type' => 'float',  'vmin' => 3, 'result' => true ],
        'vh.heuresNonPayableFa'          => ['name' => 'heures_np_fa',                 'type' => 'float',  'vmin' => 3, 'result' => true ],
        'vh.heuresNonPayableFc'          => ['name' => 'heures_np_fc',                 'type' => 'float',  'vmin' => 3, 'result' => true ],
        'vh.heuresNonPayableReferentiel' => ['name' => 'heures_np_referentiel',        'type' => 'float',  'vmin' => 3, 'result' => true ],
        'vh.heuresComplFi'               => ['name' => 'heures_compl_fi',              'type' => 'float',  'vmin' => 1, 'result' => true ],
        'vh.heuresComplFa'               => ['name' => 'heures_compl_fa',              'type' => 'float',  'vmin' => 1, 'result' => true ],
        'vh.heuresComplFc'               => ['name' => 'heures_compl_fc',              'type' => 'float',  'vmin' => 1, 'result' => true ],
        'vh.heuresComplReferentiel'      => ['name' => 'heures_compl_referentiel',     'type' => 'float',  'vmin' => 1, 'result' => true ],
        'vh.heuresPrimes'                => ['name' => 'heures_compl_fc_majorees',     'type' => 'float',  'vmin' => 1, 'result' => true ],
    ];
    //@formatter:on

    private array $proprietes = [
        'code'              => ['sheet' => 2, 'pos' => 'F3', 'cell' => null],
        'libelle'           => ['sheet' => 2, 'pos' => 'F4', 'cell' => null],
        'active'            => ['sheet' => 2, 'pos' => 'F5', 'cell' => null],
        'delegationAnnee'   => ['sheet' => 2, 'pos' => 'F9', 'cell' => null],
        'delegationFormule' => ['sheet' => 2, 'pos' => 'F10', 'cell' => null],
        'sqlIntervenant'    => ['sheet' => 3, 'pos' => 'A2', 'cell' => null],
        'sqlVolumeHoraire'  => ['sheet' => 3, 'pos' => 'B2', 'cell' => null],
    ];

    private array $formuleCells = [];



    public function __construct(Calc $tableur)
    {
        $this->tableur = $tableur;
    }



    public function tableur(): Calc
    {
        return $this->tableur;
    }



    public function sheet(): Sheet
    {
        if (!isset($this->sheet)) {
            try {
                $this->sheet = $this->tableur->getSheet(1);
            } catch (\Exception $e) {
                // pour les formules sans aucun format
                $this->sheet = $this->tableur->getSheet(0);
            }
        }
        return $this->sheet;
    }



    public function version(): int
    {
        return $this->version;
    }



    public function mainLine(): int
    {
        return $this->mainLine;
    }



    public function lastLine(): int
    {
        return $this->lastLine;
    }



    public function variables(): array
    {
        return $this->variables;
    }



    public function lire(): void
    {
        if ($this->lu) return;

        $this->trouverMainLine();
        $this->trouverVersion();

        if ($this->version() < 1) {
            throw new \Exception('Le tableur n\'est conforme à aucun modèle de formule de calcul pris en compte');
        }

        $this->trouverIntervenantData();
        $this->trouverVolumeHoraireData();
        $this->trouverVariablesDepuisAlias();

        // contrôle de conformité
        foreach ($this->variables as $v => $vp) {
            if ($vp['vmin'] > $this->version()) {
                unset($this->variables[$v]);
            } elseif (!isset($vp['cell'])) {
                throw new \Exception('Le tableur n\'est pas conforme : la variable ' . $v . ' n\'a pas pu être localisée');
            }
        }

        $this->trouverProprietes();

        // contrôle de conformité des propriétés
        if ((!$this->proprietes['code']['cell']?->getValue()) || (!$this->proprietes['libelle']['cell']?->getValue())) {
            throw new \Exception('Les propriétés de la formule de calcul ne sont pas correctement renseignées');
        }

        $this->trouverFormuleCells();

        $this->lu = true;
    }



    public function getCellFloatVal(string $cellName): float
    {
        $cell = $this->sheet->getCell($cellName);
        if (!$cell){
            throw new \Exception("Cellule $cellName non trouvée");
        }

        return (float)$cell->getValue();
    }



    protected function trouverVersion(): void
    {
        if ('parametres_generaux' == $this->getColLib('A', $this->mainLine - 2)) {
            $this->version = 1; // version de base
        } else {
            $this->version = 0; // Formule ancienne non conforme
            return;
        }
        if ('structure_is_exterieure' == $this->getColLib('D', $this->mainLine - 1)) {
            // permet de gérer les structures extérieures
            $this->version = 2;
        }
        if ('heures_non_payables' == $this->getColLib('F', $this->mainLine - 1)) {
            // on prend en compte les heures non payables
            $this->version = 3;
        }
    }



    protected function trouverMainLine(): void
    {
        $col = 'A';
        $ligne = 18;
        $maxLigne = 40;
        while ($ligne <= $maxLigne) {
            if ('parametres_generaux' == $this->getColLib($col, $ligne)) {
                $this->mainLine = $ligne + 2;

                return;
            }

            $ligne++;
        }
    }



    protected function trouverIntervenantData(): void
    {
        /* On cible, par nom de cellule, les variables spécifiques aux intervenants */
        $vNames = [];
        foreach ($this->variables as $n => $dataVariable) {
            if (str_starts_with($n, 'i.')) {
                $names = (array)$dataVariable['name'];
                foreach ($names as $name) {
                    $vNames[$name] = $n;
                }
            }
        }

        $col = 3; // C
        $minLigne = 3;
        $maxLigne = $this->mainLine - 1;
        for ($ligne = $minLigne; $ligne < $maxLigne; $ligne++) {
            $colLib = $this->getColLib($col, $ligne);
            if (isset($vNames[$colLib])) {
                $dataCell = $this->sheet()->getCellByCoords($col + 1, $ligne);
                $this->variables[$vNames[$colLib]]['cell'] = $dataCell;
            }
        }


        // Passe différente de recherche des paramètres, car s'ils sont utilisés, ils sont renommés
        // On les cherche alors en-dessous de "depassement_service_du_sans_hc"
        if (isset($this->variables['i.depassementServiceDuSansHC']['cell'])) {
            /** @var Calc\Cell $refCell */
            $refCell = $this->variables['i.depassementServiceDuSansHC']['cell'];

            for ($param = 1; $param < 6; $param++) {
                if (!isset($this->variables['i.param' . $param]['cell'])) {
                    $paramCell = $this->sheet()->getCellByCoords($refCell->getCol(), $refCell->getRow() + $param);
                    $this->variables['i.param' . $param]['cell'] = $paramCell;
                }
            }
        }
    }



    protected function trouverVolumeHoraireData(): void
    {
        /* On cible, par nom de cellule, les variables spécifiques aux volumes horaires */
        $vNames = [];
        foreach ($this->variables as $n => $dataVariable) {
            if (str_starts_with($n, 'vh.')) {
                $names = (array)$dataVariable['name'];
                foreach ($names as $name) {
                    $vNames[$name] = $n;
                }
            }
        }

        $ligne = $this->mainLine - 1;
        $maxCol = $this->maxDataCol();
        for ($col = 1; $col <= $maxCol; $col++) {
            $colLib = $this->getColLib($col, $ligne);
            if (isset($vNames[$colLib])) {
                $dataCell = $this->sheet()->getCellByCoords($col, $ligne + 1);
                $this->variables[$vNames[$colLib]]['cell'] = $dataCell;
            }
        }

        // Passe différente de recherche des paramètres, car s'ils sont utilisés, ils sont renommés
        // On les cherche alors à droite de la colonne des heures et non par leur nom
        if (isset($this->variables['vh.heures']['cell'])) {
            /** @var Calc\Cell $refCell */
            $refCell = $this->variables['vh.heures']['cell'];

            for ($param = 1; $param < 6; $param++) {
                if (!isset($this->variables['vh.param' . $param]['cell'])) {
                    $paramCell = $this->sheet()->getCellByCoords($refCell->getCol() + $param, $refCell->getRow());
                    $this->variables['vh.param' . $param]['cell'] = $paramCell;
                }
            }
        }
    }



    public function trouverVariablesDepuisAlias()
    {
        $aliases = $this->tableur->getAliases();

        /* Pour celles qui n'ont pas été trouvées, on cherche dans les alias */
        foreach ($this->variables as $vname => $variable) {
            if (!isset($variable['cell'])) {
                $names = (array)$variable['name'];
                foreach ($names as $name) {
                    if (isset($aliases[$name]) && $aliases[$name]['sheet'] == 'Calculs') {
                        $this->variables[$vname]['cell'] = $this->sheet()->getCell($aliases[$name]['name']);
                    }
                }
            }
        }
    }



    protected function trouverProprietes(): void
    {
        try {
            $this->tableur->getSheet(2);
        } catch (\Exception $e) {
            throw new \Exception('La feuille des nomenclatures n\'a pas été trouvée');
        }

        try {
            $this->tableur->getSheet(3);
        } catch (\Exception $e) {
            throw new \Exception('La feuille des interactions n\'a pas été trouvée');
        }

        foreach ($this->proprietes as $pn => $pv) {
            $this->proprietes[$pn]['cell'] = $this->tableur->getSheet($pv['sheet'])->getCell($pv['pos']);
        }
    }



    protected function trouverFormuleCells(): void
    {
        $this->formuleCells = [];

        foreach ($this->variables as $vn => $v) {
            if (str_starts_with($vn, 'vh.') && $v['result']) {
                /** @var Calc\Cell $cell */
                $cell = $v['cell'];
                if ($formule = $cell->getFormule()) {
                    $this->formuleCells[$cell->getName()] = ['cell' => $cell, 'parsed' => false];
                }
            }
        }

        while (!$this->parserFormuleCells()) {
            // tant que toutes les cellules n'ont pas été parcourues, on refait le parcours
        }
    }



    private function maxDataCol(): int
    {
        if ($this->version() >= 3) {
            return Calc::letterToNumber('AH');
        } else {
            return Calc::letterToNumber('AC');
        }
    }



    private function parserFormuleCells(): bool
    {
        $allParsed = true;
        foreach ($this->formuleCells as $n => $c) {
            if (!$c['parsed']) {
                $allParsed = false;
                $this->formuleCells[$n]['parsed'] = true;
                $this->parserFormuleCell($c['cell']);
            }
        }
        return $allParsed;
    }



    private function parserFormuleCell(Calc\Cell $cell): void
    {
        $name = $cell->getName();
        $deps = $cell->getDeps();
        $depsFound = [];

        if ($cell->getRow() == $this->lastLine()){
            // on est sur la dernière ligne : ce n'est pas des formules à traduire, mais la ligne est transformée en fonction "derniere"
            return;
        }

        // Si on est sur une suite, on ajoute la ligne principale pour la colonne
        // si elle n'est pas déjà dans les dépendances
        if ($cell->getRow() == $this->mainLine()) {
            foreach ($deps as $i => $dep) {
                if (is_string($dep)) {
                    $depCellPos = Calc::cellNameToCoords($dep);
                    if ($depCellPos['row'] == $this->mainLine() - 1 && !$depCellPos['absRow']) {
                        $newDep = Calc::coordsToCellName($depCellPos['col'], $this->mainLine());
                        if (!in_array($newDep, $deps)) {
                            $deps[] = $newDep;
                        }
                    }
                }
            }
        }

        // Traitement des variables et remplacement pas les cellules nommées
        foreach ($deps as $i => $dep) {
            if (is_array($dep) && 'variable' == $dep['type']) {
                // on remplace les variables par leur cible
                $deps[$i] = $this->tableur->getAliasTarget($dep['name']);

                if (isset($deps[$i]['sheet']) && $deps[$i]['sheet'] && $deps[$i]['sheet'] != $this->sheet()->getName()) {
                    throw new \Exception('Erreur dans l\'expression de ' . $cell->getName() . ' : l\'expression fait référence à des cellules présentes dans d\'autres onglets de la feuille de calcul');
                }
            }
            if (is_array($dep) && 'range' == $dep['type']) {
                if (isset($deps[$i]['sheet']) && $deps[$i]['sheet'] && $deps[$i]['sheet'] != $this->sheet()->getName()) {
                    throw new \Exception('Erreur dans l\'expression de ' . $cell->getName() . ' : l\'expression fait référence à une plage de cellules présente dans d\'autres onglets de la feuille de calcul');
                }
            }
        }

        foreach ($deps as $dep) {
            if (is_string($dep)) { // on est sur une cellule
                $d = str_replace('$', '', $dep);
                $depsFound[$d] = $d;
            } elseif (is_array($dep) && 'range' == $dep['type']) { // on est sur un range
                $allRow = false;
                if ($dep['rowEnd'] >= 500 && $dep['rowBegin'] <= $this->mainLine){
                    // Le range correspond aux lignes de données de volumes horaires : on prend tout
                    $allRow = true;
                }
                if (!$allRow && $dep['rowEnd'] == 0 && $dep['rowBegin'] == 0){
                    // Dans ce cas, le range n'est pas précisé : on prend tout aussi
                    $allRow = true;
                }
                if ($allRow) { // Si c'est toute la colonne , on ne prend en compte que la ligne principale, pas les autres
                    $dep['rowBegin'] = $this->mainLine;
                    $dep['rowEnd'] = $this->mainLine;
                }
                for ($col = $dep['colBegin']; $col <= $dep['colEnd']; $col++) {
                    for ($row = $dep['rowBegin']; $row <= $dep['rowEnd']; $row++) {
                        if ($row == 0) {
                            $depName = Calc::numberToLetter($col);
                        } else {
                            $depName = Calc::coordsToCellName($col, $row);
                        }
                        $depsFound[$depName] = ['row' => $row, 'col' => $col];
                    }
                }
            } elseif (is_array($dep) && 'cell' == $dep['type']) {
                if (isset($dep['sheet'])) {
                    if ($sheet = $this->tableur->getSheet($dep['sheet'])) {
                        if ($sheet->getIndex() == $this->sheet->getIndex()) { // la cellule doit se trouver dans la même feuille
                            $depsFound[$dep['name']] = $dep['name'];
                        }
                    }
                }

            }
        }

        foreach ($depsFound as $dep => $depInfo) {
            if (!array_key_exists($dep, $this->formuleCells)) {
                $vFound = false;
                foreach ($this->variables as $variable) {
                    if ($variable['cell']->getName() == $dep) {
                        $vFound = true;
                        break;
                    }
                }
                if (!$vFound) {
                    $depCell = $this->sheet()->getCell($dep);
                    if ($depCell) {
                        // la cellule ne doit pas faire partie des données saisies
                        if ($depCell->getCol() > $this->maxDataCol()) {
                            $this->formuleCells[$dep] = ['cell' => $depCell, 'parsed' => false];
                        }
                    } else {
                        if (is_array($depInfo) && $depInfo['row'] == 0) {
                            // Rien : on a affaire à un range infini sur une colonne
                        } else {
                            throw new \Exception('Erreur dans l\'expression de ' . $cell->getName() . ' : la cellule ' . $dep . ' est vide');
                        }
                    }
                }
            }
        }
    }



    private function getColLib(int|string $col, int $ligne): string
    {
        $titleCell = $this->sheet()->getCellByCoords($col, $ligne);
        $result = Util::reduce($titleCell?->getContent() ?? '');

        return $result;
    }



    public function formule(): Formule
    {
        $fProps = [];

        foreach ($this->proprietes as $pn => $p) {
            if ($p['cell']) {
                /** @var Calc\Cell $cell */
                $cell = $p['cell'];
                $fProps[$pn] = $cell->getContent();
            }
        }

        $fProps['active'] = $fProps['active'] == 'Oui';
        if (!$fProps['delegationAnnee']) $fProps['delegationAnnee'] = null;
        if (!$fProps['delegationFormule']) $fProps['delegationFormule'] = null;

        if ($fProps['delegationAnnee']) {
            $fProps['delegationAnnee'] = (int)substr($fProps['delegationAnnee'], 0, 4);
        }

        $pps = [
            'i'  => ['col' => -1, 'row' => 0],
            'vh' => ['col' => 0, 'row' => -1],
        ];
        foreach ($pps as $pp => $ppp) {
            for ($i = 1; $i <= 5; $i++) {
                /** @var Calc\Cell $valCell */
                $valCell = $this->variables[$pp . '.param' . $i]['cell'];
                $cell = $this->sheet->getCellByCoords($valCell->getCol() + $ppp['col'], $valCell->getRow() + $ppp['row']);
                $value = trim($cell->getValue() ?? '');
                if ('p' . $i == strtolower($value ?? '')) $value = null;
                if ('param_' . $i == strtolower($value ?? '')) $value = null;
                if ('param ' . $i == strtolower($value ?? '')) $value = null;
                if ('0' == $value) $value = null;

                $fProps[$pp . 'Param' . $i . 'Libelle'] = $value;
            }
        }

        $formulesRepo = $this->getServiceContext()->getEntityManager()->getRepository(Formule::class);

        $formule = $formulesRepo->findOneBy(['code' => $fProps['code']]);
        if (!$formule) $formule = new Formule();

        foreach ($fProps as $fProp => $value) {
            $method = 'set' . ucfirst($fProp);
            $formule->$method($value);
        }

        foreach ($this->variables as $name => $variable) {
            if ($variable['result'] == true) {
                $method = 'set' . ucfirst(substr($name, 3)) . 'Col';
                $colPos = Calc::numberToLetter($variable['cell']->getCol());
                $formule->$method($colPos);
            }
        }

        return $formule;
    }



    public function variableValue(string $name, int $row = 0): mixed
    {
        if (!array_key_exists($name, $this->variables)) {
            return null;
        }

        if (str_starts_with($name, 'i.')) {
            $value = $this->variables[$name]['cell']->getValue();
        } else {
            $col = $this->variables[$name]['cell']->getCol();
            $cell = $this->sheet()->getCellByCoords($col, $row);

            if (!$cell) return null;
            $value = $cell?->getValue();
        }

        if (null == $value) {
            return null;
        }

        $type = $this->variables[$name]['type'];
        return match ($type) {
            'float' => (float)$value,
            'pourc' => (float)$value,
            'bool' => in_array($value, ['1', 1, 'Oui', 'oui', 'O', 'o']),
            default => $value,
        };
    }



    public function variableFromCell(string $cell): ?string
    {
        $coords = Calc::cellNameToCoords($cell);
        $col = $coords['col'];
        $row = $coords['row'] ?: $this->mainLine();
        foreach ($this->variables as $vn => $v) {
            if ($row == $this->mainLine() && str_starts_with($vn, 'vh.')) {
                /** @var Calc\Cell $cell */
                $cell = $v['cell'];
                if ($cell->getCol() == $col) {
                    return $vn;
                }
            } elseif (str_starts_with($vn, 'i.')) {
                /** @var Calc\Cell $cell */
                $cell = $v['cell'];
                if ($cell->getCol() == $col && $cell->getRow() == $row) {
                    return $vn;
                }
            }
        }
        return null;
    }



    public function variableFromName(string $name): ?string
    {
        $ivh = '';
        if (str_starts_with($name, 'i_')) {
            $ivh = 'i.';
            $name = substr($name, 2);
        } elseif (str_starts_with($name, 'vh_')) {
            $ivh = 'vh.';
            $name = substr($name, 3);
        }

        foreach ($this->variables as $vn => $v) {
            if (str_starts_with($vn, $ivh)) {
                $names = $v['name'];
                if (!is_array($names)) {
                    $names = [$names];
                }
                if (in_array($name, $names)) {
                    return $vn;
                }
            }
        }
        return null;
    }



    public function variableType(string $name): string
    {
        return $this->variables[$name]['type'];
    }



    public function formuleIntervenant(): FormuleTestIntervenant
    {
        $em = $this->getServiceContext()->getEntityManager();

        $fi = new FormuleTestIntervenant();
        $fi->setAnnee($this->getServiceContext()->getAnnee());

        $fi->setFormule($this->formule());

        $typeVolumeHoraire = $this->variableValue('i.typeVolumeHoraire');
        $fi->setTypeVolumeHoraire($em->getRepository(TypeVolumeHoraire::class)->findOneBy(['code' => $typeVolumeHoraire]));

        $etatSaisi = EtatVolumeHoraire::CODE_SAISI;
        $fi->setEtatVolumeHoraire($em->getRepository(EtatVolumeHoraire::class)->findOneBy(['code' => $etatSaisi]));

        $typeIntervenant = $this->variableValue('i.typeIntervenant');
        $fi->setTypeIntervenant($em->getRepository(TypeIntervenant::class)->findOneBy(['code' => $typeIntervenant]));

        $fi->setStructureCode($this->variableValue('i.structureCode'));
        $fi->setHeuresServiceStatutaire($this->variableValue('i.heuresServiceStatutaire'));
        $fi->setHeuresServiceModifie($this->variableValue('i.heuresServiceModifie'));
        $fi->setDepassementServiceDuSansHC($this->variableValue('i.depassementServiceDuSansHC'));
        $fi->setParam1($this->variableValue('i.param1'));
        $fi->setParam2($this->variableValue('i.param2'));
        $fi->setParam3($this->variableValue('i.param3'));
        $fi->setParam4($this->variableValue('i.param4'));
        $fi->setParam5($this->variableValue('i.param5'));

        $row = $this->mainLine;
        while ($this->variableValue('vh.structureCode', $row) && $this->variableValue('vh.typeInterventionCode', $row)) {
            $vh = new FormuleTestVolumeHoraire();
            $fi->addVolumeHoraire($vh);
            $referentiel = Util::reduce($this->variableValue('vh.typeInterventionCode', $row)) == 'referentiel';
            if ($referentiel) {
                $vh->setVolumeHoraireReferentiel($row);
                $vh->setServiceReferentiel($row);
            } else {
                $vh->setVolumeHoraire($row);
                $vh->setService($row);

                $tauxCode = $this->variableValue('vh.typeInterventionCode', $row);
                $tauxServiceDu = $this->variableValue('vh.tauxServiceDu', $row);
                $tauxServiceCompl = $this->variableValue('vh.tauxServiceCompl', $row);

                $fi->setTaux($tauxCode, $tauxServiceDu, $tauxServiceCompl);
            }
            foreach ($this->variables as $vn => $v) {
                if (str_starts_with($vn, 'vh.')) {
                    switch ($vn) {
                        case 'vh.tauxServiceDu':
                        case 'vh.tauxServiceCompl':
                            // on ne fait rien : c'est géré au niveau intervenant
                            break;
                        default:
                            // sinon on parse et on applique
                            $method = 'set' . ucfirst(substr($vn, 3));
                            $vh->$method($this->variableValue($vn, $row));
                    }
                }
            }

            $vh->populateAttendues();

            $row++;
        }

        return $fi;
    }



    /**
     * @return array|Calc\Cell[]
     */
    public function formuleCells(): array
    {
        $exprs = [];

        foreach ($this->formuleCells as $n => $cell) {
            $exprs[$n] = $cell['cell'];
        }

        uksort($exprs, function ($a, $b) {
            $ac = Calc::cellNameToCoords($a);
            $bc = Calc::cellNameToCoords($b);

            if ($ac['col'] != $bc['col']) {
                return $ac['col'] - $bc['col'];
            } else {
                return $ac['row'] - $bc['row'];
            }
        });

        return $exprs;
    }

}