SELECT 
  /**/ DISTINCT 'INSERT INTO VALIDATION (
    ID,
    TYPE_VALIDATION_ID,
    INTERVENANT_ID,
    STRUCTURE_ID,
    HISTO_CREATION,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATION,
    HISTO_MODIFICATEUR_ID
  ) VALUES (
    validation_id_seq.nextval,
    ' || tv.id || ',
    ' || i.id || ',
    ' || i.structure_id || ',
    sysdate,
    ' || u.id || ',
    sysdate,
    ' || u.id || '
  );' vsql /*/
  
  'INSERT INTO VALIDATION_VOL_HORAIRE (
      VALIDATION_ID,  VOLUME_HORAIRE_ID
  ) VALUES ( 
    ' || vv.id || ', ' || vh.id || '
  );' vsql /* */
FROM
  service s
  JOIN intervenant                   i ON i.id = s.intervenant_id
                                    --AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
 
  JOIN statut_intervenant           si ON si.id = i.statut_id
  JOIN type_intervenant             ti ON ti.id = si.type_intervenant_id
  JOIN type_volume_horaire         tvh ON tvh.code = 'PREVU'
  
  JOIN volume_horaire               vh ON vh.service_id = s.id
                                      AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
                                      AND vh.type_volume_horaire_id = tvh.id
                                  
  JOIN type_validation              tv ON tv.code = 'SERVICES_PAR_COMP'
  JOIN utilisateur                   u ON u.USERNAME = 'lecluse'

  LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
  LEFT JOIN validation               v ON v.id = vvh.validation_id
                                      AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )

  LEFT JOIN validation              vv ON vv.intervenant_id = i.id
                                      AND vv.structure_id = i.structure_id
                                      AND vv.histo_createur_id = u.id
                                      AND vv.type_validation_id = tv.id
                                      AND vv.histo_creation > SYSDATE - 1
WHERE
  1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
  AND v.id IS NULL
  AND i.annee_id = 2015
  AND ti.code = 'P'
  --AND vv.id IS NOT NULL
;

SELECT 
  /*/ DISTINCT 'INSERT INTO VALIDATION (
    ID,
    TYPE_VALIDATION_ID,
    INTERVENANT_ID,
    STRUCTURE_ID,
    HISTO_CREATION,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATION,
    HISTO_MODIFICATEUR_ID
  ) VALUES (
    validation_id_seq.nextval,
    ' || tv.id || ',
    ' || i.id || ',
    ' || i.structure_id || ',
    sysdate,
    ' || u.id || ',
    sysdate,
    ' || u.id || '
  );' vsql /*/
  
  'INSERT INTO VALIDATION_VOL_HORAIRE_REF (
      VALIDATION_ID,  VOLUME_HORAIRE_REF_ID
  ) VALUES ( 
    ' || vv.id || ', ' || vhr.id || '
  );' vsql /**/
FROM
  service_referentiel                    sr
  JOIN intervenant                        i ON i.id = sr.intervenant_id
                                           --AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )

  JOIN statut_intervenant                si ON si.id = i.statut_id
  JOIN type_intervenant                  ti ON ti.id = si.type_intervenant_id
  JOIN type_volume_horaire              tvh ON tvh.code = 'PREVU'

  JOIN volume_horaire_ref               vhr ON vhr.service_referentiel_id = sr.id
                                           AND 1 = ose_divers.comprise_entre( vhr.histo_creation, vhr.histo_destruction )
                                           AND vhr.type_volume_horaire_id = tvh.id

  JOIN type_validation                   tv ON tv.code = 'REFERENTIEL'
  JOIN utilisateur                        u ON u.USERNAME = 'lecluse'


  LEFT JOIN validation_vol_horaire_ref vvhr ON vvhr.volume_horaire_ref_id = vhr.id
  LEFT JOIN validation                    v ON v.id = vvhr.validation_id
                                           AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
                                           
  LEFT JOIN validation                   vv ON vv.intervenant_id = i.id
                                           AND vv.structure_id = i.structure_id
                                           AND vv.histo_createur_id = u.id
                                           AND vv.type_validation_id = tv.id
                                           AND vv.histo_creation > SYSDATE - 1
WHERE
  1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction )
  AND v.id IS NULL
  AND i.annee_id = 2015
  AND ti.code = 'P'
;


--triggers à désactiver pour accélérer le traitement en masse!!

alter trigger VALIDATION_CK disable;
alter trigger VALIDATION_VOL_HORAIRE_CK disable;

alter trigger VALIDATION_VOL_HORAIRE_CK disable;
alter trigger F_VALIDATION disable;
alter trigger F_VALIDATION_S disable;

alter trigger F_VALIDATION_VOL_HORAIRE disable;
alter trigger F_VALIDATION_VOL_HORAIRE_S disable;

alter trigger F_VALIDATION_VOL_HORAIRE_REF disable;
alter trigger F_VALIDATION_VOL_HORAIRE_REF_S disable;

alter trigger WF_TRG_VH_VALIDATION disable;
alter trigger WF_TRG_VH_VALIDATION_S disable;



alter trigger VALIDATION_CK enable;
alter trigger VALIDATION_VOL_HORAIRE_CK enable;

alter trigger VALIDATION_VOL_HORAIRE_CK enable;
alter trigger F_VALIDATION enable;
alter trigger F_VALIDATION_S enable;

alter trigger F_VALIDATION_VOL_HORAIRE enable;
alter trigger F_VALIDATION_VOL_HORAIRE_S enable;

alter trigger F_VALIDATION_VOL_HORAIRE_REF enable;
alter trigger F_VALIDATION_VOL_HORAIRE_REF_S enable;

alter trigger WF_TRG_VH_VALIDATION enable;
alter trigger WF_TRG_VH_VALIDATION_S enable;

/

BEGIN
  ose_formule.CALCULER_TOUT();
  ose_workflow.update_all_intervenants_etapes();
END;
