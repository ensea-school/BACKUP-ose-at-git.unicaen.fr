SELECT
  null id,
  ti.id type_intervention_id,
  ep.id element_pedagogique_id,
  1 visible,
  src.id source_id,
  ti.code || '_' || ep.source_code || '_' || ep.annee_id source_code
FROM
  element_pedagogique ep
  JOIN type_intervention ti ON ti.code = 'FOAD' AND 1 = ose_divers.comprise_entre( ti.histo_creation, ti.histo_destruction )
  JOIN structure s ON s.id = ep.structure_id
  JOIN source src ON src.code = 'Calcul'
WHERE
  ep.taux_foad > 0
  AND 1 = ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction )
  AND s.source_code IN ('U07','U08','U04','12')
  
UNION

SELECT
  null id,
  ti.id type_intervention_id,
  ep.id element_pedagogique_id,
  1 visible,
  src.id source_id,
  ti.code || '_' || ep.source_code || '_' || ep.annee_id source_code
FROM
  element_pedagogique ep
  JOIN type_intervention ti ON ti.code IN ('FOAD-ECR', 'FOAD-ACTU', 'FOAD-EXPL') AND 1 = ose_divers.comprise_entre( ti.histo_creation, ti.histo_destruction )
  JOIN structure s ON s.id = ep.structure_id
  JOIN source src ON src.code = 'Calcul'
WHERE
  ep.taux_foad > 0
  AND 1 = ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction )
  AND s.source_code IN ('U10') /* IAE uniquement */
  
UNION

SELECT
  null id,
  ti.id type_intervention_id,
  ep.id element_pedagogique_id,
  1 visible,
  src.id source_id,
  ti.code || '_' || ep.source_code || '_' || ep.annee_id source_code
FROM
  element_pedagogique ep
  JOIN type_intervention ti ON ti.code IN ('FOAD-ECR', 'FOAD-ACTU', 'FOAD-EXPL') AND 1 = ose_divers.comprise_entre( ti.histo_creation, ti.histo_destruction )
  JOIN structure s ON s.id = ep.structure_id
  JOIN source src ON src.code = 'Calcul'
WHERE
  ep.taux_foad > 0
  AND ep.annee_id >= 2015 -- à partir de 2015-2016 uniquement
  AND 1 = ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction )
  AND s.source_code IN ('C53') /* CEMU */
;


CREATE OR REPLACE VIEW SRC_TYPE_INTERVENTION_EP AS
SELECT
  null                                                   id,
  ti.id                                                  type_intervention_id,
  ep.id                                                  element_pedagogique_id,
  src.id                                                 source_id,
  ti.code || '_' || ep.source_code || '_' || ep.annee_id source_code
FROM
            element_pedagogique              ep

       JOIN type_intervention                ti ON ep.annee_id BETWEEN COALESCE(ti.annee_debut_id,0) AND COALESCE(ti.annee_fin_id, 999999)
                                               AND 1 = ose_divers.comprise_entre( ti.histo_creation, ti.histo_destruction )

       JOIN source                          src ON src.code = 'Calcul'

  LEFT JOIN type_intervention_regle_linker tirl ON tirl.type_intervention_id = ti.id

  LEFT JOIN v_type_intervention_regle_ep   tire ON tire.element_pedagogique_id = ep.id
                                               AND tire.type_intervention_regle_id = tirl.type_intervention_regle_id

  LEFT JOIN type_intervention_structure     tis ON tis.type_intervention_id = ti.id
                                               AND tis.structure_id = ep.structure_id
                                               AND ep.annee_id BETWEEN COALESCE(tis.annee_debut_id,0) AND COALESCE(tis.annee_fin_id, 999999)
                                               AND 1 = ose_divers.comprise_entre( tis.histo_creation, tis.histo_destruction )

WHERE
  1 = ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction )
  AND COALESCE( tis.visible, ti.visible ) = 1
  AND (tirl.type_intervention_id IS NULL OR tire.type_intervention_regle_id IS NOT NULL)
;



SELECT 
  type_intervention_id,
  element_pedagogique_id
FROM
  type_intervention_ep tie
WHERE
  1 = OSE_DIVERS.COMPRISE_ENTRE( tie.histo_creation, tie.histo_destruction )
;
