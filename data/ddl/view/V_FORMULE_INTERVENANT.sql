CREATE OR REPLACE FORCE VIEW V_FORMULE_INTERVENANT AS
SELECT
  i.id                                                                 intervenant_id,
  i.annee_id                                                           annee_id,
  ti.id                                                                type_intervenant_id,
  CASE WHEN ti.code = 'P' THEN s.code ELSE NULL END                    structure_code,
  si.service_statutaire                                                heures_service_statutaire,
  COALESCE( SUM( msd.heures * mms.multiplicateur ), 0 )                heures_service_modifie,
  CASE WHEN
    si.depassement_service_du_sans_hc = 1
    OR COALESCE( SUM( msd.heures * mms.multiplicateur * mms.decharge ), 0 ) <> 0
  THEN 1 ELSE 0 END                                                    depassement_service_du_sans_hc
FROM
            intervenant                  i
  LEFT JOIN structure                    s ON s.id = i.structure_id
  LEFT JOIN modification_service_du    msd ON msd.intervenant_id = i.id AND msd.histo_destruction IS NULL
  LEFT JOIN motif_modification_service mms ON mms.id = msd.motif_id
       JOIN statut                      si ON si.id = i.statut_id
       JOIN type_intervenant            ti ON ti.id = si.type_intervenant_id
WHERE
  1=1
  /*@INTERVENANT_ID=i.id*/
  /*@STATUT_ID=si.id*/
  /*@TYPE_INTERVENANT_ID=ti.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@MOTIF_MODIFICATION_SERVICE_ID=mms.id*/
GROUP BY
  i.id, i.annee_id, i.structure_id, ti.id, ti.code, s.code, si.service_statutaire, si.depassement_service_du_sans_hc