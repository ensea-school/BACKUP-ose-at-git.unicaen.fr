CREATE OR REPLACE FORCE VIEW V_TBL_DOSSIER AS
SELECT
  i.annee_id,
  i.id intervenant_id,
  si.dossier actif,
  d.id dossier_id,
  v.id validation_id,
  /*Complétude statut*/
  CASE WHEN si.code = 'AUTRES' THEN 0
  ELSE 1 END completude_statut,
  /*Complétude identité*/
  CASE WHEN
    (
      d.civilite_id IS NOT NULL
      AND d.nom_usuel IS NOT NULL
      AND d.prenom IS NOT NULL
      AND (CASE WHEN si.dossier_situation_matrimoniale = 1 THEN
	            CASE WHEN d.situation_matrimoniale_id = (SELECT id FROM situation_matrimoniale WHERE code = 'CEL') THEN 1
      				 WHEN d.situation_matrimoniale_id IS NOT NULL AND d.situation_matrimoniale_date IS NOT NULL THEN 1 ELSE 0 END
      	   ELSE 1 END) = 1
    ) THEN 1 ELSE 0 END completude_identite,
   /*Complétude identité complémentaire*/
  CASE WHEN si.dossier_identite_comp = 0 THEN 1
  ELSE
        CASE WHEN
        (
           d.date_naissance IS NOT NULL
       AND NOT (OSE_DIVERS.str_reduce(pn.LIBELLE) = 'france' AND d.departement_naissance_id IS NULL)
           AND d.pays_naissance_id IS NOT NULL
           AND d.pays_nationalite_id IS NOT NULL
           AND d.commune_naissance IS NOT NULL
        ) THEN 1 ELSE 0 END
   END completude_identite_comp,
   /*Complétude contact*/
   CASE WHEN si.dossier_contact = 0 THEN 1
   ELSE
   (
        CASE WHEN
        (
          (CASE WHEN si.dossier_email_perso = 1 THEN
             CASE WHEN d.email_perso IS NOT NULL THEN 1 ELSE 0 END
           ELSE
             CASE WHEN d.email_pro IS NOT NULL OR d.email_perso IS NOT NULL THEN 1 ELSE 0 END
           END) = 1
           AND
          (CASE WHEN si.dossier_tel_perso = 1 THEN
             CASE WHEN d.tel_perso IS NOT NULL AND d.tel_pro IS NOT NULL THEN 1 ELSE 0 END
           ELSE
             CASE WHEN d.tel_pro IS NOT NULL OR d.tel_perso IS NOT NULL THEN 1 ELSE 0 END
           END) = 1
        ) THEN 1 ELSE 0 END
   ) END completude_contact,
   /*Complétude adresse*/
   CASE WHEN si.dossier_adresse = 0 THEN 1
   ELSE
   (
      CASE WHEN
      (
         d.adresse_precisions IS NOT NULL
         OR d.adresse_lieu_dit IS NOT NULL
         OR (d.adresse_voie IS NOT NULL AND d.adresse_numero IS NOT NULL)
      ) AND
      (
       d.adresse_commune IS NOT NULL
         AND d.adresse_code_postal IS NOT NULL
      ) THEN 1 ELSE 0 END
    ) END completude_adresse,
     /*Complétude INSEE*/
     CASE WHEN si.dossier_insee = 0 THEN 1
     ELSE
     (
       CASE
           WHEN d.numero_insee IS NOT NULL THEN 1
           ELSE 0 END
     ) END completude_insee,
     /*Complétude IBAN*/
     CASE WHEN si.dossier_banque = 0 THEN 1
     ELSE
     (
       CASE WHEN d.iban IS NOT NULL AND d.bic IS NOT NULL THEN 1 ELSE 0 END
     ) END completude_banque,
     /*Complétude employeur*/
     CASE WHEN si.dossier_employeur = 0 OR si.dossier_employeur_facultatif = 1 THEN 1
     ELSE
     (
       CASE WHEN
       (
         d.employeur_id IS NOT NULL
       ) THEN 1 ELSE 0 END
     ) END completude_employeur,
     /*Complétude champs autres*/
     CASE WHEN
     (
       NOT (d.autre_1 IS NULL AND COALESCE(dca1.obligatoire,0) = 1)
       AND NOT (d.autre_2 IS NULL AND COALESCE(dca2.obligatoire,0) = 1)
       AND NOT (d.autre_3 IS NULL AND COALESCE(dca3.obligatoire,0) = 1)
       AND NOT (d.autre_4 IS NULL AND COALESCE(dca4.obligatoire,0) = 1)
       AND NOT (d.autre_5 IS NULL AND COALESCE(dca5.obligatoire,0) = 1)
     ) THEN 1 ELSE 0 END completude_autres

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
  /*@intervenant_id=i.id*/
  /*@annee_id=i.annee_id*/