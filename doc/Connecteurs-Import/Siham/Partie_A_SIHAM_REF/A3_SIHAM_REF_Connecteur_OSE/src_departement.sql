create or replace force view src_departement as  
select
  hq.code          code,
  hq.libelle_long  libelle_long,
  s.id             source_id,
  hq.source_code   source_code
from
       um_departement hq
  join source         s on s.code = hq.source_id;