-- 2 année univ dans OSE

declare 

i               number;
v_ordre         number := 18;
v_delta_mois    number := 17;
v_id_periode    number;
begin
  for i in 2..12 loop
    select periode_id_seq.nextval into v_id_periode from dual;
    insert into periode(id,code,libelle_long,libelle_court,ecart_mois,ordre,enseignement,paiement,histo_creation,histo_createur_id,histo_modification,histo_modificateur_id) values (
      v_id_periode
    , 'R'||decode(length(i), 1,'0'||i,i)
    , case  i when  1 then 'Reliquat Janvier'
              when  2 then 'Reliquat Février'
              when  3 then 'Reliquat Mars'
              when  4 then 'Reliquat Avril'
              when  5 then 'Reliquat Mai'
              when  6 then 'Reliquat Juin'
              when  7 then 'Reliquat Juillet'
              when  8 then 'Reliquat Août'
              when  9 then 'Reliquat Septembre'
              when 10 then 'Reliquat Octobre'
              when 11 then 'Reliquat Novembre'
              when 12 then 'Reliquat Décembre'
      end
    , case  i when  1 then 'Reliquat 01'
              when  2 then 'Reliquat 02'
              when  3 then 'Reliquat 03'
              when  4 then 'Reliquat 04'
              when  5 then 'Reliquat 05'
              when  6 then 'Reliquat 06'
              when  7 then 'Reliquat 07'
              when  8 then 'Reliquat 08'
              when  9 then 'Reliquat 09'
              when 10 then 'Reliquat 10'
              when 11 then 'Reliquat 11'
              when 12 then 'Reliquat 12'
      end
    , v_delta_mois
    , v_ordre
    , 0
    , 1
    , trunc(sysdate)
    , 1
    , trunc(sysdate)
    , 1);
    v_delta_mois := v_delta_mois+1;
    v_ordre := v_ordre+1;
  end loop;
  commit;
  update periode set libelle_long = 'Reliquat Janvier', libelle_court = 'Reliquat 01' where code = 'PTD';
end;
     
-- 3 année univ dans OSE 
-- 
declare 

i               number;
v_ordre         number := 29;
v_delta_mois    number := 28;
v_id_periode    number;
begin
  for i in 13..24 loop
    select periode_id_seq.nextval into v_id_periode from dual;
    insert into periode(id,code,libelle_long,libelle_court,ecart_mois,ordre,enseignement,paiement,histo_creation,histo_createur_id,histo_modification,histo_modificateur_id) values (
      v_id_periode
    , concat('R',i)
    , case  i-12 when  1 then 'Reliquat Janvier'
				 when  2 then 'Reliquat Février'
				 when  3 then 'Reliquat Mars'
				 when  4 then 'Reliquat Avril'
				 when  5 then 'Reliquat Mai'
				 when  6 then 'Reliquat Juin'
				 when  7 then 'Reliquat Juillet'
				 when  8 then 'Reliquat Août'
				 when  9 then 'Reliquat Septembre'
				 when 10 then 'Reliquat Octobre'
				 when 11 then 'Reliquat Novembre'
				 when 12 then 'Reliquat Décembre'
      end
    , case  i-12 when  1 then 'Reliquat 01'
				 when  2 then 'Reliquat 02'
				 when  3 then 'Reliquat 03'
				 when  4 then 'Reliquat 04'
				 when  5 then 'Reliquat 05'
				 when  6 then 'Reliquat 06'
				 when  7 then 'Reliquat 07'
				 when  8 then 'Reliquat 08'
				 when  9 then 'Reliquat 09'
				 when 10 then 'Reliquat 10'
				 when 11 then 'Reliquat 11'
				 when 12 then 'Reliquat 12'
      end
    , v_delta_mois
    , v_ordre
    , 0
    , 1
    , trunc(sysdate)
    , 1
    , trunc(sysdate)
    , 1);
    v_delta_mois := v_delta_mois+1;
    v_ordre := v_ordre+1;
  end loop;
  commit;
end;

-- 4 année univ dans OSE 
-- 
declare 

i               number;
v_ordre         number := 42;
v_delta_mois    number := 40;
v_id_periode    number;
begin
  for i in 25..36 loop
    select periode_id_seq.nextval into v_id_periode from dual;
    insert into periode(id,code,libelle_long,libelle_court,ecart_mois,ordre,enseignement,paiement,histo_creation,histo_createur_id,histo_modification,histo_modificateur_id) values (
      v_id_periode
    , concat('R',i)
    , case  i-24 when  1 then 'Reliquat Janvier'
				 when  2 then 'Reliquat Février'
				 when  3 then 'Reliquat Mars'
				 when  4 then 'Reliquat Avril'
				 when  5 then 'Reliquat Mai'
				 when  6 then 'Reliquat Juin'
				 when  7 then 'Reliquat Juillet'
				 when  8 then 'Reliquat Août'
				 when  9 then 'Reliquat Septembre'
				 when 10 then 'Reliquat Octobre'
				 when 11 then 'Reliquat Novembre'
				 when 12 then 'Reliquat Décembre'
      end
    , case  i-24 when  1 then 'Reliquat 01'
				 when  2 then 'Reliquat 02'
				 when  3 then 'Reliquat 03'
				 when  4 then 'Reliquat 04'
				 when  5 then 'Reliquat 05'
				 when  6 then 'Reliquat 06'
				 when  7 then 'Reliquat 07'
				 when  8 then 'Reliquat 08'
				 when  9 then 'Reliquat 09'
				 when 10 then 'Reliquat 10'
				 when 11 then 'Reliquat 11'
				 when 12 then 'Reliquat 12'
      end
    , v_delta_mois
    , v_ordre
    , 0
    , 1
    , trunc(sysdate)
    , 1
    , trunc(sysdate)
    , 1);
    v_delta_mois := v_delta_mois+1;
    v_ordre := v_ordre+1;
  end loop;
  commit;
end;