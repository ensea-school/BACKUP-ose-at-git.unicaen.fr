SELECT
  z_type_intervention_id,
  'Actul'      z_source_id,
  z_element_pedagogique_id,
  SUM(groupes) groupes
FROM
(
  SELECT
    peg.typ_heu     z_type_intervention_id,
    COALESCE(pep.prev_elp_reference_id,pep.id) z_element_pedagogique_id,
    peg.nb_gpes    groupes
  FROM
         PREV_ELP_CALC_NB_GPES peg
    JOIN PREV_ELEMENT_PEDAGOGI pep1 ON pep1.id = peg.prev_elp_id
    JOIN PREV_ELEMENT_PEDAGOGI pep ON pep.id = COALESCE(pep1.prev_elp_reference_id,pep1.id)
    JOIN PREV_VERSION_ETAPE    pve ON pve.id = pep.PREV_VET_ID
    JOIN PREV_ETAPE             pe ON pe.id = pve.PREV_ETAPE_ID
    JOIN PREV_VERSION_DIPLOME  pvd ON pvd.id = pe.PREV_VERSION_DIPLOME_ID
    JOIN PREV_DIPLOME           pd ON pd.id = pvd.PREV_DIPLOME_ID
    JOIN PREV_PROJET            pp ON pp.COD_ANU = pd.PREV_PROJET_ID
  WHERE
    pp.TEMOIN_ACTIF = 1        -- on ne sélectionne que les projets actifs
    AND pve.STATUT = 'TERMINE' -- on ne récupère que ce qui est terminé
) t
GROUP BY
  z_type_intervention_id,
  z_element_pedagogique_id