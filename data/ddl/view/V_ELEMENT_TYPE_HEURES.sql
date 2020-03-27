CREATE OR REPLACE FORCE VIEW V_ELEMENT_TYPE_HEURES AS
select ep.id element_pedagogique_id, th.id type_heures_id
  from element_pedagogique ep
  join type_heures th on th.code = decode(ep.fi, 1, 'fi', null)
union all
  select ep.id element_pedagogique_id, th.id type_heures_id
  from element_pedagogique ep
  join type_heures th on th.code = decode(ep.fc, 1, 'fc', null)
union all
  select ep.id element_pedagogique_id, th.id type_heures_id
  from element_pedagogique ep
  join type_heures th on th.code = decode(ep.fa, 1, 'fa', null)