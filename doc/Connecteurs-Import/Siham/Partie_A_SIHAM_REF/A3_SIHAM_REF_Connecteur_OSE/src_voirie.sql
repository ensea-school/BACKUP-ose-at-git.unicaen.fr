create or replace force view src_voirie as 
select
  code,
  libelle,
  source_id,
  source_code
from
  um_voirie p;
