SELECT 

annee, i_nom, i_code, sum(dues) dues, sum(payees) payees, sum(trop_paye) trop_paye

FROM 
(SELECT
  i.annee_id  annee,
  i.prenom || ' ' || i.nom_usuel i_nom,
  i.source_code i_code,
  AVG(frs.heures_compl_fi + frs.heures_compl_fa + frs.heures_compl_fc + frs.heures_compl_fc_majorees) dues,
  SUM(mep.heures) payees,
  SUM(mep.heures) - AVG(frs.heures_compl_fi + frs.heures_compl_fa + frs.heures_compl_fc + frs.heures_compl_fc_majorees) trop_paye 
FROM
  formule_resultat_service frs
  JOIN formule_resultat fr ON fr.id = frs.formule_resultat_id
  JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code = 'REALISE'
  JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id AND evh.code = 'valide'
  JOIN intervenant i ON i.id = fr.intervenant_id
  LEFT JOIN mise_en_paiement mep ON mep.formule_res_service_id = frs.id AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
GROUP BY
  frs.service_id, i.id, i.prenom, i.nom_usuel, i.source_code, i.annee_id
HAVING
  ABS(SUM(mep.heures) - AVG(frs.heures_compl_fi + frs.heures_compl_fa + frs.heures_compl_fc + frs.heures_compl_fc_majorees)) > 0.01
  
UNION ALL

SELECT
  i.annee_id  annee,
  i.prenom || ' ' || i.nom_usuel i_nom,
  i.source_code i_code,
  AVG(frs.heures_compl_referentiel) dues,
  SUM(mep.heures) payees,
  SUM(mep.heures) - AVG(frs.heures_compl_referentiel) trop_paye 
FROM
  formule_resultat_service_ref frs
  JOIN formule_resultat fr ON fr.id = frs.formule_resultat_id
  JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code = 'REALISE'
  JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id AND evh.code = 'valide'
  JOIN intervenant i ON i.id = fr.intervenant_id
  LEFT JOIN mise_en_paiement mep ON mep.formule_res_service_ref_id = frs.id AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
GROUP BY
  frs.service_referentiel_id, i.id, i.prenom, i.nom_usuel, i.source_code, i.annee_id
HAVING
  ABS(SUM(mep.heures) - AVG(frs.heures_compl_referentiel)) > 0.01
) t1

GROUP BY
  annee, i_nom, i_code
  
HAVING
  sum(trop_paye) > 0;
  
  
  
