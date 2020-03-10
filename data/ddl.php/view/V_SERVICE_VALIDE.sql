CREATE OR REPLACE FORCE VIEW V_SERVICE_VALIDE AS
select vh.ID, i.ID as intervenant_id, s.ID as service_id, vh.ID as volume_horaire_id, ep.id as element_pedagogique_id, ep.LIBELLE, vh.HEURES, v.ID as validation_id, tv.CODE
  from service s
  inner join INTERVENANT i on s.INTERVENANT_ID = i.id
  left join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id and ep.histo_destruction IS NULL -- pas d'EP si intervention hors-UCBN
  inner join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.ID and vh.histo_destruction IS NULL
  inner join VALIDATION_VOL_HORAIRE vvh on vvh.VOLUME_HORAIRE_ID = vh.ID
  inner join VALIDATION v on vvh.VALIDATION_ID = v.ID and v.histo_destruction IS NULL
  inner join TYPE_VALIDATION tv on v.TYPE_VALIDATION_ID = tv.ID