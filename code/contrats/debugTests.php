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
    'source_id', 'source_code', 'auto_validation', 'tag_id', 'type_volume_horaire_id', 'contrat_id', 'horaire_debut', 'horaire_fin',
    'type_intervention_id',
    'histo_creation', 'histo_createur_id', 'histo_modification', 'histo_modificateur_id', 'histo_destructeur_id',
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
  vh.*,
  frvh.type_intervention_code,
  frvh.total hetd
FROM
  volume_horaire vh
  JOIN service s ON s.id = vh.service_id
  JOIN type_volume_horaire tvh ON tvh.code = 'PREVU'
  JOIN etat_volume_horaire evh ON evh.code = 'valide'
  JOIN formule_resultat_intervenant fri ON fri.intervenant_id = s.intervenant_id AND fri.type_volume_horaire_id = tvh.id AND fri.etat_volume_horaire_id = evh.id
  JOIN formule_resultat_volume_horaire frvh ON frvh.volume_horaire_id = vh.id AND frvh.formule_resultat_intervenant_id = fri.id
WHERE
  s.intervenant_id = :intervenant
ORDER BY
  vh.id
", ['intervenant' => $intervenantId], ['case' => CASE_LOWER]);

$contrats = [];
$volumesHoraires = [];

foreach( $cData as $contrat){
    $contrat['volumesHoraires'] = [];
    foreach ($vhData as $vh) {
        $volumesHoraires[$vh['id']] = $vh;
        if ($vh['contrat_id'] == $contrat['id']) {
            $contrat['volumesHoraires'][$vh['id']] = $vh;
        }
    }
    $contrats[$contrat['id']] = $contrat;
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

    $vhs = $contrat['volumesHoraires'];

    ?>
    <div <?php if ($isAvenant) echo ' style="margin-left: 4em"'; ?>>
        <div class="card <?php if ($isHistorise) echo 'bg-danger' ?>">
            <div class="card-header"><?= implode(' | ', $title) ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
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
                    <div class="col">
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
        foreach ($volumesHoraires as $vh) {
            if (!$vh['contrat_id'] && !$vh['histo_destruction']) {
                echo '<tr>';
                foreach ($vh as $prop => $value) {
                    if (!in_array($prop, $noDisplayVh)) {
                        echo '<td>' . ($value ?: '<i style="color: gray">null</i>') . '</td>';
                    }
                }
                echo '</tr>';
            }
        }
    }

    ?>
</table>
<?php
