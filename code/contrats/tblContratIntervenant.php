<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

// récup intervenantId
$pdata  = [
    'intervenantId' => [
        'type'  => 'text',
        'label' => 'ID de l\'intervenant',
    ],
];
$params = \UnicaenCode\Util::codeGenerator()->generer($pdata);

if (!$params['intervenantId']) {
    return;
}

$intervenantId = $params['intervenantId'];

$typeContratAvenant = $container->get(\Contrat\Service\TypeContratService::class)->getAvenant();

// récup data & formatage
$bdd = $container->get(Unicaen\BddAdmin\Bdd::class);

$sql  = "
SELECT 
  * 
FROM 
  tbl_contrat
WHERE 
  intervenant_id = :intervenant 
ORDER BY 
  contrat_id, uuid, volume_horaire_id, volume_horaire_ref_id, volume_horaire_mission_id
";
$data = $bdd->select($sql, ['intervenant' => $intervenantId], ['case' => CASE_LOWER]);

$contrats = [];

$contratCols = [
    'type_contrat_id', 'uuid',
    'annee_id', 'intervenant_id', 'actif', 'structure_id',
    'edite', 'signe', 'termine',
    'contrat_id', 'contrat_parent_id',
    'date_creation', 'date_debut', 'date_fin',
    'taux_conges_payes', 'taux_remu_date', 'taux_remu_id', 'taux_remu_majore_id', 'taux_remu_majore_valeur',
    'taux_remu_valeur',
];

foreach ($data as $d) {
    $uuid      = $d['uuid'];
    $id        = $d['id'];
    $isAvenant = $d['type_contrat_id'] == $typeContratAvenant->getId();

    if ($d['volume_horaire_id']) {
        $d['type']  = 'ens';
        $d['vh_id'] = $d['volume_horaire_id'];
    } elseif ($d['volume_horaire_ref_id']) {
        $d['type']  = 'ref';
        $d['vh_id'] = $d['volume_horaire_ref_id'];
    } elseif ($d['volume_horaire_mission_id']) {
        $d['type']  = 'mis';
        $d['vh_id'] = $d['volume_horaire_mission_id'];
    }

    if ($d['service_id']) {
        $d['s_id'] = $d['service_id'];
    } elseif ($d['service_referentiel_id']) {
        $d['s_id'] = $d['service_referentiel_id'];
    } elseif ($d['mission_id']) {
        $d['s_id'] = $d['mission_id'];
    }

    if (!isset($contrats[$uuid])) {
        $contrats[$uuid]              = [];
        $contrats[$uuid]['isAvenant'] = $isAvenant;
        foreach ($contratCols as $contratCol) {
            $contrats[$uuid][$contratCol] = $d[$contratCol];
        }
        $contrats[$uuid]['volumesHoraires'] = [];
    }

    $contrats[$uuid]['volumesHoraires'][$id] = [];
    foreach ($d as $col => $val) {
        if (!in_array($col, $contratCols)) {
            $contrats[$uuid]['volumesHoraires'][$id][$col] = $val;
        }
    }
}

// affichage
$noDisplayContrat = [
    'intervenant_id', 'uuid', 'isAvenant', 'type_contrat_id',
];

$noDisplayVh = [
    'id',
    'service_id', 'service_referentiel_id', 'mission_id',
    'volume_horaire_id', 'volume_horaire_ref_id', 'volume_horaire_mission_id',
];


foreach ($contrats as $uuid => $contrat) {
    $vhs = $contrat['volumesHoraires'];
    ?>
    <div <?php if ($contrat['isAvenant']) echo ' style="margin-left: 4em"'; ?>>
        <div class="card">
            <div class="card-header"><?= $uuid ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h4>Propriétés</h4>
                        <table class="table table-xs table">
                            <?php
                            foreach ($contrat as $prop => $value) {
                                if (!in_array($prop, $noDisplayContrat) && !is_array($value)) {
                                    echo '<tr><th>' . $prop . '</th><td>' . ($value ?: '<i style="color: gray">null</i>') . '</td></tr>';
                                }
                            }
                            ?>
                        </table>
                    </div>
                    <div class="col-md-9">
                        <h4>Volumes horaires</h4>
                        <?php
                        if (empty($vhs)) {
                            echo "<i>Aucun</i>";
                        }
                        ?>
                        <table class="table table-xs table">
                            <?php
                            $first = true;
                            foreach ($vhs as $vh) {
                                if ($first) {
                                    $first = false;
                                    echo '<tr>';
                                    foreach ($vh as $prop => $value) {
                                        if (!in_array($prop, $noDisplayVh)) {
                                            echo '<th>' . $prop . '</th>';
                                        }
                                    }
                                    echo '</tr>';
                                }
                                echo '<tr>';
                                foreach ($vh as $prop => $value) {
                                    if (!in_array($prop, $noDisplayVh)) {
                                        echo '<td>' . ($value ?: '<i style="color: gray">null</i>') . '</td>';
                                    }
                                }
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
