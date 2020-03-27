CREATE OR REPLACE FORCE VIEW V_SERVICE_NON_VALIDE AS
select vh.ID, i.ID as intervenant_id, s.ID as service_id, vh.ID as volume_horaire_id, ep.id as element_pedagogique_id, ep.LIBELLE, vh.HEURES
  from service s
  inner join INTERVENANT i on s.INTERVENANT_ID = i.id
  inner join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id and ep.histo_destruction IS NULL
  inner join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.ID and vh.HISTO_DESTRUCTION is null
  left join VALIDATION_VOL_HORAIRE vvh on vvh.VOLUME_HORAIRE_ID = vh.ID
  left join VALIDATION v on vvh.VALIDATION_ID = v.ID
  left join TYPE_VALIDATION tv on v.TYPE_VALIDATION_ID = tv.ID
  where (v.ID is null or v.HISTO_DESTRUCTION is not null) and
  not exists (
    select * from VALIDATION_VOL_HORAIRE vvh2
    inner join VALIDATION v2 on vvh2.VALIDATION_ID = v2.ID and v2.histo_destruction IS NULL
    where vvh2.VOLUME_HORAIRE_ID = vvh.VOLUME_HORAIRE_ID
  )