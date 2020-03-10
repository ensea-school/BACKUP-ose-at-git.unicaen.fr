CREATE OR REPLACE FORCE VIEW V_INDIC_ATT_VALID_SERVICE AS
select distinct rownum id, i.id intervenant_id, nvl(ep.structure_id, i.structure_id) structure_id, vh.type_volume_horaire_id
from service s
join intervenant i on i.id = s.intervenant_id and i.histo_destruction IS NULL
join volume_horaire vh on vh.service_id = s.id and vh.histo_destruction IS NULL
join element_pedagogique ep on s.element_pedagogique_id = ep.id and ep.histo_destruction IS NULL
--join type_volume_horaire tvh on vh.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
left join validation_vol_horaire vvh on vvh.volume_horaire_id = vh.id
left join validation v on vvh.validation_id = v.id and v.histo_destruction IS NULL
where v.id is null and s.histo_destruction IS NULL