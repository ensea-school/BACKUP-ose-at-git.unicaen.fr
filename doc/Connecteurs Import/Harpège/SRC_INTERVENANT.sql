CREATE OR REPLACE FORCE VIEW SRC_INTERVENANT AS
WITH srci AS (
  SELECT
    i.code                                     code,
    i.utilisateur_code                         utilisateur_code,
    s.id                                       structure_id,
    COALESCE(si.id,sautre.id)                  statut_id,  -- à tester
    g.id                                       grade_id,
    COALESCE( d.id, d99.id )                   discipline_id,
    c.id                                       civilite_id,
    i.nom_usuel                                nom_usuel,
    i.prenom                                   prenom,
    COALESCE(i.date_naissance,TO_DATE('2099-01-01','YYYY-MM-DD')) date_naissance,
    i.nom_patronymique                         nom_patronymique,
    i.commune_naissance                        commune_naissance,
    pnaiss.id                                  pays_naissance_id,
    dep.id                                     departement_naissance_id,
    pnat.id                                    pays_nationalite_id,
    i.tel_pro                                  tel_pro,
    i.tel_perso                                tel_perso,
    i.email_pro                                email_pro,
    i.email_perso                              email_perso,
    i.adresse_precisions                       adresse_precisions,
    i.adresse_numero                           adresse_numero,
    anc.id                                     adresse_numero_compl_id,
    v.id                                       adresse_voirie_id,
    i.adresse_voie                             adresse_voie,
    i.adresse_lieu_dit                         adresse_lieu_dit,
    i.adresse_code_postal                      adresse_code_postal,
    i.adresse_commune                          adresse_commune,
    padr.id                                    adresse_pays_id,
    i.numero_insee                             numero_insee,
    COALESCE(i.numero_insee_provisoire,0)      numero_insee_provisoire,
    i.iban                                     iban,
    i.bic                                      bic,
    i.rib_hors_sepa                            rib_hors_sepa,
    i.autre_1                                  autre_1,
    i.autre_2                                  autre_2,
    i.autre_3                                  autre_3,
    i.autre_4                                  autre_4,
    i.autre_5                                  autre_5,
    empl.id                                    employeur_id,
    src.id                                     source_id,
    i.code || '-' || COALESCE(si.id,sautre.id) source_code
    ,ROW_NUMBER() OVER (PARTITION BY i.code || '-' || COALESCE(si.id,sautre.id) ORDER BY i.poids DESC, i.date_fin DESC) filtre1
  FROM
              mv_intervenant          i
         JOIN source                src ON src.code = 'Harpege'
    LEFT JOIN structure               s ON s.source_code = i.z_structure_id
    LEFT JOIN statut_intervenant sautre ON sautre.code = 'AUTRES' AND sautre.histo_destruction IS NULL
    LEFT JOIN statut_intervenant     si ON
      i.z_statut_id_contrat_trav IS NOT NULL AND (',' || si.codes_corresp_1 || ',' LIKE '%,' || i.z_statut_id_contrat_trav || ',%')
      OR (i.z_statut_id_type_pop IS NOT NULL AND ',' || si.codes_corresp_2 || ',' LIKE '%,' || i.z_statut_id_type_pop || ',%')
    LEFT JOIN grade                   g ON g.source_code = i.z_grade_id
    LEFT JOIN discipline              d ON
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
    LEFT JOIN discipline            d99 ON d99.source_code = '99'
    LEFT JOIN civilite                c ON c.libelle_court = i.z_civilite_id
    LEFT JOIN pays               pnaiss ON pnaiss.source_code = i.z_pays_naissance_id
    LEFT JOIN departement           dep ON dep.source_code = i.z_departement_naissance_id
    LEFT JOIN pays                 pnat ON pnat.source_code = i.z_pays_nationalite_id
    LEFT JOIN adresse_numero_compl  anc ON anc.code = i.z_adresse_numero_compl_id
    LEFT JOIN voirie                  v ON v.source_code = i.z_adresse_voirie_id
    LEFT JOIN pays                 padr ON padr.source_code = i.z_adresse_pays_id
    LEFT JOIN employeur            empl ON empl.source_code = i.z_employeur_id
)
SELECT
  unicaen_import.get_current_annee annee_id,
  srci.code,
  srci.utilisateur_code,
  CASE WHEN i.sync_structure = 0 THEN COALESCE(i.structure_id,srci.structure_id) ELSE srci.structure_id END structure_id,
  CASE WHEN i.sync_statut = 0 THEN COALESCE(i.statut_id,srci.statut_id) ELSE srci.statut_id END statut_id,
  srci.grade_id,
  srci.discipline_id,
  srci.civilite_id,
  srci.nom_usuel,
  srci.prenom,
  srci.date_naissance,
  srci.nom_patronymique,
  srci.commune_naissance,
  srci.pays_naissance_id,
  srci.departement_naissance_id,
  srci.pays_nationalite_id,
  srci.tel_pro,
  srci.tel_perso,
  srci.email_pro,
  srci.email_perso,
  srci.adresse_precisions,
  srci.adresse_numero,
  srci.adresse_numero_compl_id,
  srci.adresse_voirie_id,
  srci.adresse_voie,
  srci.adresse_lieu_dit,
  srci.adresse_code_postal,
  srci.adresse_commune,
  srci.adresse_pays_id,
  srci.numero_insee,
  srci.numero_insee_provisoire,
  srci.iban,
  srci.bic,
  srci.rib_hors_sepa,
  srci.autre_1,
  srci.autre_2,
  srci.autre_3,
  srci.autre_4,
  srci.autre_5,
  srci.employeur_id,
  srci.source_id,
  srci.source_code
FROM
  srci
  LEFT JOIN intervenant i ON i.source_code = srci.source_code AND i.annee_id = unicaen_import.get_current_annee AND i.histo_destruction IS NULL
WHERE
  filtre1 = 1

UNION ALL

SELECT
  unicaen_import.get_current_annee - 1       annee_id,
  srci.code,
  srci.utilisateur_code,
  COALESCE(i.structure_id,srci.structure_id) structure_id,
  COALESCE(i.statut_id,srci.statut_id)       statut_id,
  srci.grade_id,
  srci.discipline_id,
  srci.civilite_id,
  srci.nom_usuel,
  srci.prenom,
  srci.date_naissance,
  srci.nom_patronymique,
  srci.commune_naissance,
  srci.pays_naissance_id,
  srci.departement_naissance_id,
  srci.pays_nationalite_id,
  srci.tel_pro,
  srci.tel_perso,
  srci.email_pro,
  srci.email_perso,
  srci.adresse_precisions,
  srci.adresse_numero,
  srci.adresse_numero_compl_id,
  srci.adresse_voirie_id,
  srci.adresse_voie,
  srci.adresse_lieu_dit,
  srci.adresse_code_postal,
  srci.adresse_commune,
  srci.adresse_pays_id,
  srci.numero_insee,
  srci.numero_insee_provisoire,
  srci.iban,
  srci.bic,
  srci.rib_hors_sepa,
  srci.autre_1,
  srci.autre_2,
  srci.autre_3,
  srci.autre_4,
  srci.autre_5,
  srci.employeur_id,
  srci.source_id,
  srci.source_code
FROM
  srci
  LEFT JOIN intervenant i ON i.source_code = srci.source_code AND i.annee_id = unicaen_import.get_current_annee -1 AND i.histo_destruction IS NULL
WHERE
  filtre1 = 1