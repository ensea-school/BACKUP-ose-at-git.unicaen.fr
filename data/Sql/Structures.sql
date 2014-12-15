SELECT * FROM

(select id, source_code, rpad(' ', (level-1)*4) || libelle_court libelle, niveau
from structure
start with parente_id is null
connect by parente_id = prior id) tmp
WHERE
  niveau < 3
  
ORDER BY
  libelle

;