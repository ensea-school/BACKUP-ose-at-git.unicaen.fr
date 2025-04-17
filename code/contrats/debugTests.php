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

$noDisplayContrat = [
    'id', 'intervenant_id', 'type_contrat_id', 'contrat_id', 'type_contrat_code', 'type_contrat_libelle',
    'histo_creation', 'histo_createur_id', 'histo_modification', 'histo_modificateur_id', 'histo_destruction', 'histo_destructeur_id',
    'date_envoi_email', 'process_signature_id',
];

$noDisplayVh = [
    'annee_id', 'intervenant_id', 'contrat_id', 'heures',
    'service_id', 'service_referentiel_id', 'mission_id',
    'volume_horaire_id', 'volume_horaire_ref_id', 'volume_horaire_mission_id',
    'autre_libelle',
];


// récup data & formatage
$bdd = $container->get(Unicaen\BddAdmin\Bdd::class);

$anneeId = $bdd->selectOne("SELECT i.annee_id FROM intervenant i WHERE id = :intervenantId", ['intervenantId' => $intervenantId], 'annee_id');

$cData = $bdd->select("
SELECT 
    c.*,
    tc.code type_contrat_code,
    tc.libelle type_contrat_libelle,
    v.histo_destruction validation_histo_destruction
FROM 
    contrat c
    JOIN type_contrat tc ON tc.id = c.type_contrat_id
    LEFT JOIN validation v ON v.id = c.validation_id
WHERE
    c.intervenant_id = :intervenant 
ORDER BY
    c.id
", ['intervenant' => $intervenantId], ['case' => CASE_LOWER]);

$vhData = $bdd->select("
SELECT
  vh.*
FROM
  v_tbl_contrat_volume_horaire vh
WHERE
  vh.intervenant_id = :intervenant
ORDER BY
  vh.volume_horaire_id, vh.volume_horaire_ref_id, vh.volume_horaire_mission_id
", ['intervenant' => $intervenantId], ['case' => CASE_LOWER]);

$contrats        = [];
$volumesHoraires = [];

foreach ($cData as $contrat) {
    $contrat['volumesHoraires'] = [];
    $contrats[$contrat['id']] = $contrat;
}

foreach ($vhData as $vh) {
    if ($vh['volume_horaire_id']) {
        $vh['type']  = 'ens';
        $vh['vh_id'] = $vh['volume_horaire_id'];
    } elseif ($vh['volume_horaire_ref_id']) {
        $vh['type']  = 'ref';
        $vh['vh_id'] = $vh['volume_horaire_ref_id'];
    } elseif ($vh['volume_horaire_mission_id']) {
        $vh['type']  = 'mis';
        $vh['vh_id'] = $vh['volume_horaire_mission_id'];
    }

    if ($vh['service_id']) {
        $vh['s_id'] = $vh['service_id'];
    } elseif ($vh['service_referentiel_id']) {
        $vh['s_id'] = $vh['service_referentiel_id'];
    } elseif ($vh['mission_id']) {
        $vh['s_id'] = $vh['mission_id'];
    }

    if (!empty($vh['contrat_id'])){
        $contrats[$vh['contrat_id']]['volumesHoraires'][] = $vh;
    }else{
        $volumesHoraires[] = $vh;
    }
}

// Affichage des données

echo "<h2>Intervenant ID = $intervenantId, Année ID = $anneeId</h2>";

foreach ($contrats as $contrat) {
    $isAvenant   = $contrat['type_contrat_code'] == \Contrat\Entity\Db\TypeContrat::CODE_AVENANT;
    $isHistorise = $contrat['histo_destruction'] != null;

    $title = [
        $contrat['type_contrat_libelle'],
        'ID=' . $contrat['id'],
        'Création=' . $contrat['histo_creation'],
    ];
    if ($isHistorise) {
        $title[] = 'Histo=' . $contrat['histo_destruction'];
    }
    if ($contrat['contrat_id']) {
        $title[] = 'ID parent=' . $contrat['contrat_id'];
    }

    $props = $contrat;
    unset($props['volumesHoraires']);

    $vhs = $contrat['volumesHoraires'] ?? [];

    ?>
    <div <?php if ($isAvenant) echo ' style="margin-left: 4em"'; ?>>
        <div class="card <?php if ($isHistorise) echo 'bg-danger' ?>">
            <div class="card-header"><?= implode(' | ', $title) ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h4>Propriétés</h4>
                        <table class="table table-xs table">
                            <?php
                            foreach ($props as $prop => $value) {
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

?>
    <h2>Volumes horaires prévus validés non contractualisés</h2>
    <table class="table table-xs table">
        <?php

        $noDisplayVh[] = 'histo_destruction';

        $first = true;
        foreach ($volumesHoraires as $vh) {
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
            if (!$vh['contrat_id']) {
                echo '<tr>';
                foreach ($vh as $prop => $value) {
                    if (!in_array($prop, $noDisplayVh)) {
                        echo '<td>' . ($value ?: '<i style="color: gray">null</i>') . '</td>';
                    }
                }
                echo '</tr>';
            }
        }

        ?>
    </table>
<?php


// Génération d'un jeu de tests unitaires pour le TBL Contrat
echo "<h2>Génération d'un jeu de tests unitaires pour le TBL Contrat</h2>";

echo '<a href="'.\UnicaenCode\Util::url('contrats/tblContratIntervenant', ['intervenantId' => $intervenantId]).'">Affichage des données en sortie du TBL_CONTRAT</a><br />';
echo '<a href="'.$this->url('intervenant/contrat', ['intervenant' => $intervenantId]).'">Page contrat de l\'intervenant</a><br />';

$contratIndex         = 0;
$avenantIndex         = 0;
$volumesHorairesIndex = 0;
$php                  = "";
foreach ($contrats as $cid => $contrat) {
    $isAvenant = $contrat['type_contrat_code'] == \Contrat\Entity\Db\TypeContrat::CODE_AVENANT;

    // Nommage de la variable
    if ($isAvenant) {
        $avenantIndex++;
        $contrat['variable'] = 'avenant' . $avenantIndex;
    } else {
        $contratIndex++;
        $contrat['variable'] = 'contrat' . $contratIndex;
    }
    $contrats[$cid]['variable'] = $contrat['variable'];
    $cnom                       = $contrat['variable'];

    $php .= "$" . $cnom . "                = new Contrat();\n";
    $php .= "$" . $cnom . "->id            = " . $contrat['id'] . ";\n";
    if ($contrat['histo_destruction']) {
        $php .= "$" . $cnom . "->historise     = true;\n";
    }
    if ($contrat['structure_id']) {
        $php .= "$" . $cnom . "->structureId   = " . $contrat['structure_id'] . ";\n";
    }
    if ($contrat['contrat_id']) {
        $php .= "$" . $cnom . "->setParent($" . $contrats[(int)$contrat['contrat_id']]['variable'] . ");\n";
    }
    if ($contrat['numero_avenant']) {
        $php .= "$" . $cnom . "->numeroAvenant = " . $contrat['numero_avenant'] . ";\n";
    }
    if ($contrat['debut_validite']) {
        $php .= "$" . $cnom . "->debutValidite = new \Datetime('" . substr($contrat['debut_validite'], 0, 10) . "');\n";
    }
    if ($contrat['fin_validite']) {
        $php .= "$" . $cnom . "->finValidite   = new \Datetime('" . substr($contrat['fin_validite'], 0, 10) . "');\n";
    }
    //if ($contrat['histo_creation']) {
    //    $php .= "$" . $cnom . "->histoCreation = new \Datetime('" . substr($contrat['histo_creation'], 0, 10) . "');\n";
    //}
    if ($contrat['validation_id'] && null == $contrat['validation_histo_destruction']) {
        $php .= "$" . $cnom . "->edite         = true;\n";
    }

    $volumeHoraireVariables = [];

    $php .= "\n";
    foreach ($contrat['volumesHoraires'] as $volumeHoraire) {
        $volumesHorairesIndex++;
        $vnom = 'volumeHoraire' . $volumesHorairesIndex;

        $volumeHoraireVariables[] = $vnom;

        $php .= volumeHoraireToPhp($volumeHoraire, $vnom);
    }

    if (!empty($volumeHoraireVariables)) {
        $php .= "$" . $cnom . "->volumesHoraires = [$" . implode(', $', $volumeHoraireVariables) . "];\n";
        $php .= "\n";
    }
}

if (!empty($volumesHoraires)){
    $php .= "\n";
    $php .= "// Volumes horaires orphelins... \n";
    $php .= "\n";
    foreach ($volumesHoraires as $volumeHoraire) {
        $volumesHorairesIndex++;
        $vnom = 'volumeHoraire' . $volumesHorairesIndex;

        $volumeHoraireVariables[] = $vnom;

        $php .= volumeHoraireToPhp($volumeHoraire, $vnom);
    }
}

\UnicaenCode\Util::highlight($php, 'php', true, ['show-line-numbers' => true]);



function volumeHoraireToPhp(array $volumesHoraire, string $vnom): string
{
    $php = "$" . $vnom . " = new VolumeHoraire();\n";
    if ($volumesHoraire['structure_id']) {
        $php .= "$" . $vnom . "->structureId        = " . $volumesHoraire['structure_id'] . ";\n";
    }
    if ($volumesHoraire['service_id']) {
        $php .= "$" . $vnom . "->serviceId          = " . $volumesHoraire['service_id'] . ";\n";
    }
    if ($volumesHoraire['service_referentiel_id']) {
        $php .= "$" . $vnom . "->serviceReferentielId = " . $volumesHoraire['service_referentiel_id'] . ";\n";
    }
    if ($volumesHoraire['mission_id']) {
        $php .= "$" . $vnom . "->missionId          = " . $volumesHoraire['mission_id'] . ";\n";
    }
    if ($volumesHoraire['volume_horaire_id']) {
        $php .= "$" . $vnom . "->volumeHoraireId    = " . $volumesHoraire['volume_horaire_id'] . ";\n";
    }
    if ($volumesHoraire['volume_horaire_ref_id']) {
        $php .= "$" . $vnom . "->volumeHoraireRefId = " . $volumesHoraire['volume_horaire_ref_id'] . ";\n";
    }
    if ($volumesHoraire['taux_remu_id']) {
        $php .= "$" . $vnom . "->tauxRemuId         = " . $volumesHoraire['taux_remu_id'] . ";\n";
    }
    if ($volumesHoraire['taux_remu_majore_id']) {
        $php .= "$" . $vnom . "->tauxRemuMajoreId   = " . $volumesHoraire['taux_remu_majore_id'] . ";\n";
    }
    if ($volumesHoraire['date_fin_mission']) {
        $php .= "$" . $vnom . "->dateFinMission     = new \Datetime('" . substr($volumesHoraire['date_fin_mission'], 0, 10) . "');\n";
    }
    if ($volumesHoraire['cm']) {
        $php .= "$" . $vnom . "->cm                 = " . $volumesHoraire['cm'] . ";\n";
    }
    if ($volumesHoraire['td']) {
        $php .= "$" . $vnom . "->td                 = " . $volumesHoraire['td'] . ";\n";
    }
    if ($volumesHoraire['tp']) {
        $php .= "$" . $vnom . "->tp                 = " . $volumesHoraire['tp'] . ";\n";
    }
    if ($volumesHoraire['autres']) {
        $php .= "$" . $vnom . "->autres             = " . $volumesHoraire['autres'] . ";\n";
    }
    if ($volumesHoraire['heures']) {
        $php .= "$" . $vnom . "->heures             = " . $volumesHoraire['heures'] . ";\n";
    }
    if ($volumesHoraire['hetd']) {
        $php .= "$" . $vnom . "->hetd               = " . $volumesHoraire['hetd'] . ";\n";
    }
    $php .= "\n";

    return $php;
}
