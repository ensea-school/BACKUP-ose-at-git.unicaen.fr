CREATE OR REPLACE FORCE VIEW V_INDIC_ATT_VALID_SERVICE_REF AS
select distinct rownum id, i.id intervenant_id, s.structure_id, vh.type_volume_horaire_id
from service_referentiel s
join intervenant i on i.id = s.intervenant_id and i.histo_destruction IS NULL
join volume_horaire_ref vh on vh.service_referentiel_id = s.id and vh.histo_destruction IS NULL
join fonction_referentiel f on s.fonction_id = f.id and f.histo_destruction IS NULL
--join type_volume_horaire tvh on vh.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
left join validation_vol_horaire_ref vvh on vvh.volume_horaire_ref_id = vh.id
left join validation v on vvh.validation_id = v.id and v.histo_destruction IS NULL
where v.id is null and s.histo_destruction IS NULL