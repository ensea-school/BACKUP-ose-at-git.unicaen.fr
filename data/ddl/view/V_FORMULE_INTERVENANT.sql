CREATE OR REPLACE FORCE VIEW V_FORMULE_INTERVENANT AS
SELECT
  i.id                                              intervenant_id,
  i.annee_id                                        annee_id,
  ti.id                                             type_intervenant_id,
  CASE WHEN ti.code = 'P' THEN s.code ELSE NULL END structure_code,
  tsd.service_statutaire                            heures_service_statutaire,
  tsd.service_modifie                               heures_service_modifie,
  tsd.depassement_service_du_sans_hc                depassement_service_du_sans_hc,
  i.formule_calcul_arrondisseur                     arrondisseur
FROM
            intervenant       i
       JOIN statut           si ON si.id = i.statut_id
       JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
       JOIN tbl_service_du  tsd ON tsd.intervenant_id = i.id
  LEFT JOIN structure         s ON s.id = i.structure_id
WHERE
  1=1
  /*@INTERVENANT_ID=i.id*/
  /*@STATUT_ID=si.id*/
  /*@TYPE_INTERVENANT_ID=ti.id*/
  /*@ANNEE_ID=i.annee_id*/