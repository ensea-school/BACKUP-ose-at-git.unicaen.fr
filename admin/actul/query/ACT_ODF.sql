SELECT
  COALESCE(pep.CODE,CONCAT('id_',pep.id))                     as code,
  pep.LIBELLE_long                                            as libelle,
  pp.COD_ANU                                                  as annee_id,
  pep.PREV_VET_ID                                             as z_etape_id,
  pe.COD_CMP                                                  as z_structure_id,
  CASE WHEN pn.tem_sem = 1 THEN pe.prem_sem ELSE NULL END     as z_periode_id_semestre,
  CASE WHEN pn.tem_sem = 1 THEN pep.elp_order ELSE NULL END   as z_periode_id_ordre,
  CASE WHEN elp_ead.PREV_ELP_ID IS NOT NULL THEN 1 ELSE 0 END as taux_foad,
  pep.CODE_SECTION_CNU                                        as z_discipline_id,
  'Actul'                                                     as z_source_id,
  pep.id                                                      as source_code,
  pep.NB_CHOIX                                                as choix_minimum,
  pep.NB_CHOIX_MAX                                            as choix_maximum,
  pep.id                                                      as id,
  pep.nel                                                     as type_noeud,
  CASE pep.nel WHEN 'EC' THEN 1 WHEN 'STAG' THEN 1 ELSE 0 END as tem_element_pedagogique,
  pep.PREV_ELP_PARENT_ID                                      as element_parent_id,
  pep.PREV_ELP_REFERENCE_ID                                   as element_ref_id
FROM
       PREV_ELEMENT_PEDAGOGI   pep
  JOIN PREV_VERSION_ETAPE      pve ON pve.id = pep.PREV_VET_ID
  JOIN PREV_NEL                 pn ON pn.COD_NEL = pep.NEL
  JOIN PREV_ETAPE               pe ON pe.id = pve.PREV_ETAPE_ID
  JOIN PREV_VERSION_DIPLOME    pvd ON pvd.id = pe.PREV_VERSION_DIPLOME_ID
  JOIN PREV_DIPLOME             pd ON pd.id = pvd.PREV_DIPLOME_ID
  JOIN PREV_PROJET              pp ON pp.COD_ANU = pd.PREV_PROJET_ID
  LEFT JOIN (
    SELECT   ph.PREV_ELP_ID
    FROM     PREV_HEUS ph
    WHERE    ph.TYP_HEU IN ('EAD') -- EAD => FOAD
    GROUP BY ph.PREV_ELP_ID
    HAVING   SUM(ph.NB_HEURES) > 0
  )                        elp_ead ON elp_ead.PREV_ELP_ID = pep.id
WHERE
  pp.TEMOIN_ACTIF = 1        -- on ne sélectionne que les projets actifs
  AND pve.STATUT = 'TERMINE' -- on ne récupère que ce qui est terminé