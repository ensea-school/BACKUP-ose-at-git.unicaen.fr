SELECT
  COALESCE(epr.prev_elp_reference_id,epr.id)  as z_noeud_sup_id,
  COALESCE(pep.prev_elp_reference_id,pep.id)  as z_noeud_inf_id,
  epr.nb_choix                                as choix_minimum,
  epr.nb_choix_max                            as choix_maximum,
  'Actul'                                     as z_source_id
FROM
  PREV_ELEMENT_PEDAGOGI           pep
  JOIN PREV_VERSION_ETAPE         pve ON pve.id = pep.PREV_VET_ID
  JOIN PREV_ETAPE                  pe ON pe.id = pve.PREV_ETAPE_ID
  JOIN PREV_VERSION_DIPLOME       pvd ON pvd.id = pe.PREV_VERSION_DIPLOME_ID
  JOIN PREV_DIPLOME                pd ON pd.id = pvd.PREV_DIPLOME_ID
  JOIN PREV_PROJET                 pp ON pp.COD_ANU = pd.PREV_PROJET_ID
  LEFT JOIN PREV_ELEMENT_PEDAGOGI epr ON epr.id = pep.prev_elp_parent_id
WHERE
  pp.TEMOIN_ACTIF = 1        -- on ne sélectionne que les projets actifs
  AND pep.prev_elp_parent_id IS NOT NULL
  AND pve.STATUT IN ('TERMINE','FERME','VALIDE')  -- on ne récupère que ce qui est terminé ou fermé ou validé