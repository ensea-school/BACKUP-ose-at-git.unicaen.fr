CREATE OR REPLACE FORCE VIEW V_INDIC_ATTENTE_DEMANDE_MEP AS
select to_number(i.id||dmep.structure_id) id, i.id intervenant_id, i.source_code, ti.code, i.annee_id, dmep.structure_id, 0 TOTAL_HEURES_MEP, 0 TOTAL_HEURES_COMPL
  from intervenant i
  join statut si on si.id = i.statut_id
  join type_intervenant ti on ti.id = si.type_intervenant_id
  -- l'intervenant doit avoir des heures disponibles pour une demande de MEP
  join V_HAS_DMEP_A_FAIRE dmep on dmep.intervenant_id = i.id and dmep.has_dmep_a_faire <> 0
  where (
    -- un vacataire n'a pas d'autre contrainte
    ti.code = 'E'
    or
    -- mais un permanent doit...
    (
      -- avoir sa saisie de service réalisé clôturée
      exists (
        select * from validation v
        join type_validation tv on v.type_validation_id = tv.id and tv.code = 'CLOTURE_REALISE'
        where v.intervenant_id = i.id and v.histo_destruction IS NULL
      )
      -- et tous ses enseignements réalisés validés (toutes composantes d'intervention confondues)
      and not exists (
        select * from volume_horaire vh
        join service s on vh.service_id = s.id and s.histo_destruction IS NULL
        join type_volume_horaire tvh on vh.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
        left join validation_vol_horaire vvh on vvh.volume_horaire_id = vh.id
        left join validation v on vvh.validation_id = v.id and v.histo_destruction IS NULL
        left join type_validation tv on v.type_validation_id = tv.id and tv.code = 'SERVICES_PAR_COMP'
        where s.intervenant_id = i.id and v.id is null and vh.histo_destruction IS NULL
      )
      -- et tout son référentiel réalisé validé (toutes composantes d'intervention confondues)
      and not exists (
        select * from volume_horaire_ref vh
        join service_referentiel s on vh.service_referentiel_id = s.id and s.histo_destruction IS NULL
        join type_volume_horaire tvh on vh.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
        left join validation_vol_horaire_ref vvh on vvh.volume_horaire_ref_id = vh.id
        left join validation v on vvh.validation_id = v.id and v.histo_destruction IS NULL
        left join type_validation tv on v.type_validation_id = tv.id and tv.code = 'SERVICES_PAR_COMP'
        where s.intervenant_id = i.id and v.id is null and vh.histo_destruction IS NULL
      )
    )
  )