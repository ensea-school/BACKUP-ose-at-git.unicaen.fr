select * from individu@harpprod;
select * from individu@apoprod;

select count(*) from ucbn_vue_ose_ens@harpprod v where v.type = 'permanent'; --1221
select count(*) from ucbn_vue_ose_ens@harpprod v where v.type = 'exterieur'; --1099
select count(*) from ucbn_vue_ose_ens@harpprod v where v.type = 'biatss'; --11
select count(*) from ucbn_vue_ose_ens@harpprod v where v.type = 'autre'; --1769

SELECT COUNT(*) FROM MV_OSE_ENS;
