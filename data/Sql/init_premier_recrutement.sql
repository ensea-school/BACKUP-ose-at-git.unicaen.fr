UPDATE intervenant SET
  premier_recrutement = (SELECT 
  case when count(*) > 0 then 0 else 1 end
from
  intervenant i
  JOIN formule_resultat fr ON fr.intervenant_id = i.id
where
  source_code IN (select source_code from intervenant where id = 8334)
  AND i.annee_id = 2014
  AND fr.total > 0)
WHERE
  id = 8334
  AND premier_recrutement IS NULL
  ;

SELECT 
  case when count(*) > 0 then 0 else 1 end
from
  intervenant i
  JOIN formule_resultat fr ON fr.intervenant_id = i.id
where
  source_code IN (select source_code from intervenant where id = 8919)
  AND i.annee_id = 2014
  AND fr.total > 0;


select source_code from intervenant where id = 5249;


8329   8334