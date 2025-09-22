CREATE OR REPLACE FORCE VIEW V_TBL_PIECE_JOINTE_FOURNIE AS
SELECT
   i.annee_id,
   i.code code_intervenant,
   pj.type_piece_jointe_id,
   pj.intervenant_id,
   pj.id piece_jointe_id,
   v.id validation_id,
   MAX(f.id) fichier_id,
   MIN(COALESCE(tpjs.duree_vie,999)) duree_vie,
   MIN(i.annee_id+COALESCE(tpjs.duree_vie,999)) date_validitee,
   pj.date_archive date_archive,
   pjs.code        type_pj_code,
   COALESCE(max(tpjs.demandee_apres_recrutement),0)  demandee_apres_recrutement,
   COALESCE(max(tpjs.seuil_hetd),0)  seuil_hetd,
   COALESCE(max(tpjs.obligatoire),0)  obligatoire
 FROM
             piece_jointe              pj
        JOIN intervenant                i ON i.id = pj.intervenant_id
                                         AND i.histo_destruction IS NULL
        JOIN piece_jointe_fichier     pjf ON pjf.piece_jointe_id = pj.id
        JOIN fichier                    f ON f.id = pjf.fichier_id
                                         AND f.histo_destruction IS NULL
  LEFT JOIN type_piece_jointe_statut tpjs ON tpjs.statut_id = i.statut_id
                                         AND tpjs.type_piece_jointe_id = pj.type_piece_jointe_id
                                         AND i.annee_id = tpjs.annee_id
                                         AND tpjs.histo_destruction IS NULL
  LEFT JOIN type_piece_jointe        pjs ON pjs.id = pj.type_piece_jointe_id
  LEFT JOIN validation                  v ON v.id = pj.validation_id
                                         AND v.histo_destruction IS NULL
 WHERE
   pj.histo_destruction IS NULL
 GROUP BY
   i.annee_id,
   i.code,
   pj.type_piece_jointe_id,
   pj.intervenant_id,
   pj.id,
   pj.date_archive,
   pjs.code,
   v.id