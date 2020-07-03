CREATE OR REPLACE FORCE VIEW V_TBL_PIECE_JOINTE AS
SELECT
    annee_id,
    type_piece_jointe_id,
    intervenant_id,
    demandee,
    fournie,
    validee,
    heures_pour_seuil,
    obligatoire,
    date_archive
FROM (
  SELECT
    COALESCE( pjd.annee_id, pjf.annee_id )                              annee_id,
    COALESCE( pjd.type_piece_jointe_id, pjf.type_piece_jointe_id )      type_piece_jointe_id,
    COALESCE( pjd.intervenant_id, pjf.intervenant_id )                  intervenant_id,
    CASE WHEN pjd.intervenant_id IS NULL THEN 0 ELSE 1 END              demandee,
    CASE WHEN pjf.fichier = pjf.count THEN 1 ELSE 0 END                 fournie,
    CASE WHEN pjf.validation = pjf.count THEN 1 ELSE 0 END              validee,
    COALESCE(pjd.heures_pour_seuil,0)                                   heures_pour_seuil,
    COALESCE(pjd.obligatoire,1)                                         obligatoire,
    pjf.date_archive                                                    date_archive,
    rank() over (partition by pjd.annee_id, pjd.code_intervenant, pjd.type_piece_jointe_id order by pjf.annee_id DESC)  rank1
  FROM
    tbl_piece_jointe_demande pjd
    FULL JOIN (
      SELECT
        pjf.annee_id,
        pjf.type_piece_jointe_id,
        pjf.intervenant_id,
        pjf.code_intervenant,
        pjf.date_validite,
        pjf.duree_vie,
        pjf.date_archive,
        COUNT(*) count,
        SUM(CASE WHEN validation_id IS NULL THEN 0 ELSE 1 END) validation,
        SUM(CASE WHEN fichier_id IS NULL THEN 0 ELSE 1 END) fichier
      FROM
        tbl_piece_jointe_fournie pjf
      WHERE
        1=1
      GROUP BY
        pjf.annee_id,
        pjf.intervenant_id,
        pjf.code_intervenant,
        pjf.type_piece_jointe_id,
        pjf.date_validite,
        pjf.duree_vie,
        pjf.date_archive
    ) pjf
    ON pjf.type_piece_jointe_id = pjd.type_piece_jointe_id
    AND pjd.code_intervenant = pjf.code_intervenant
    AND pjd.annee_id BETWEEN pjf.annee_id AND (pjf.annee_id + pjf.duree_vie - 1)
    AND pjd.annee_id BETWEEN pjf.annee_id AND NVL(pjf.date_archive - 1,(pjf.annee_id + pjf.duree_vie - 1))
  ) t
  WHERE
    t.rank1 = 1