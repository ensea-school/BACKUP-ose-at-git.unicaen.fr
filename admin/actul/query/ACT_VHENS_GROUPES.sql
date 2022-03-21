SELECT
  z_type_intervention_id,
  'Actul'      z_source_id,
  z_element_pedagogique_id,
  SUM(groupes) groupes
FROM
(
  SELECT
    peg.typ_heu    z_type_intervention_id,
    pep.id         z_element_pedagogique_id,
    peg.nb_gpes    groupes
  FROM
         PREV_ELP_CALC_NB_GPES peg
    JOIN PREV_ELEMENT_PEDAGOGI ep1 ON ep1.id = peg.prev_elp_id
    JOIN PREV_ELEMENT_PEDAGOGI pep ON pep.id = COALESCE(ep1.prev_elp_reference_id,ep1.id)
    JOIN PREV_VERSION_ETAPE    pve ON pve.id = pep.PREV_VET_ID
    JOIN PREV_ETAPE             pe ON pe.id = pve.PREV_ETAPE_ID
    JOIN PREV_VERSION_DIPLOME  pvd ON pvd.id = pe.PREV_VERSION_DIPLOME_ID
    JOIN PREV_DIPLOME           pd ON pd.id = pvd.PREV_DIPLOME_ID
    JOIN PREV_PROJET            pp ON pp.COD_ANU = pd.PREV_PROJET_ID
  WHERE
    pp.TEMOIN_ACTIF = 1        -- on ne sélectionne que les projets actifs
    AND pve.STATUT IN ('TERMINE','FERME','VALIDE')  -- on ne récupère que ce qui est terminé ou fermé ou validé
) t
GROUP BY
  z_type_intervention_id,
  z_element_pedagogique_id