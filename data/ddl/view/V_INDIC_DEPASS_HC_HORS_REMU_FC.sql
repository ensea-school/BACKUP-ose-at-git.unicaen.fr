CREATE OR REPLACE FORCE VIEW V_INDIC_DEPASS_HC_HORS_REMU_FC AS
with totaux as (
  -- totaux HC FI+FA+FC+Ref par intervenant et type de VH
  select fr.intervenant_id, fr.type_volume_horaire_id, sum(fr.heures_compl_fi + fr.heures_compl_fa + fr.heures_compl_fc + fr.heures_compl_referentiel) total
  from formule_resultat fr
  join etat_volume_horaire evh on evh.id = fr.etat_volume_horaire_id and evh.code = 'saisi'
  group by fr.intervenant_id, fr.type_volume_horaire_id
),
depass as (
  -- totaux HC FI+FA+FC+Ref dépassant le plafond HC par intervenant et type de VH
  select i.id intervenant_id, t.type_volume_horaire_id, t.total, si.plafond_hc_hors_remu_fc plafond
  from intervenant i
  join statut si on i.statut_id = si.id and si.plafond_hc_hors_remu_fc is not null
  join totaux t on t.intervenant_id = i.id
  where t.total > si.plafond_hc_hors_remu_fc
),
str_interv as (
  -- structures d'intervention distinctes par intervenant et type de VH
  select distinct s.intervenant_id, vh.type_volume_horaire_id, coalesce(ep.structure_id, i.structure_id) structure_id
  from service s
  left join element_pedagogique ep on s.element_pedagogique_id = ep.id and ep.histo_destruction IS NULL
  join intervenant i on s.intervenant_id = i.id and i.histo_destruction IS NULL
  join volume_horaire vh on vh.service_id = s.id and vh.histo_destruction IS NULL
  join v_vol_horaire_etat_multi vhe on vhe.volume_horaire_id = vh.id
  join etat_volume_horaire evh on vhe.etat_volume_horaire_id = evh.id and evh.code = 'saisi'
  where s.histo_destruction IS NULL
)
select to_number(d.intervenant_id||d.type_volume_horaire_id||str.structure_id) id, 2014 annee_id, d.intervenant_id, d.type_volume_horaire_id, str.structure_id, d.total, d.plafond
from depass d
join str_interv str on str.intervenant_id = d.intervenant_id and str.type_volume_horaire_id = d.type_volume_horaire_id