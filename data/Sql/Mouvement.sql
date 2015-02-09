SELECT
  frs.id formule_resultat_servcie_id, cc.id centre_cout_id
FROM
  formule_resultat_service   frs
  JOIN service                 s ON s.id = frs.service_id AND s.element_pedagogique_id IS NOT NULL
  JOIN centre_cout_ep       ccep ON ccep.element_pedagogique_id = s.element_pedagogique_id
  JOIN centre_cout            cc ON cc.id = ccep.centre_cout_id AND cc.structure_id = s.structure_ens_id AND 1 = ose_divers.comprise_entre( cc.histo_creation, cc.histo_destruction )
  JOIN cc_activite             a ON a.id = cc.activite_id AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id AND 1 = ose_divers.comprise_entre( tr.histo_creation, tr.histo_destruction )
WHERE
  (
    (frs.heures_compl_fi > 0 AND tr.fi = 1 AND a.fi = 1 )
    OR (frs.heures_compl_fa > 0 AND tr.fa = 1 AND a.fa = 1 )
    OR (frs.heures_compl_fc + frs.heures_compl_fc_majorees > 0 AND tr.fc = 1 AND a.fc = 1 )
  )

UNION

SELECT
  frs.id formule_resultat_servcie_id, cc.id
FROM
  formule_resultat_service   frs
  JOIN service                 s ON s.id = frs.service_id AND s.element_pedagogique_id IS NOT NULL
  JOIN centre_cout            cc ON cc.structure_id = s.structure_ens_id AND 1 = ose_divers.comprise_entre( cc.histo_creation, cc.histo_destruction )
  JOIN cc_activite             a ON a.id = cc.activite_id AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id AND 1 = ose_divers.comprise_entre( tr.histo_creation, tr.histo_destruction )
  LEFT JOIN centre_cout_ep  ccep ON ccep.element_pedagogique_id = s.element_pedagogique_id
WHERE
  (
    (frs.heures_compl_fi > 0 AND tr.fi = 1 AND a.fi = 1 )
    OR (frs.heures_compl_fa > 0 AND tr.fa = 1 AND a.fa = 1 )
    OR (frs.heures_compl_fc + frs.heures_compl_fc_majorees > 0 AND tr.fc = 1 AND a.fc = 1 )
  )
  AND ccep.id IS NULL
;





