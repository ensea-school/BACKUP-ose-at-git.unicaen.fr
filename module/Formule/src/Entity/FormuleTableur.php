<?php

namespace Formule\Entity;


use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\Db\Formule;
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
    private int $version = 0;
    private bool $lu = false;

    //@formatter:off
    private array $variables = [
        'i.typeIntervenant'            => ['name' => 'type_intervenant_code',          'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.structureCode'              => ['name' => 'structure_code',                 'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.typeVolumeHoraire'          => ['name' => 'type_volume_horaire_code',       'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.heuresServiceStatutaire'    => ['name' => 'heures_service_statutaire',      'type' => 'float',  'vmin' => 1, 'result' => false],
        'i.heuresServiceModifie'       => ['name' => 'heures_service_modifie',         'type' => 'float',  'vmin' => 1, 'result' => false],
        'i.depassementServiceDuSansHC' => ['name' => 'depassement_service_du_sans_hc', 'type' => 'bool',   'vmin' => 1, 'result' => false],
        'i.param1'                     => ['name' => ['p1', 'param_1'],                'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.param2'                     => ['name' => ['p2', 'param_2'],                'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.param3'                     => ['name' => ['p3', 'param_3'],                'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.param4'                     => ['name' => ['p4', 'param_4'],                'type' => 'string', 'vmin' => 1, 'result' => false],
        'i.param5'                     => ['name' => ['p5', 'param_5'],                'type' => 'string', 'vmin' => 1, 'result' => false],

        'vh.structureCode'               => ['name' => 'structure_code',               'type' => 'string', 'vmin' => 1, 'result' => false],
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
        if ((!$this->proprietes['code']['cell']->getValue()) || (!$this->proprietes['libelle']['cell']->getValue())) {
            throw new \Exception('Les propriétés de la formule de calcul ne sont pas correctement renseignées');
        }

        $this->trouverFormuleCells();

        $this->lu = true;
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
        $deps = $cell->getDeps();
        foreach ($deps as $dep) {
            $dep = str_replace('$', '', $dep);
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
                        throw new \Exception('Erreur dans l\'expression de ' . $cell->getName() . ' : la cellule ' . $dep . ' est vide');
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
                $fProps[$pn] = $cell->getValue();
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



    public function formuleIntervenant(): FormuleIntervenant
    {
        $em = $this->getServiceContext()->getEntityManager();

        $fi = new FormuleIntervenant();
        $fi->setAnnee($this->getServiceContext()->getAnnee());

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
            $vh = new FormuleVolumeHoraire();
            $fi->addVolumeHoraire($vh);
            $referentiel = Util::reduce($this->variableValue('vh.typeInterventionCode', $row)) == 'referentiel';
            if ($referentiel) {
                $vh->setVolumeHoraireReferentiel($row);
                $vh->setServiceReferentiel($row);
            } else {
                $vh->setVolumeHoraire($row);
                $vh->setService($row);
            }
            foreach ($this->variables as $vn => $v) {
                if (str_starts_with($vn, 'vh.')) {
                    $method = 'set' . ucfirst(substr($vn, 3));
                    $vh->$method($this->variableValue($vn, $row));
                }
            }

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