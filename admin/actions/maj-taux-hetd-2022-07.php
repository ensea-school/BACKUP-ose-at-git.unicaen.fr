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
        'left join taux_horaire_hetd( +)th[\n\(\= ._,a-zA-Z]*' => "LEFT JOIN taux_horaire_hetd    th ON th.valeur = OSE_FORMULE.GET_TAUX_HORAIRE_HETD(a.date_debut",
        'th.histo_creation'                        => 'th.histo_modification',
    ],
    'V_ETAT_PAIEMENT'               => [
        'OSE_FORMULE.GET_TAUX_HORAIRE_HETD\( *NVL\( *mep.date_mise_en_paiement, *SYSDATE *\) *\)' => 'a.taux_hetd',
    ],
    'V_EXPORT_PAIEMENT_WINPAIE'     => [
        'ose_formule.get_taux_horaire_hetd\( *nvl\( *t2.date_mise_en_paiement, *sysdate\) *\)' => '(select taux_hetd from annee ann where ann.id = i.annee_id)',
    ],
    'V_EXPORT_PAIEMENT_SIHAM'       => [
        'ose_formule.get_taux_horaire_hetd\( *nvl\( *t2.date_mise_en_paiement, *sysdate\)\)' => '(select taux_hetd from annee ann where ann.id = i.annee_id)',
    ],
    'V_EXP_HETD_CENTRE_COUT'        => [
        'OSE_FORMULE.GET_TAUX_HORAIRE_HETD\( *NVL\( *mep.date_mise_en_paiement, *SYSDATE *\) *\)' => 'a.taux_hetd',
        'OSE_FORMULE.GET_TAUX_HORAIRE_HETD\( *SYSDATE *\)'                                        => '(select taux_hetd from annee ann where ann.id = annee_id)',
    ],
    'V_IMPUTATION_BUDGETAIRE_SIHAM' => [
        'ose_formule.get_taux_horaire_hetd\( *nvl\( *mep.date_mise_en_paiement, *sysdate *\) *\)' => 'a.taux_hetd',
    ],
];
$dir   = '/app/data/ddl/view/';

foreach ($vreps as $view => $reps) {

    $vcm = $bdd->view()->get($view);

    $count = 0;
    if (isset($vcm[$view]['definition'])) {
        $vcm = $vcm[$view]['definition'];
        foreach ($reps as $s => $r) {
            $cnt = 0;
            $vcm = preg_replace('/' . $s . '/si', $r, $vcm, -1, $cnt);
            $count += $cnt;
        }

        $c->msg('Mise à jour de la vue ' . $view.' : '.$count.' modification(s) apportée(s)');
        $bdd->exec($vcm);
    }
}

$c->end('Taux horaire actualisé');
