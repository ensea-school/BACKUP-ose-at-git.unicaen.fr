CREATE OR REPLACE FORCE VIEW V_PJ_HEURES AS
SELECT
  i.NOM_USUEL,
  i.PRENOM,
  i.id intervenant_id,
  i.SOURCE_CODE,
  i.annee_id, 'service' categ,
  sum(vh.HEURES) as total_heures
from INTERVENANT i
  join SERVICE s on s.INTERVENANT_ID = i.id      and s.histo_destruction IS NULL
  join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.id and vh.histo_destruction IS NULL
  join type_volume_horaire tvh ON TVH.ID = VH.TYPE_VOLUME_HORAIRE_ID AND (tvh.code = 'PREVU')
  join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id        and ep.histo_destruction IS NULL
  join ETAPE e on ep.ETAPE_ID = e.id and e.histo_destruction IS NULL
where i.histo_destruction IS NULL
  group by i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, i.annee_id, 'service'
UNION
  SELECT i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, i.annee_id, 'referentiel' categ, sum(vh.HEURES) as total_heures
  from INTERVENANT i
  join service_referentiel s on s.INTERVENANT_ID = i.id                  and s.histo_destruction IS NULL
  join volume_horaire_ref vh on vh.service_referentiel_id = s.id         and vh.histo_destruction IS NULL
  join type_volume_horaire tvh ON TVH.ID = VH.TYPE_VOLUME_HORAIRE_ID     AND (tvh.code = 'PREVU')
  join fonction_referentiel ep on s.fonction_id = ep.id                  and ep.histo_destruction IS NULL
  where i.histo_destruction IS NULL
  group by i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, i.annee_id, 'referentiel'