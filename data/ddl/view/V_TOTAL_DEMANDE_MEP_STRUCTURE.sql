CREATE OR REPLACE FORCE VIEW V_TOTAL_DEMANDE_MEP_STRUCTURE AS
with mep as (
  -- enseignements
  select
    fr.intervenant_id,
    nvl(ep.structure_id, i.structure_id) structure_id,
    nvl(mep.heures, 0) mep_heures
  from mise_en_paiement mep
  join formule_resultat_service frs on mep.formule_res_service_id = frs.id --and mep.date_mise_en_paiement is null -- date_mise_en_paiement is null <=> demande
  join formule_resultat fr on frs.formule_resultat_id = fr.id
  join intervenant i on fr.intervenant_id = i.id
  join service s on frs.service_id = s.id
  left join element_pedagogique ep on s.element_pedagogique_id = ep.id and ep.histo_destruction IS NULL
  where mep.histo_destruction IS NULL
  union all
  -- referentiel
  select
    fr.intervenant_id,
    s.structure_id,
    nvl(mep.heures, 0) mep_heures
  from mise_en_paiement mep
  join formule_resultat_service_ref frs on mep.formule_res_service_ref_id = frs.id --and mep.date_mise_en_paiement is null -- date_mise_en_paiement is null <=> demande
  join formule_resultat fr on frs.formule_resultat_id = fr.id
  join intervenant i on fr.intervenant_id = i.id
  join service_referentiel s on frs.service_referentiel_id = s.id
  where mep.histo_destruction IS NULL
)
select intervenant_id, structure_id, sum(nvl(mep_heures, 0)) total_heures_mep from mep
group by intervenant_id, structure_id