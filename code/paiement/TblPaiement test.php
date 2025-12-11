<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$c = $container->get(\UnicaenTbl\Service\TableauBordService::class);
$bdd = $container->get(\Unicaen\BddAdmin\Bdd::class);

/** @var Paiement\Tbl\Process\PaiementProcess $ptblProcess */
$ptblProcess = $c->getTableauBord(\Application\Provider\Tbl\TblProvider::PAIEMENT)->getProcess();


$vTblPaiementData = array (
    0 =>
        array (
            'key' => 'r-7391-5',
            'calcul_semestriel' => '1',
            'a_payer_id' => '20129',
            'annee_id' => '2023',
            'service_id' => NULL,
            'service_referentiel_id' => '7391',
            'mission_id' => NULL,
            'volume_horaire_id' => '20129',
            'type_intervenant_id' => '1',
            'intervenant_id' => '26488',
            'structure_id' => '45',
            'type_heures_id' => '5',
            'def_domaine_fonctionnel_id' => '27',
            'def_centre_cout_id' => '76',
            'taux_remu_id' => '1',
            'taux_conges_payes' => '1',
            'heures' => '44.98',
            'periode_ens_id' => NULL,
            'periode_ens_code' => NULL,
            'horaire_debut' => '2023-09-01 00:00:00.000',
            'horaire_fin' => '2024-08-31 00:00:00.000',
            'mise_en_paiement_id' => '107591',
            'date_mise_en_paiement' => '2024-11-30 08:38:13.000',
            'periode_paiement_id' => '15',
            'mep_centre_cout_id' => '76',
            'mep_heures' => '46.04',
            'mep_domaine_fonctionnel_id' => '27',
        ),
    1 =>
        array (
            'key' => 'r-8452-5',
            'calcul_semestriel' => '1',
            'a_payer_id' => '20130',
            'annee_id' => '2023',
            'service_id' => NULL,
            'service_referentiel_id' => '8452',
            'mission_id' => NULL,
            'volume_horaire_id' => '20130',
            'type_intervenant_id' => '1',
            'intervenant_id' => '26488',
            'structure_id' => '45',
            'type_heures_id' => '5',
            'def_domaine_fonctionnel_id' => '27',
            'def_centre_cout_id' => '76',
            'taux_remu_id' => '1',
            'taux_conges_payes' => '1',
            'heures' => '16.37',
            'periode_ens_id' => NULL,
            'periode_ens_code' => NULL,
            'horaire_debut' => '2023-09-01 00:00:00.000',
            'horaire_fin' => '2024-08-31 00:00:00.000',
            'mise_en_paiement_id' => '107592',
            'date_mise_en_paiement' => '2024-11-30 08:38:13.000',
            'periode_paiement_id' => '15',
            'mep_centre_cout_id' => '76',
            'mep_heures' => '16.75',
            'mep_domaine_fonctionnel_id' => '27',
        ),
    2 =>
        array (
            'key' => 'r-10495-5',
            'calcul_semestriel' => '1',
            'a_payer_id' => '21824',
            'annee_id' => '2023',
            'service_id' => NULL,
            'service_referentiel_id' => '10495',
            'mission_id' => NULL,
            'volume_horaire_id' => '21824',
            'type_intervenant_id' => '1',
            'intervenant_id' => '26488',
            'structure_id' => '45',
            'type_heures_id' => '5',
            'def_domaine_fonctionnel_id' => '27',
            'def_centre_cout_id' => '76',
            'taux_remu_id' => '1',
            'taux_conges_payes' => '1',
            'heures' => '31.7',
            'periode_ens_id' => NULL,
            'periode_ens_code' => NULL,
            'horaire_debut' => '2023-09-01 00:00:00.000',
            'horaire_fin' => '2024-08-31 00:00:00.000',
            'mise_en_paiement_id' => '107590',
            'date_mise_en_paiement' => '2024-11-30 08:38:13.000',
            'periode_paiement_id' => '15',
            'mep_centre_cout_id' => '76',
            'mep_heures' => '32.45',
            'mep_domaine_fonctionnel_id' => '27',
        ),
    3 =>
        array (
            'key' => 'r-10495-5',
            'calcul_semestriel' => '1',
            'a_payer_id' => '27537',
            'annee_id' => '2023',
            'service_id' => NULL,
            'service_referentiel_id' => '10495',
            'mission_id' => NULL,
            'volume_horaire_id' => '27537',
            'type_intervenant_id' => '1',
            'intervenant_id' => '26488',
            'structure_id' => '45',
            'type_heures_id' => '5',
            'def_domaine_fonctionnel_id' => '27',
            'def_centre_cout_id' => '76',
            'taux_remu_id' => '1',
            'taux_conges_payes' => '1',
            'heures' => '-11.02',
            'periode_ens_id' => NULL,
            'periode_ens_code' => NULL,
            'horaire_debut' => '2023-09-01 00:00:00.000',
            'horaire_fin' => '2024-08-31 00:00:00.000',
            'mise_en_paiement_id' => '107590',
            'date_mise_en_paiement' => '2024-11-30 08:38:13.000',
            'periode_paiement_id' => '15',
            'mep_centre_cout_id' => '76',
            'mep_heures' => '32.45',
            'mep_domaine_fonctionnel_id' => '27',
        ),
);



echo '<h2>Data en entrée</h2>';
echo phpDump($vTblPaiementData);

$resData = $ptblProcess->testData($vTblPaiementData);

$res = phpDump($resData);
echo '<h2>Résultat</h2>';
echo phpDump($res);
