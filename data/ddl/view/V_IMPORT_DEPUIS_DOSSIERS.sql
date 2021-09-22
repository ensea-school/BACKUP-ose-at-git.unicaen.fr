CREATE OR REPLACE FORCE VIEW V_IMPORT_DEPUIS_DOSSIERS AS
SELECT
  i.annee_id,
  i.code intervenant_code,
  MAX(cv.libelle_court)      z_civilite_id,
  MAX(d.nom_usuel)           nom_usuel,
  MAX(d.prenom)              prenom,
  MAX(d.date_naissance)      date_naissance,
  MAX(d.nom_patronymique)    nom_patronymique,
  MAX(d.commune_naissance)   commune_naissance,
  MAX(pn.source_code)        z_pays_naissance_id,
  MAX(dep.source_code)       z_departement_naissance_id,
  MAX(pnat.source_code)      z_pays_nationalite_id,
  MAX(d.tel_pro)             tel_pro,
  MAX(d.tel_perso)           tel_perso,
  MAX(d.email_pro)           email_pro,
  MAX(d.email_perso)         email_perso,
  MAX(d.adresse_precisions)  adresse_precisions,
  MAX(d.adresse_numero)      adresse_numero,
  MAX(anc.code)              z_adresse_numero_compl_id,
  MAX(av.source_code)        z_adresse_voirie_id,
  MAX(d.adresse_voie)        adresse_voie,
  MAX(d.adresse_lieu_dit)    adresse_voie_lieu_dit,
  MAX(d.adresse_code_postal) adresse_code_postal,
  MAX(d.adresse_commune)     adresse_commune,
  MAX(padr.source_code)      z_adresse_pays_id,
  MAX(d.numero_insee) numero_insee,
  MAX(d.numero_insee_provisoire) numero_insee_provisoire,
  MAX(d.iban) iban,
  MAX(d.bic) bic,
  MAX(d.rib_hors_sepa) rib_hors_sepa,
  MAX(d.autre_1) autre_1,
  MAX(d.autre_2) autre_2,
  MAX(d.autre_3) autre_3,
  MAX(d.autre_4) autre_4,
  MAX(d.autre_5) autre_5,
  MAX(empl.source_code) z_employeur_id
FROM
            intervenant             i
       JOIN statut_intervenant     si ON si.id = i.statut_id
       JOIN intervenant_dossier     d ON d.intervenant_id = i.id
                                     AND d.histo_destruction IS NULL

       JOIN type_validation        tv ON tv.code = 'DONNEES_PERSO_PAR_COMP'
       JOIN validation              v ON v.intervenant_id = i.id
                                     AND v.type_validation_id = tv.id
                                     AND v.histo_destruction IS NULL
       JOIN civilite               cv ON cv.id = d.civilite_id
  LEFT JOIN pays                   pn ON pn.id = d.pays_naissance_id
  LEFT JOIN departement           dep ON dep.id = d.departement_naissance_id
  LEFT JOIN pays                 pnat ON pnat.id = d.pays_nationalite_id
  LEFT JOIN adresse_numero_compl  anc ON anc.id = d.adresse_numero_compl_id
  LEFT JOIN voirie                 av ON av.id = d.adresse_voirie_id
  LEFT JOIN pays                 padr ON padr.id   = d.adresse_pays_id
  LEFT JOIN employeur            empl ON empl.id   = d.employeur_id
WHERE
  i.histo_destruction IS NULL
  AND si.code NOT IN ('AUTRES')
  AND si.peut_saisir_dossier = 1
GROUP BY
  i.annee_id, i.code