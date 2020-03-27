CREATE OR REPLACE FORCE VIEW V_TBL_CHARGENS_SEUILS_DEF AS
SELECT
  sta.annee_id,
  sta.scenario_id,
  s.structure_id,
  gtf.groupe_type_formation_id,
  sta.type_intervention_id,
  COALESCE(sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement) dedoublement
FROM
  (SELECT DISTINCT scenario_id, type_intervention_id, annee_id FROM seuil_charge WHERE histo_destruction IS NULL) sta
  JOIN (SELECT DISTINCT structure_id FROM noeud WHERE structure_id IS NOT NULL) s ON 1=1
  JOIN (SELECT id groupe_type_formation_id FROM groupe_type_formation) gtf ON 1=1

  LEFT JOIN seuil_charge sc1 ON
    sc1.histo_destruction            IS NULL
    AND sc1.annee_id                 = sta.annee_id
    AND sc1.scenario_id              = sta.scenario_id
    AND sc1.type_intervention_id     = sta.type_intervention_id
    AND sc1.structure_id             = s.structure_id
    AND sc1.groupe_type_formation_id = gtf.groupe_type_formation_id

  LEFT JOIN seuil_charge sc2 ON
    sc2.histo_destruction            IS NULL
    AND sc2.annee_id                 = sta.annee_id
    AND sc2.scenario_id              = sta.scenario_id
    AND sc2.type_intervention_id     = sta.type_intervention_id
    AND sc2.structure_id             = s.structure_id
    AND sc2.groupe_type_formation_id IS NULL

  LEFT JOIN seuil_charge sc3 ON
    sc3.histo_destruction            IS NULL
    AND sc3.annee_id                 = sta.annee_id
    AND sc3.scenario_id              = sta.scenario_id
    AND sc3.type_intervention_id     = sta.type_intervention_id
    AND sc3.structure_id             IS NULL
    AND sc3.groupe_type_formation_id = gtf.groupe_type_formation_id

  LEFT JOIN seuil_charge sc4 ON
    sc4.histo_destruction            IS NULL
    AND sc4.annee_id                 = sta.annee_id
    AND sc4.scenario_id              = sta.scenario_id
    AND sc4.type_intervention_id     = sta.type_intervention_id
    AND sc4.structure_id             IS NULL
    AND sc4.groupe_type_formation_id IS NULL
WHERE
  COALESCE(sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement, 1) <> 1