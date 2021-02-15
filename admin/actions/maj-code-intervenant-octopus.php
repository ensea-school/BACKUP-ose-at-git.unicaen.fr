<?php

$bdd     = $this->getBdd();
$console = $this->getConsole();
$console->printMainTitle("Maj des codes intervenants de harpège vers Octopus", $console::BG_BLUE);
$console->println("Récupération source id 'Octopus'", $console::COLOR_GREEN);
//On récupére la source octopus
$sqlSource = "SELECT id FROM source WHERE code = 'Octopus'";
$result    = $bdd->select($sqlSource, [], ['fetch' => $bdd::FETCH_ONE]);

if ($result) {
    $sourceIdOctopus = $result['ID'];
    $console->println("Source id : " . $sourceIdOctopus);
} else {
    $console->println('Source Octopus inconnu', $console::BG_RED);
}

//On récupére tous les intervenants de OSE provenant de la source Harpege
$sql = "SELECT 
           id       intervenant_id,
           code     code_harpege,
           source_id   source_harpege,
           annee_id
       FROM intervenant 
       WHERE source_id = (SELECT id FROM source WHERE code ='Harpege')";

$resultIntervenantOse = $bdd->select($sql);

//On récupére de octopus le mapping des codes harpeges vs code octopus
$sql = " SELECT DISTINCT
   			FIRST_VALUE(c_individu_chaine) OVER (PARTITION BY c_individu_chaine ORDER BY ordre_source) code_octopus,
			FIRST_VALUE(c_source) OVER (PARTITION BY c_individu_chaine ORDER BY ordre_source) c_source,
			FIRST_VALUE(c_src_individu) OVER (PARTITION BY c_individu_chaine ORDER BY ordre_source) code_harpege FROM (
				SELECT DISTINCT
			   		u.c_individu_chaine,
			   		u.c_source,
			   		u.c_src_individu,
			   		CASE WHEN u.c_source = 'SIHAM' THEN 1
			            WHEN u.c_source = 'HARP'  THEN 2
			   		    WHEN u.c_source = 'OCTO'  THEN 3
			    	    WHEN u.c_source = 'APO'   THEN 4
			       END                  ordre_source
				FROM octo.individu_unique@octoprod u
	 		)
";

/*$sql = "SELECT
            c_individu_chaine        code_octopus,
            c_src_individu           code_harpege            
        FROM individu_unique
        WHERE c_source = 'HARP'";*/

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
foreach ($resultIntervenantOse as $intervenantOse) {
    $i++;
    $pourcent = round(($i * 100) / $totalIntervenant);
    if (array_key_exists($intervenantOse['CODE_HARPEGE'], $mappingCodeOctopusHarpege)) {
        $console->msg($pourcent . " % Migration code intervenant  / code harpege : " . $intervenantOse['CODE_HARPEGE'] . " vers code octopus " . $mappingCodeOctopusHarpege[$intervenantOse['CODE_HARPEGE']], true);
        $sql = "UPDATE intervenant SET code = '" . $mappingCodeOctopusHarpege[$intervenantOse['CODE_HARPEGE']] . "', source_id =" . $sourceIdOctopus . " WHERE code = '" . $intervenantOse['CODE_HARPEGE'] . "'";
        //$bdd->exec($sql);

    } else {
        $notFound [$intervenantOse['ANNEE_ID']][] = $intervenantOse['CODE_HARPEGE'];
    }
}
ksort($notFound);
$console->end("Début migration des codes intervenant OSE de Harpège vers Octopus");
$console->println("==================================");
$console->println("Intervenants non trouvés dans Octopus", $console::BG_RED);
foreach ($notFound as $annee => $intervenants) {
    $console->println("Année " . $annee . " : " . implode(',', $intervenants));
    $console->println("==================================");
}

$console->println("Fin migration code intervenant OSE de harpege vers octopus", $console::BG_BLUE);

