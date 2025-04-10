CREATE OR REPLACE FORCE VIEW V_TBL_INTERVENANT_DOSSIER AS
SELECT
  i.annee_id 					annee_id,
  i.id 							intervenant_id,
  si.dossier 					actif,
  si.code						statut_code,
  si.dossier_identite_comp 		dossier_identite_comp,
  si.dossier_contact 			dossier_contact,
  si.dossier_adresse			dossier_adresse,
  si.dossier_banque 			dossier_banque,
  si.dossier_insee 				dossier_insee,
  si.dossier_employeur 			dossier_employeur,
  si.dossier_autre_1 			dossier_autre_1,
  si.dossier_autre_2 			dossier_autre_2,
  si.dossier_autre_3 			dossier_autre_3,
  si.dossier_autre_4 			dossier_autre_4,
  si.dossier_autre_5 			dossier_autre_5,
  si.dossier_email_perso		statut_dossier_email_perso,
  si.dossier_tel_perso			statut_dossier_tel_perso,
  d.id 							dossier_id,
  v.id 							validation_id,
  d.civilite_id					civilite_id,
  d.nom_usuel					nom_usuel,
  d.prenom						prenom,
  d.date_naissance				date_naissance,
  d.situation_matrimoniale_id	situation_matrimoniale_id,
  d.departement_naissance_id	departement_naissance_id,
  d.pays_naissance_id			pays_naissance_id,
  d.pays_nationalite_id			pays_nationalite_id,
  d.commune_naissance			commune_naissance,
  d.email_perso					email_perso,
  d.email_pro					email_pro,
  d.tel_perso					tel_perso,
  d.tel_pro						tel_pro,
  d.adresse_precisions			adresse_precisions,
  d.adresse_lieu_dit			adresse_lieu_dit,
  d.adresse_voie				adresse_voie,
  d.adresse_numero				adresse_numero,
  d.adresse_commune				adresse_commune,
  d.adresse_code_postal			adresse_code_postal,
  d.numero_insee				numero_insee,
  d.iban						iban,
  d.bic							bic,
  d.employeur_id				employeur_id,
  d.autre_1						autre_1,
  d.autre_2						autre_2,
  d.autre_3						autre_3,
  d.autre_4						autre_4,
  d.autre_5						autre_5
FROM
            intervenant         i
       JOIN statut             si ON si.id = i.statut_id
  LEFT JOIN intervenant_dossier d ON d.intervenant_id = i.id
                                 AND d.histo_destruction IS NULL
  LEFT JOIN pays               pn ON pn.id = d.pays_naissance_id
       JOIN type_validation tv ON tv.code = 'DONNEES_PERSO_PAR_COMP'
  LEFT JOIN validation       v ON v.intervenant_id = i.id
                              AND v.type_validation_id = tv.id
                              AND v.histo_destruction IS NULL
  LEFT JOIN dossier_champ_autre dca1 ON dca1.id = 1 AND si.dossier_autre_1 = 1
  LEFT JOIN dossier_champ_autre dca2 ON dca2.id = 2 AND si.dossier_autre_2 = 1
  LEFT JOIN dossier_champ_autre dca3 ON dca3.id = 3 AND si.dossier_autre_3 = 1
  LEFT JOIN dossier_champ_autre dca4 ON dca4.id = 4 AND si.dossier_autre_4 = 1
  LEFT JOIN dossier_champ_autre dca5 ON dca5.id = 5 AND si.dossier_autre_5 = 1
WHERE
  i.histo_destruction IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/