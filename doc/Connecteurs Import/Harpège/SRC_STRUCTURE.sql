CREATE OR REPLACE VIEW SRC_STRUCTURE AS
WITH harpege_query AS (
  SELECT
    str.c_structure  code,
    str.lc_structure libelle_court,
    str.ll_structure libelle_long,
    null             adresse_precisions,
    aa.no_voie_a     adresse_numero,
    aa.bis_ter_a     z_adresse_numero_compl_id,
    aa.c_voie        z_adresse_voirie_id,
    aa.nom_voie_a    adresse_voie,
    aa.localite_a    adresse_lieu_dit,
    aa.code_postal_a adresse_code_postal,
    aa.ville_a       adresse_commune,
    aa.c_pays        z_adresse_pays_id,
    'Harpege'        z_source_id,
    str.c_structure  source_code
  FROM
              structure@harpprod              str
    LEFT JOIN localisation_structure@harpprod  ls ON ls.c_structure = str.c_structure AND ls.tem_local_principal = 'O'
    LEFT JOIN local@harpprod                    l ON l.c_local = ls.c_local
    LEFT JOIN adresse_administrat@harpprod     aa ON aa.id_adresse_admin = l.id_adresse_admin AND COALESCE(aa.d_fin_val,SYSDATE) >= SYSDATE
  WHERE
    SYSDATE BETWEEN str.date_ouverture AND COALESCE( str.date_fermeture, SYSDATE )
    AND (str.c_structure = 'UNIV' OR str.c_structure_pere = 'UNIV')
)
SELECT
  hq.code                 code,
  hq.libelle_court        libelle_court,
  hq.libelle_long         libelle_long,
  hq.adresse_precisions   adresse_precisions,
  hq.adresse_numero       adresse_numero,
  anc.id                  adresse_numero_compl_id,
  v.id                    adresse_voirie_id,
  hq.adresse_voie         adresse_voie,
  hq.adresse_lieu_dit     adresse_lieu_dit,
  hq.adresse_code_postal  adresse_code_postal,
  hq.adresse_commune      adresse_commune,
  p.id                    z_adresse_pays_id,
  src.id                  source_id,
  hq.source_code          source_code
FROM
            harpege_query         hq
       JOIN source               src ON src.code = hq.z_source_id
  LEFT JOIN adresse_numero_compl anc ON anc.code = hq.z_adresse_numero_compl_id
  LEFT JOIN voirie                 v ON v.source_code = hq.z_adresse_voirie_id
  LEFT JOIN pays                   p ON p.source_code = hq.z_adresse_pays_id;
