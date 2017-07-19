select
  s.libelle_court str,
  i.nom_usuel nom,
  i.prenom prenom,
  sr.heures
from
  service_referentiel sr
  JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
  JOIN intervenant i ON i.id = sr.intervenant_id AND i.histo_destruction IS NULL
  LEFT JOIN structure s ON s.id = sr.structure_id AND s.histo_destruction IS NULL
WHERE
  sr.histo_destruction IS NULL
  AND fr.code = 'PRL';