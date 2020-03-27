CREATE OR REPLACE FORCE VIEW V_INDIC_ATTENTE_MEP AS
with
  -- total des heures comp ayant fait l'objet d'une *demande* de mise en paiement
  mep as (
    select intervenant_id, structure_id, sum(nvl(mep_heures, 0)) total_heures_mep
    from (
      -- enseignements
      select
        fr.intervenant_id,
        nvl(ep.structure_id, i.structure_id) structure_id,
        nvl(mep.heures, 0) mep_heures
      from mise_en_paiement mep
      join formule_resultat_service frs on mep.formule_res_service_id = frs.id
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service s on frs.service_id = s.id
      left join element_pedagogique ep on s.element_pedagogique_id = ep.id
      where mep.histo_destruction IS NULL and mep.date_mise_en_paiement is null -- si date_mise_en_paiement = null, c'est une demande
      union all
      -- referentiel
      select
        fr.intervenant_id,
        s.structure_id,
        nvl(mep.heures, 0) mep_heures
      from mise_en_paiement mep
      join formule_resultat_service_ref frs on mep.formule_res_service_ref_id = frs.id
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service_referentiel s on frs.service_referentiel_id = s.id
      where mep.histo_destruction IS NULL and mep.date_mise_en_paiement is null -- si date_mise_en_paiement = null, c'est une demande
    )
    group by intervenant_id, structure_id
  )
select to_number(intervenant_id||structure_id) id, 2014 annee_id, intervenant_id, structure_id, total_heures_mep from mep