create or replace force view src_corps as
select
  libelle_long,
  libelle_court,
  src.id source_id,
  source_code
from um_corps
    ,source src 
where src.code = 'Siham';