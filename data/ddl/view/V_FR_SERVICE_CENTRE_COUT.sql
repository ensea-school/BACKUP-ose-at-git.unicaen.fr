CREATE OR REPLACE FORCE VIEW V_FR_SERVICE_CENTRE_COUT AS
SELECT
  frs.id formule_resultat_service_id, cc.id centre_cout_id
FROM
  formule_resultat_service   frs
  JOIN parametre               p ON p.nom = 'centres_couts_paye'
  JOIN service                 s ON s.id = frs.service_id
  JOIN intervenant             i ON i.id = s.intervenant_id
  JOIN statut                 si ON si.id = i.statut_id
  JOIN type_intervenant       ti ON ti.id = si.type_intervenant_id
  JOIN element_pedagogique    ep ON ep.id = s.element_pedagogique_id
  JOIN centre_cout            cc ON cc.histo_destruction IS NULL

  JOIN centre_cout_structure ccs ON ccs.centre_cout_id = cc.id
                                AND ccs.structure_id = CASE WHEN p.valeur = 'enseignement' OR ti.code = 'E' THEN ep.structure_id ELSE COALESCE(i.structure_id,ep.structure_id) END
                                AND ccs.histo_destruction IS NULL

  JOIN cc_activite             a ON a.id = cc.activite_id
                                AND a.histo_destruction IS NULL

  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id
                                AND tr.histo_destruction IS NULL
WHERE
  (
    (frs.heures_compl_fi > 0 AND tr.fi = 1 AND a.fi = 1 )
    OR (frs.heures_compl_fa > 0 AND tr.fa = 1 AND a.fa = 1 )
    OR (frs.heures_compl_fc > 0 AND tr.fc = 1 AND a.fc = 1 )
    OR (frs.heures_compl_fc_majorees > 0 AND tr.fc_majorees = 1 AND a.fc_majorees = 1 )
  )

UNION

SELECT
  frs.id formule_resultat_service_id, cc.id centre_cout_id
FROM
  formule_resultat_service   frs
  JOIN service                 s ON s.id = frs.service_id
                                AND s.element_pedagogique_id IS NULL

  JOIN intervenant             i ON i.id = s.intervenant_id
  JOIN centre_cout            cc ON cc.histo_destruction IS NULL

  JOIN centre_cout_structure ccs ON ccs.centre_cout_id = cc.id
                                AND ccs.structure_id = i.structure_id
                                AND ccs.histo_destruction IS NULL

  JOIN cc_activite             a ON a.id = cc.activite_id
                                AND a.histo_destruction IS NULL

  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id
                                AND tr.histo_destruction IS NULL
WHERE
  (
    (frs.heures_compl_fi > 0 AND tr.fi = 1 AND a.fi = 1 )
    OR (frs.heures_compl_fa > 0 AND tr.fa = 1 AND a.fa = 1 )
    OR (frs.heures_compl_fc > 0 AND tr.fc = 1 AND a.fc = 1 )
    OR (frs.heures_compl_fc_majorees > 0 AND tr.fc_majorees = 1 AND a.fc_majorees = 1 )
  )