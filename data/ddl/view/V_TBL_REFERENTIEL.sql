CREATE OR REPLACE FORCE VIEW V_TBL_REFERENTIEL AS
SELECT
  t.annee_id,
  t.intervenant_id,
  t.type_volume_horaire_id,
  t.actif,
  t.structure_id,
  t.intervenant_structure_id,
  t.service_referentiel_id,
  t.fonction_referentiel_id,
  t.type_intervenant_id,
  t.type_intervenant_code,
  t.type_volume_horaire_code,
  SUM(nbvh)   nbvh,
  SUM(heures) heures,
  SUM(valide) valide
FROM
  (
  SELECT
    i.annee_id       annee_id,
    i.id             intervenant_id,
    tvh.id           type_volume_horaire_id,
    CASE tvh.code
      WHEN 'PREVU' THEN si.referentiel_prevu
      WHEN 'REALISE' THEN si.referentiel_realise
      ELSE 0
    END              actif,
    s.structure_id   structure_id,
    i.structure_id   intervenant_structure_id,
    s.id             service_referentiel_id,
    s.fonction_id    fonction_referentiel_id,
    ti.id            type_intervenant_id,
    tvh.code         type_volume_horaire_code,
    ti.code          type_intervenant_code,
    vh.heures        heures,
    1                nbvh,
    CASE WHEN v.id IS NULL AND vh.auto_validation=0 THEN 0 ELSE 1 END valide
  FROM
              volume_horaire_ref               vh
         JOIN service_referentiel               s ON s.id = vh.service_referentiel_id
                                                 AND s.histo_destruction IS NULL

         JOIN intervenant                       i ON i.id = s.intervenant_id
                                                 AND i.histo_destruction IS NULL

         JOIN statut                           si ON si.id = i.statut_id

         JOIN type_intervenant                 ti ON ti.id = si.type_intervenant_id

         JOIN type_volume_horaire             tvh ON tvh.id = vh.type_volume_horaire_id

    LEFT JOIN validation_vol_horaire_ref      vvh ON vvh.volume_horaire_ref_id = vh.id

    LEFT JOIN validation                        v ON v.id = vvh.validation_id
                                                 AND v.histo_destruction IS NULL
  WHERE
    vh.histo_destruction IS NULL
    /*@intervenant_id=i.id*/
    /*@annee_id=i.annee_id*/
    /*@structure_id=COALESCE(s.structure_id,i.structure_id)*/
  ) t
GROUP BY
  t.annee_id,
  t.intervenant_id,
  t.type_volume_horaire_id,
  t.actif,
  t.structure_id,
  t.intervenant_structure_id,
  t.service_referentiel_id,
  t.fonction_referentiel_id,
  t.type_intervenant_id,
  t.type_intervenant_code,
  t.type_volume_horaire_code