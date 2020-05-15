CREATE OR REPLACE FORCE VIEW SRC_INTERVENANT AS
WITH srci as (
SELECT
  i.code,
  c.id civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  COALESCE(i.date_naissance,TO_DATE('2099-01-01','YYYY-MM-DD')) date_naissance,
  pnaiss.id pays_naissance_id,
  dep.id departement_naissance_id,
  i.commune_naissance,
  pnat.id pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  si.id statut_id, si.source_code statut_code,
  s.id structure_id,
  src.id source_id, i.source_code,
  i.numero_insee, i.numero_insee_provisoire,
  i.iban, i.bic,
  g.id grade_id,
  NVL( d.id, d99.id ) discipline_id,
  i.critere_recherche,
  COALESCE (si.ordre,99999) ordre,
  MIN(COALESCE (si.ordre,99999)) OVER (PARTITION BY i.source_code) min_ordre
FROM
            mv_intervenant i
       JOIN source        src ON src.code = 'Harpege'
  LEFT JOIN civilite        c ON c.libelle_court = i.z_civilite_id
  LEFT JOIN structure       s ON s.source_code = i.z_structure_id
  LEFT JOIN statut_intervenant si ON si.source_code = i.z_statut_id
  LEFT JOIN grade           g ON g.source_code = i.z_grade_id
  LEFT JOIN pays       pnaiss ON pnaiss.source_code = i.z_pays_naissance_id
  LEFT JOIN pays         pnat ON pnat.source_code = i.z_pays_nationalite_id
  LEFT JOIN departement   dep ON dep.source_code = i.z_departement_naissance_id
  LEFT JOIN discipline d99 ON d99.source_code = '99'
  LEFT JOIN discipline d ON
    d.histo_destruction IS NULL
    AND 1 = CASE WHEN -- si rien n'ac été défini

      COALESCE( i.z_discipline_id_cnu, i.z_discipline_id_sous_cnu, i.z_discipline_id_spe_cnu, i.z_discipline_id_dis2deg ) IS NULL
      AND d.source_code = '00'

    THEN 1 WHEN -- si une CNU ou une spécialité a été définie...

      COALESCE( i.z_discipline_id_cnu, i.z_discipline_id_sous_cnu, z_discipline_id_spe_cnu ) IS NOT NULL

    THEN CASE WHEN -- alors on teste par les sections CNU et spécialités

      (
           ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || i.z_discipline_id_cnu || NVL(i.z_discipline_id_sous_cnu,'') || ',%'
        OR ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || i.z_discipline_id_cnu || NVL(i.z_discipline_id_sous_cnu,'00') || ',%'
      )
      AND ',' || NVL(d.CODES_CORRESP_3,'000') || ',' LIKE  '%,' || NVL(CASE WHEN d.CODES_CORRESP_3 IS NOT NULL THEN z_discipline_id_spe_cnu ELSE NULL END,'000') || ',%'

    THEN 1 ELSE 0 END ELSE CASE WHEN -- sinon on teste par les disciplines du 2nd degré

      i.z_discipline_id_dis2deg IS NOT NULL
      AND ',' || NVL(d.CODES_CORRESP_4,'') || ',' LIKE  '%,' || i.z_discipline_id_dis2deg || ',%'

    THEN 1 ELSE 0 END END -- fin du test
)
SELECT
  i.code code, lpad(i.code, 8, '0') utilisateur_code,
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  i.date_naissance,
  i.pays_naissance_id,
  i.departement_naissance_id,
  i.commune_naissance,
  i.pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  COALESCE(
    isai.statut_id,
    CASE WHEN i.statut_code = 'AUTRES' AND d.statut_id IS NOT NULL THEN d.statut_id ELSE i.statut_id END
  ) statut_id,
  i. structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_provisoire,
  i.iban, i.bic,
  i.grade_id,
  i.discipline_id,
  unicaen_import.get_current_annee annee_id,
  i.critere_recherche
FROM
  srci i
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = unicaen_import.get_current_annee
  LEFT JOIN intervenant_saisie  isai ON isai.intervenant_id = i2.id
  LEFT JOIN dossier               d  ON d.intervenant_id = i2.id AND d.histo_destruction IS NULL
WHERE
  i.ordre = i.min_ordre

UNION ALL

SELECT
  i.code code, lpad(i.code, 8, '0') utilisateur_code,
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  i.date_naissance,
  i.pays_naissance_id,
  i.departement_naissance_id,
  i.commune_naissance,
  i.pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  COALESCE(i2.statut_id,i.statut_id) statut_id,
  COALESCE(i2.structure_id,i.structure_id) structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_provisoire,
  i.iban, i.bic,
  i.grade_id,
  i.discipline_id,
  unicaen_import.get_current_annee - 1 annee_id,
  i.critere_recherche
FROM
  srci i
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = unicaen_import.get_current_annee - 1
WHERE
  i.ordre = i.min_ordre;