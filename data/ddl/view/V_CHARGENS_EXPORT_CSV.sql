CREATE OR REPLACE FORCE VIEW V_CHARGENS_EXPORT_CSV AS
SELECT
  a.id              annee_id,
  cph.scenario_id   scenario_id,
  sp.id             structure_porteuse_id,
  sp.ids            structure_porteuse_ids,
  si.id             structure_ins_id,
  si.ids            structure_ins_ids,

  a.libelle         annee,

  sp.source_code    structure_porteuse_code,
  sp.libelle_court  structure_porteuse_libelle,
  eport.code        etape_porteuse_code,
  eport.libelle     etape_porteuse_libelle,

  si.source_code    structure_ins_code,
  si.libelle_court  structure_ins_libelle,
  eins.code         etape_ins_code,
  eins.libelle      etape_ins_libelle,

  ep.code           element_code,
  ep.libelle        element_libelle,
  CASE
    WHEN COALESCE(ch.nbch,0) > 1 THEN 'Oui'
    ELSE 'Non'
  END               element_mutualise,
  p.libelle_court   periode,
  d.source_code     discipline_code,
  d.libelle_court   discipline_libelle,
  th.libelle_court  type_heures,
  ti.code           type_intervention,

  cph.ouverture     seuil_ouverture,
  cph.dedoublement  seuil_dedoublement,
  cph.assiduite     assiduite,
  sne.effectif      effectif_etape,
  cph.effectif      effectif_element,
  cph.heures_ens    heures_ens,
  cph.groupes       groupes,
  cph.heures        heures,
  cph.hetd          hetd
  FROM
            tbl_chargens                cph
       JOIN annee                         a ON a.id = cph.annee_id
       JOIN structure                    sp ON sp.id = cph.structure_id
       JOIN etape                     eport ON eport.id = cph.etape_id
       JOIN etape                      eins ON eins.id = cph.etape_ens_id
       JOIN structure                    si ON si.id = eins.structure_id
       JOIN element_pedagogique          ep ON ep.id = cph.element_pedagogique_id
       JOIN type_heures                  th ON th.id = cph.type_heures_id
       JOIN type_intervention            ti ON ti.id = cph.type_intervention_id
  LEFT JOIN periode                       p ON p.id = ep.periode_id
  LEFT JOIN discipline                    d ON d.id = ep.discipline_id
  LEFT JOIN noeud                         n ON n.etape_id = eins.id
                                           AND n.histo_destruction IS NULL

  LEFT JOIN scenario_noeud               sn ON sn.noeud_id = n.id
                                           AND sn.histo_destruction IS NULL
                                           AND sn.scenario_id = cph.scenario_id

  LEFT JOIN scenario_noeud_effectif     sne ON sne.scenario_noeud_id = sn.id
                                           AND sne.type_heures_id = cph.type_heures_id
                                           AND sne.etape_id = n.etape_id
  LEFT JOIN (
    SELECT element_pedagogique_id, count(*) nbch
    FROM chemin_pedagogique
    WHERE histo_destruction IS NULL
    GROUP BY element_pedagogique_id
  )                                     ch ON ch.element_pedagogique_id = ep.id
ORDER BY
  structure_porteuse_code,
  etape_porteuse_code,
  structure_ins_code,
  etape_ins_code,
  element_code,
  type_heures,
  type_intervention