CREATE OR REPLACE FORCE VIEW V_CENTRE_COUT_TYPE_HEURES AS
select
  cc.id centre_cout_id,
  th.id type_heures_id
from
  centre_cout cc
  join type_ressource  tr on tr.id = cc.type_ressource_id
  join cc_activite    cca on cca.id = cc.activite_id
  join type_heures     th on th.code = decode(tr.fi + cca.fi, 2, 'fi', null)

union all

select
  cc.id centre_cout_id,
  th.id type_heures_id
from
  centre_cout cc
  join type_ressource  tr on tr.id = cc.type_ressource_id
  join cc_activite    cca on cca.id = cc.activite_id
  join type_heures     th on th.code = decode(tr.fc + cca.fc, 2, 'fc', null)

union all

select
  cc.id centre_cout_id,
  th.id type_heures_id
from
  centre_cout cc
  join type_ressource  tr on tr.id = cc.type_ressource_id
  join cc_activite    cca on cca.id = cc.activite_id
  join type_heures     th on th.code = decode(tr.fa + cca.fa, 2, 'fa', null)

union all

select
  cc.id centre_cout_id,
  th.id type_heures_id
from
  centre_cout cc
  join type_ressource  tr on tr.id = cc.type_ressource_id
  join cc_activite    cca on cca.id = cc.activite_id
  join type_heures     th on th.code = decode(tr.referentiel + cca.referentiel, 2, 'referentiel', null)

union all

select
  cc.id centre_cout_id,
  th.id type_heures_id
from
  centre_cout cc
  join type_ressource  tr on tr.id = cc.type_ressource_id
  join cc_activite    cca on cca.id = cc.activite_id
  join type_heures     th on th.code = decode(tr.primes + cca.primes, 2, 'primes', null)