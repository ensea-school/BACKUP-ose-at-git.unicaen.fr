<?php

$bdd     = $this->getBdd();
$console = $this->getConsole();
$console->printMainTitle("Maj des codes intervenants de harpège vers Octopus", $console::BG_BLUE);
$console->println("Récupération source id 'Octopus'", $console::COLOR_GREEN);
$notFound = [];
//On récupére la source octopus
$sqlSource = "SELECT id FROM source WHERE code = 'Octopus'";
$result    = $bdd->select($sqlSource, [], ['fetch' => $bdd::FETCH_ONE]);

if ($result) {
    $sourceIdOctopus = $result['ID'];
    $console->println("Source id : " . $sourceIdOctopus);
} else {
    $console->println('Source Octopus inconnu', $console::BG_RED);
    die;
}

//On récupére la source harpege
$sqlSource = "SELECT id FROM source WHERE code = 'Harpege'";
$result    = $bdd->select($sqlSource, [], ['fetch' => $bdd::FETCH_ONE]);

if ($result) {
    $sourceIdHarpege = $result['ID'];
    $console->println("Source id : " . $sourceIdHarpege);
} else {
    $console->println('Source Harpege inconnu', $console::BG_RED);
    die;
}

//On récupére tous les intervenants de OSE provenant de la source Harpege
$sql = "SELECT DISTINCT
           code     code_harpege
       FROM intervenant 
       WHERE source_id =" . $sourceIdHarpege;

$resultIntervenantOse = $bdd->select($sql);

//On récupére de octopus le mapping des codes harpeges vs code octopus
$sql = "SELECT
            c_individu_chaine        code_octopus,
            c_src_individu           code_harpege            
        FROM individu_unique
        WHERE c_source = 'HARP'";

$resultIntervenantOctopus  = $bdd->select($sql);
$mappingCodeOctopusHarpege = [];

foreach ($resultIntervenantOctopus as $intervenantOcto) {
    if (!array_key_exists($intervenantOcto['CODE_HARPEGE'], $mappingCodeOctopusHarpege)) {
        $mappingCodeOctopusHarpege[$intervenantOcto['CODE_HARPEGE']] = $intervenantOcto['CODE_OCTOPUS'];
    }
}
$i = 0;
$console->begin("Début migration des codes intervenant OSE de Harpège vers Octopus");
$totalIntervenant = count($resultIntervenantOse);
//On commence la migration des codes intervenants et on change la source en octopus
$bdd->trigger()->disable('INTERVENANT_CK');
foreach ($resultIntervenantOse as $intervenantOse) {

    $i++;
    $pourcent = round(($i * 100) / $totalIntervenant);
    if (array_key_exists($intervenantOse['CODE_HARPEGE'], $mappingCodeOctopusHarpege)) {

        $console->msg($pourcent . " % Migration code intervenant  / code harpege : " . $intervenantOse['CODE_HARPEGE'] . " vers code octopus " . $mappingCodeOctopusHarpege[$intervenantOse['CODE_HARPEGE']], true);
        $sql = "UPDATE intervenant SET code = '" . $mappingCodeOctopusHarpege[$intervenantOse['CODE_HARPEGE']] . "', source_id =" . $sourceIdOctopus . " WHERE source_id=" . $sourceIdHarpege . " AND code = '" . $intervenantOse['CODE_HARPEGE'] . "'";
        $bdd->exec($sql);
    } else {
        $notFound[] = $intervenantOse['CODE_HARPEGE'];
    }
}
$bdd->trigger()->enable('INTERVENANT_CK');

ksort($notFound);
$console->end("Début migration des codes intervenant OSE de Harpège vers Octopus");
$console->println("==================================");
$console->println("Intervenants non trouvés dans Octopus", $console::BG_RED);
$console->println(implode(',', $notFound));
$console->println("==================================");


//Calcul des tableaux pour mettre à jour suite changement des codes intervenants
$oa->exec('calcul-tableaux-bord');

$console->println("Fin migration code intervenant OSE de harpege vers octopus", $console::BG_BLUE);

