CREATE OR REPLACE FORCE VIEW V_CHARGENS_CALC_EFFECTIF AS
SELECT
  gl.noeud_inf_id             noeud_id,
  snsup.scenario_id           scenario_id,
  sninf.id                    scenario_noeud_id,
  sne.type_heures_id          type_heures_id,
  sne.etape_id                etape_id,
--  sne.effectif                effectif,
--  slsup.choix_minimum         choix_minimum,
--  slsup.choix_maximum         choix_maximum,
--  COALESCE(slinf.poids,1)     poids,
--  COALESCE(sninf.assiduite,1) assiduite,
--  MIN(COALESCE(sl.poids,1))   min_poids,
--  MAX(COALESCE(sl.poids,1))   max_poids,
--  SUM(COALESCE(sl.poids,1))   total_poids,
--  COUNT(*)                    nb_choix,
  OSE_CHARGENS.CALC_COEF(
    slsup.choix_minimum,
    slsup.choix_maximum,
    COALESCE(slinf.poids,1),
    MAX(COALESCE(sl.poids,1)),
    SUM(COALESCE(sl.poids,1)),
    COUNT(*)
  ) * sne.effectif effectif
FROM
            v_chargens_grands_liens  gl
       JOIN scenario_noeud        snsup ON snsup.noeud_id = gl.noeud_sup_id
                                       AND snsup.histo_destruction IS NULL

       JOIN scenario_noeud_effectif sne ON sne.scenario_noeud_id = snsup.id

  LEFT JOIN scenario_lien         slsup ON slsup.histo_destruction IS NULL
                                       AND slsup.lien_id = gl.lien_sup_id
                                       AND slsup.scenario_id = snsup.scenario_id

  LEFT JOIN scenario_lien         slinf ON slinf.histo_destruction IS NULL
                                       AND slinf.lien_id = gl.lien_inf_id
                                       AND slinf.scenario_id = snsup.scenario_id

  LEFT JOIN scenario_noeud        sninf ON sninf.noeud_id = gl.noeud_inf_id
                                       AND sninf.scenario_id = snsup.scenario_id
                                       AND sninf.histo_destruction IS NULL

       JOIN lien                      l ON l.noeud_sup_id = gl.noeud_liste_id
                                       AND l.histo_destruction IS NULL

  LEFT JOIN scenario_lien            sl ON sl.lien_id = l.id
                                       AND sl.scenario_id = snsup.scenario_id
                                       AND sl.histo_destruction IS NULL

WHERE
  (slsup.actif = 1 OR slsup.actif IS NULL)
  AND (slinf.actif = 1 OR slinf.actif IS NULL)
  AND (sl.actif = 1 OR sl.actif IS NULL)
  AND (snsup.scenario_id = 0 OR NULL IS NULL)
  AND (sne.type_heures_id = 0 OR NULL IS NULL)
  AND (sne.etape_id = 0 OR NULL IS NULL)
GROUP BY
  gl.noeud_sup_id,
  gl.noeud_inf_id,
  snsup.scenario_id,
  sninf.id,
  sne.type_heures_id,
  sne.etape_id,
  sne.effectif,
  slsup.choix_minimum,
  slsup.choix_maximum,
  slinf.poids