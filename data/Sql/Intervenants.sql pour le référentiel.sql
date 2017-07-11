SELECT
  'OSE'                                              C_SOURCE,
  i.code                                             C_SRC_INDIVIDU,
  s.source_code                                      C_SRC_STRUCTURE,
  4                                                  TYPE_ID,
  i.id || '_' || s.id                                ID_ORIG,
  a.date_debut                                       DATE_DEBUT,
  a.date_fin                                         DATE_FIN,
  CASE WHEN i.structure_id = s.id THEN 1 ELSE 0 END  T_PRINCIPALE
FROM
            tbl_service ts
       JOIN intervenant          i ON i.id = ts.intervenant_id
       JOIN annee                a ON a.id = ts.annee_id
       JOIN structure            s ON s.id = ts.structure_id
WHERE
  ts.type_volume_horaire_code = 'PREVU'
  AND ts.valide > 0
  AND SYSDATE BETWEEN a.date_debut AND a.date_fin -- pour ne prendre que les intervenants actuels
GROUP BY
  i.id, i.code, i.structure_id,
  s.id, s.source_code,
  a.date_debut, a.date_fin
;