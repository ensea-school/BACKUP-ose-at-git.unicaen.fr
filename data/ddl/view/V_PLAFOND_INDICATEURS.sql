CREATE OR REPLACE FORCE VIEW V_PLAFOND_INDICATEURS AS
SELECT
  p.numero*10 + tvh.id ordre,
  1 enabled,
  p.numero*100 + tvh.id *10 numero,
  '%s intervenants dépassent en ' || lower(tvh.libelle) || ' le plafond "' || p.libelle || '"' libelle_pluriel,
  '%s intervenant dépasse en ' || lower(tvh.libelle) || ' le plafond "' || p.libelle || '"' libelle_singulier,
  'intervenant/voir' route,
  CASE pp.code
    WHEN 'intervenant'    THEN 12
    WHEN 'structure'      THEN 13
    WHEN 'referentiel'    THEN 14
    WHEN 'element'        THEN 15
    WHEN 'volume_horaire' THEN 16
    WHEN 'mission'        THEN 17
  END type_indicateur_id,
  0 irrecevables
FROM
  plafond p
  JOIN plafond_perimetre pp ON pp.id = p.plafond_perimetre_id
  JOIN type_volume_horaire tvh ON 1=1