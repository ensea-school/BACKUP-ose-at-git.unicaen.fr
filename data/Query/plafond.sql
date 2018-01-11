SELECT
  p.id plafond_id,
  p.libelle plafond_libelle,
  b.type_volume_horaire_id,
  tvh.libelle type_volume_horaire_libelle,
  b.annee_id,
  b.intervenant_id,
  b.plafond,
  b.heures,
  pe.code plafond_etat_code
FROM
(
-- Montant maximal par intervenant de la prime D714-60 du code de l'éducation
SELECT
  'remu-d714-60'                      plafond_code,
  fr.type_volume_horaire_id           type_volume_horaire_id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 ) plafond,
  fr.heures_compl_fc_majorees  heures
FROM
       intervenant                i
  JOIN annee                      a ON a.id = i.annee_id
  JOIN statut_intervenant        si ON si.id = i.statut_id
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
WHERE
  fr.heures_compl_fc_majorees > ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 )
  /*i.id*/
UNION

-- Heures max. de référentiel par intervenant selon son statut
SELECT
  'ref-par-statut'                    plafond_code,
  fr.type_volume_horaire_id           type_volume_horaire_id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  si.plafond_referentiel              plafond,
  fr.SERVICE_REFERENTIEL + fr.HEURES_COMPL_REFERENTIEL heures
FROM
  intervenant                     i
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN statut_intervenant        si ON si.id = i.statut_id
WHERE
  fr.SERVICE_REFERENTIEL + fr.HEURES_COMPL_REFERENTIEL > si.plafond_referentiel
  /*i.id*/
UNION

-- Heures max. de référentiel par intervenant et par fonction référentielle
SELECT
  'ref-par-fonction' plafond_code,
  t.type_volume_horaire_id,
  i.annee_id,
  t.intervenant_id,
  AVG(t.plafond)  plafond,
  AVG(t.heures)   heures
FROM
  (
  SELECT
    vhr.type_volume_horaire_id        type_volume_horaire_id,
    sr.intervenant_id                 intervenant_id,
    fr.plafond                        plafond,
    fr.id                             fr_id,
    SUM(vhr.heures)                   heures
  FROM
         service_referentiel       sr
    JOIN fonction_referentiel      fr ON fr.id = sr.fonction_id
    JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
  WHERE
    sr.histo_destruction IS NULL
  GROUP BY
    vhr.type_volume_horaire_id,
    sr.intervenant_id,
    fr.plafond,
    fr.id
  ) t
  JOIN intervenant i ON i.id = t.intervenant_id
WHERE
  t.heures > t.plafond
  /*i.id*/
GROUP BY
  t.type_volume_horaire_id,
  i.annee_id,
  t.intervenant_id

UNION

-- Nombre maximum d'heures équivalent TD par intervenant selon son statut
SELECT
  'hetd'                              plafond_code,
  fr.type_volume_horaire_id           type_volume_horaire_id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  si.maximum_hetd                     plafond,
  fr.total                            heures
FROM
  intervenant                     i
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN statut_intervenant        si ON si.id = i.statut_id
WHERE
  fr.total > si.maximum_hetd
  /*i.id*/

UNION

-- Nombre d'heures complémentaires maximum (hors rémunération au titre de l'article d714-60 du code de l'éducation)
SELECT
  'hc-hors-d71460'                    plafond_code,
  fr.type_volume_horaire_id           type_volume_horaire_id,
  i.annee_id                          annee_id,
  fr.intervenant_id                   intervenant_id,
  si.plafond_hc_hors_remu_fc          plafond,
  fr.heures_compl_fi + fr.heures_compl_fc + fr.heures_compl_fa + fr.heures_compl_referentiel heures
FROM
       intervenant                i
  JOIN statut_intervenant        si ON si.id = i.statut_id
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id
WHERE
  (fr.heures_compl_fi + fr.heures_compl_fc + fr.heures_compl_fa + fr.heures_compl_referentiel) > si.plafond_hc_hors_remu_fc
  /*i.id*/

) b

  JOIN plafond               p ON p.code = b.plafond_code
  JOIN plafond_application  pa ON pa.plafond_id = p.id AND pa.type_volume_horaire_id = b.type_volume_horaire_id AND b.annee_id BETWEEN COALESCE(pa.annee_debut_id,b.annee_id) AND COALESCE(pa.annee_fin_id,b.annee_id)
  JOIN plafond_etat         pe ON pe.id = pa.plafond_etat_id
  JOIN type_volume_horaire tvh ON tvh.id = b.type_volume_horaire_id
WHERE
  pe.code IN ('bloquant','informatif')
