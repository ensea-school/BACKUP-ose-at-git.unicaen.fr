SELECT
  vde.libelle                as libelle,
  tpd.typ_dip_apo            as z_type_formation_id,
  CASE etp.prem_sem
    WHEN 1 THEN 1 -- semestre 1 => première année
    WHEN 3 THEN 2 -- semestre 3 => deuxième année
    WHEN 5 THEN 3 -- semestre 5 => troisième année
    ELSE NULL
  END                        as niveau,
  0                          as specifique_echanges,
  vde.cod_cip                as z_structure_id,
  'Actul'                    as z_source_id,
  vde.id                     as source_code,
  case tpd.COD_CURSUS_LMD -- Codification LMD standard
    WHEN 'L' THEN 'D101'
    WHEN 'M' THEN 'D102'
    WHEN 'D' THEN 'D103'
    ELSE NULL             -- sinon NULL
  END                        as z_domaine_fonctionnel_id,
  anu.COD_ANU                as annee_id,
  CASE WHEN etp.code IS NOT NULL AND MAX(vti.code) IS NOT NULL
    THEN concat(ltrim(rtrim(etp.code)), '_', cast(MAX(vti.code) as char(255)))
    ELSE concat('act_',vde.id)
  END                        as code,
  CASE WHEN SUM(CASE pti.LIBELLE_COURT WHEN 'FI' THEN 1 ELSE 0 END) >= 1 THEN 1 ELSE 0 END fi,
  CASE WHEN SUM(CASE pti.LIBELLE_COURT WHEN 'FC' THEN 1 ELSE 0 END) >= 1 THEN 1 ELSE 0 END fc,
  CASE WHEN SUM(CASE pti.LIBELLE_COURT WHEN 'FA' THEN 1 ELSE 0 END) >= 1 THEN 1 ELSE 0 END fa,
  vde.EFF_PREV               as effectif
FROM
    PREV_PROJET               anu
    JOIN PREV_DIPLOME         dip on dip.prev_projet_id = anu.cod_anu
    JOIN PREV_TYP_DIPLOME     tpd on tpd.id = dip.prev_typ_diplome_id
    JOIN PREV_VERSION_DIPLOME vdi on vdi.prev_diplome_id = dip.id
    JOIN PREV_ETAPE           etp on etp.prev_version_diplome_id = vdi.id
    JOIN PREV_VERSION_ETAPE   vde on vde.prev_etape_id = etp.id
    LEFT JOIN PREV_VET_TYPINS vti on vde.id = vti.prev_vet_id
    LEFT JOIN PREV_TYP_INS    pti ON pti.COD_TYP_INS = vti.PREV_TYPINS_ID
WHERE
  anu.temoin_actif = 1 -- on ne sélectionne que les projets actifs
  AND vde.STATUT IN ('TERMINE','FERME','VALIDE')  -- on ne récupère que ce qui est terminé ou fermé ou validé
  AND tpd.COD_CURSUS_LMD IN ('L','M','D')
GROUP BY
  vde.libelle,
  tpd.typ_dip_apo,
  etp.prem_sem,
  etp.cod_cmp,
  vde.id,
  tpd.COD_CURSUS_LMD,
  anu.COD_ANU,
  etp.code
