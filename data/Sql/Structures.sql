

select id, source_code, rpad(' ', (level-1)*4) || libelle_court libelle
from structure
start with parente_id is null
connect by parente_id = prior id;