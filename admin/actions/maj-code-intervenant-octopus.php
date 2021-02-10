<?php

$bdd     = $this->getBdd();
$console = $this->getConsole();
$console->printMainTitle("Maj des codes intervenants de harpège vers Octopus", $console::BG_BLUE);
$console->println("Récupération source id 'Octopus'", $console::COLOR_GREEN);
//On récupére la source octopus
$sqlSource = "SELECT id FROM source WHERE code = 'Octopus'";
$result    = $bdd->select($sqlSource, [], ['fetch' => $bdd::FETCH_ONE]);

if ($result) {
    $sourceId = $result['ID'];
    $console->println("Source id : " . $sourceId);
} else {
    $console->println('Source Octopus inconnu', $console::BG_RED);
}

//On récupére tous les intervenants de OSE
$sql = "SELECT 
           id       intervenant_id,
           code     code_harpege,
           source_id   source_harpege,
           annee_id
       FROM intervenant 
       WHERE source_id = (SELECT id FROM source WHERE code ='Harpege')";

$resultIntervenantOse = $bdd->select($sql);

//On récupére tous les intervenants de MV_INTERVENANT
$sql = "SELECT 
            code        code_octopus,
            code_rh     code_harpege            
        FROM intervenant_octopus";

$resultIntervenantOctopus  = $bdd->select($sql);
$mappingCodeOctopusHarpege = [];
foreach ($resultIntervenantOctopus as $intervenantOcto) {
    if (!array_key_exists($intervenantOcto['CODE_HARPEGE'], $mappingCodeOctopusHarpege)) {
        $mappingCodeOctopusHarpege[$intervenantOcto['CODE_HARPEGE']] = $intervenantOcto['CODE_OCTOPUS'];
    }
}
$i = 0;
foreach ($resultIntervenantOse as $intervenantOse) {
    if (array_key_exists($intervenantOse['CODE_HARPEGE'], $mappingCodeOctopusHarpege)) {
        $console->println("Migration intervenant code harpege : " . $intervenantOse['CODE_HARPEGE'] . " vers code octopus " . $mappingCodeOctopusHarpege[$intervenantOse['CODE_HARPEGE']]);
    } else {
        if (in_array($intervenantOse['ANNEE_ID'], [2020, 2021])) {
            $i++;
        }
        $console->println("Intervenant non trouvé dans Octopus " . $intervenantOse['CODE_HARPEGE'] . " / " . $intervenantOse['ANNEE_ID']);
    }
}

$console->println("Nombre d'intervenant non migré : " . $i);


die;
