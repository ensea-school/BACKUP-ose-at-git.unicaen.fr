<?php

$bdd = $oa->getBdd();


$c->begin('Mise à jour du taux horaire');


$c->msg('Ajout du nouveau taux');

$dsql = "DELETE FROM TAUX_HORAIRE_HETD WHERE ID NOT IN (1,2)";
$bdd->exec($dsql);

$isql = "
INSERT INTO TAUX_HORAIRE_HETD(
  ID,
  VALEUR,
  HISTO_CREATION,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATION,
  HISTO_MODIFICATEUR_ID
) VALUES (
  3,
  42.86,
  TO_DATE('01/09/2022 00:00', 'dd/mm/YYYY HH24:MI'),
  (SELECT id FROM utilisateur where username='oseappli'),
  TO_DATE('01/07/2022 00:00', 'dd/mm/YYYY HH24:MI'),
  (SELECT id FROM utilisateur where username='oseappli')
)
";
$bdd->exec($isql);


$vreps = [
    'V_CONTRAT_MAIN'                => [
        'c.histo_creation'  => 'a.date_debut',
        'th.histo_creation' => 'th.histo_modification',
    ],
    'V_ETAT_PAIEMENT'               => [
        'OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) )' => 'a.taux_hetd',
    ],
    'V_EXPORT_PAIEMENT_WINPAIE'     => [
        'ose_formule.get_taux_horaire_hetd(nvl(t2.date_mise_en_paiement, sysdate))'  => '(select taux_hetd from annee ann where ann.id = i.annee_id)',
        'OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(t2.date_mise_en_paiement,SYSDATE) )' => '(select taux_hetd from annee ann where ann.id = i.annee_id)',

    ],
    'V_EXPORT_PAIEMENT_SIHAM'       => [
        'ose_formule.get_taux_horaire_hetd(nvl(t2.date_mise_en_paiement, sysdate))' => '(select taux_hetd from annee ann where ann.id = i.annee_id)',
    ],
    'V_EXP_HETD_CENTRE_COUT'        => [
        'OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) )' => 'a.taux_hetd',
        'OSE_FORMULE.GET_TAUX_HORAIRE_HETD( SYSDATE )'                                => '(select taux_hetd from annee ann where ann.id = annee_id)',
    ],
    'V_IMPUTATION_BUDGETAIRE_SIHAM' => [
        'ose_formule.get_taux_horaire_hetd(nvl(mep.date_mise_en_paiement, sysdate))'  => 'a.taux_hetd',
        'OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) )' => 'a.taux_hetd',
    ],
];


foreach ($vreps as $view => $reps) {
    $c->msg('Mise à jour de la vue ' . $view);
    $vcm = $bdd->view()->get($view);

    if (isset($vcm[$view]['definition'])) {
        $vcm = $vcm[$view]['definition'];
        foreach ($reps as $s => $r) {
            $vcm = str_ireplace($s, $r, $vcm);
        }
        $bdd->exec($vcm);
    }
}

$c->end('Taux horaire actualisé');