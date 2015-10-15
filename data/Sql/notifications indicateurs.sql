select * from personnel where nom_usuel like 'Lobs%';

select * from structure where id = 238;

select
  i.id i_id,
  ni.id ni_id,
  p.id p_id,
  a.id a_id,
  sni.id sni_id,
  sa.id sa_id,
  
  p.NOM_usuel || ' ' || p.PRENOM personnel,
  i.NUMERO || ' ' || i.CODE indicateur,

  sni.libelle_court struct_not_indic,
  sa.libelle_court struct_affectation
  
from
  NOTIFICATION_INDICATEUR ni
  JOIN indicateur i ON i.id = ni.indicateur_id
  JOIN personnel p ON p.id = ni.personnel_id
  LEFT JOIN affectation a ON a.personnel_id = p.id AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
  LEFT JOIN structure sni ON sni.id = ni.structure_id AND sni.niveau = 2
  LEFT JOIN structure sa ON sa.id = a.structure_id AND sa.niveau = 2
where
  NVL(ni.structure_id,0) <> NVL(a.structure_id,0)
ORDER BY
  personnel, indicateur
;

delete from NOTIFICATION_INDICATEUR where
personnel_id = 3079
AND structure_id = 238;


select * from AFFECTATION;

select * from structure where niveau = 1;