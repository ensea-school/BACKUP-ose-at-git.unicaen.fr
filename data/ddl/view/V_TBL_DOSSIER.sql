CREATE OR REPLACE FORCE VIEW V_TBL_DOSSIER AS
SELECT
  i.annee_id,
  i.id intervenant_id,
  si.peut_saisir_dossier,
  d.id dossier_id,
  v.id validation_id
FROM
            intervenant         i
       JOIN statut_intervenant si ON si.id = i.statut_id
  LEFT JOIN dossier             d ON d.intervenant_id = i.id
                              AND d.histo_destruction IS NULL

       JOIN type_validation tv ON tv.code = 'DONNEES_PERSO_PAR_COMP'
  LEFT JOIN validation       v ON v.intervenant_id = i.id
                              AND v.type_validation_id = tv.id
                              AND v.histo_destruction IS NULL
WHERE
  i.histo_destruction IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/