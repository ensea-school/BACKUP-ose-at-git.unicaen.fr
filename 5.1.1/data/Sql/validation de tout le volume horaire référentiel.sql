SELECT DISTINCT -- pour créer les validations
  'INSERT INTO validation (
    id,
    type_validation_id,
    intervenant_id,
    structure_id,
    histo_createur_id,
    histo_creation,
    histo_modificateur_id,
    histo_modification
  ) VALUES (
    validation_id_seq.nextval,
    ' || tv.id || ',
    ' || i.id || ',
    ' || i.structure_id || ',
    (select id from utilisateur where username=''labeyrie''),
    sysdate,
    (select id from utilisateur where username=''labeyrie''),
    sysdate
  );' isql
FROM
  volume_horaire_ref vhr
  JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id AND 1 = ose_divers.comprise_entre(sr.histo_creation,sr.histo_destruction)
  JOIN intervenant i ON i.id = sr.intervenant_id
  JOIN type_validation tv ON tv.code = 'REFERENTIEL'
  LEFT JOIN validation_vol_horaire_ref vvhr ON vvhr.volume_horaire_ref_id = vhr.id
  LEFT JOIN validation v ON v.id = vvhr.validation_id AND 1 = ose_divers.comprise_entre(v.histo_creation,v.histo_destruction)
WHERE
  1 = ose_divers.comprise_entre(vhr.histo_creation,vhr.histo_destruction)
  AND v.id IS NULL
;

select
  'INSERT INTO VALIDATION_VOL_HORAIRE_REF
  ( VALIDATION_ID, VOLUME_HORAIRE_REF_ID )
  VALUES
  ( '|| VALIDATION_ID || ', ' || VOLUME_HORAIRE_REF_ID || ' );' isql
from
(
SELECT -- pour créer les validations de volumes horaires
  vv.id validation_id,
  vhr.id volume_horaire_ref_id
FROM
  volume_horaire_ref vhr
  JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id AND 1 = ose_divers.comprise_entre(sr.histo_creation,sr.histo_destruction)
  JOIN intervenant i ON i.id = sr.intervenant_id
  JOIN type_validation tv ON tv.code = 'REFERENTIEL'
  JOIN utilisateur u ON u.username='labeyrie'
  LEFT JOIN validation_vol_horaire_ref vvhr ON vvhr.volume_horaire_ref_id = vhr.id
  LEFT JOIN validation v ON v.id = vvhr.validation_id AND 1 = ose_divers.comprise_entre(v.histo_creation,v.histo_destruction)
  LEFT JOIN validation vv ON 
    vv.type_validation_id = tv.id
    AND vv.intervenant_id = i.id
    AND vv.structure_id = i.structure_id
    AND vv.histo_createur_id = u.id
WHERE
  1 = ose_divers.comprise_entre(vhr.histo_creation,vhr.histo_destruction)
  AND v.id IS NULL
) t1
;