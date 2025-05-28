CREATE OR REPLACE FORCE VIEW V_TBL_SERVICE_DU AS
SELECT
  CASE WHEN
    si.service_prevu + si.service_realise + si.referentiel_prevu + si.referentiel_realise > 0
  THEN 1 ELSE 0 END                                     actif,
  i.annee_id                                            annee_id,
  si.id                                                 statut_id,
  i.id                                                  intervenant_id,
  si.service_statutaire                                 service_statutaire,
  COALESCE( SUM( msd.heures * mms.multiplicateur ), 0 ) service_modifie,
  si.service_statutaire + COALESCE( SUM( msd.heures * mms.multiplicateur ), 0 ) service_du,
  CASE WHEN
    si.depassement_service_du_sans_hc = 1
    OR COALESCE( SUM( msd.heures * mms.multiplicateur * mms.decharge ), 0 ) <> 0
  THEN 1 ELSE 0 END                                     depassement_service_du_sans_hc
FROM
            intervenant                  i
       JOIN statut                      si ON si.id = i.statut_id
--       JOIN type_intervenant            ti ON ti.id = si.type_intervenant_id
  LEFT JOIN structure                    s ON s.id = i.structure_id
  LEFT JOIN modification_service_du    msd ON msd.intervenant_id = i.id AND msd.histo_destruction IS NULL
  LEFT JOIN motif_modification_service mms ON mms.id = msd.motif_id
WHERE
  1=1
  /*@INTERVENANT_ID=i.id*/
  /*@STATUT_ID=si.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@MOTIF_MODIFICATION_SERVICE_ID=mms.id*/
GROUP BY
  i.id, i.annee_id, i.structure_id, si.service_statutaire, si.depassement_service_du_sans_hc, si.service_prevu, si.service_realise, si.referentiel_prevu, si.referentiel_realise, si.id