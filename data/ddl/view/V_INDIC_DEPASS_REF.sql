CREATE OR REPLACE FORCE VIEW V_INDIC_DEPASS_REF AS
with totaux as (
  select fr.intervenant_id, fr.type_volume_horaire_id, sum(fr.service_referentiel) total
  from formule_resultat fr
  join etat_volume_horaire evh on evh.id = fr.etat_volume_horaire_id and evh.code = 'saisi'
  group by fr.intervenant_id, fr.type_volume_horaire_id
  having sum(fr.service_referentiel) > 0
),
depass as (
  select i.id intervenant_id, t.type_volume_horaire_id, t.total, si.plafond_referentiel plafond
  from intervenant i
  join statut si on i.statut_id = si.id and si.plafond_referentiel is not null and si.plafond_referentiel <> 0
  join totaux t on t.intervenant_id = i.id
  where t.total > si.plafond_referentiel
),
str_interv as (
  -- structures d'intervention distinctes par intervenant et type de VH
  select distinct s.intervenant_id, vh.type_volume_horaire_id, s.structure_id
  from service_referentiel s
  join volume_horaire_ref vh on vh.service_referentiel_id = s.id and vh.histo_destruction IS NULL
  join v_vol_horaire_ref_etat_multi vhe on vhe.volume_horaire_ref_id = vh.id
  join etat_volume_horaire evh on vhe.etat_volume_horaire_id = evh.id and evh.code = 'saisi'
  where s.histo_destruction IS NULL
)
select to_number(d.intervenant_id||d.type_volume_horaire_id||str.structure_id) id, 2014 annee_id, d.intervenant_id, d.type_volume_horaire_id, str.structure_id, d.total, d.plafond
from depass d
join str_interv str on str.intervenant_id = d.intervenant_id and str.type_volume_horaire_id = d.type_volume_horaire_id