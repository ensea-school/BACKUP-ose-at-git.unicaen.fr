SELECT
 'INSERT INTO VOLUME_HORAIRE (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    SERVICE_ID,
    PERIODE_ID,
    TYPE_INTERVENTION_ID,
    HEURES,
    MOTIF_NON_PAIEMENT_ID,
    CONTRAT_ID,
    HISTO_CREATION,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATION,
    HISTO_MODIFICATEUR_ID
  ) VALUES (
    VOLUME_HORAIRE_id_seq.nextval,
    ' || tvhr.id || ',
    ' || s.id || ',
    ' || vh.periode_id || ',
    ' || vh.TYPE_INTERVENTION_ID || ',
    ' || replace( to_char(vh.HEURES), ',', '.' ) || ',
    ' || NVL(to_char(vh.MOTIF_NON_PAIEMENT_ID),'null') || ',
    null,
    sysdate,
    ' || u.id || ',
    sysdate,
    ' || u.id || '
  );' isql
  
FROM
  volume_horaire             vh
  JOIN service                s ON s.id =vh.service_id
  JOIN intervenant            i ON i.id = s.intervenant_id
  JOIN statut_intervenant    si ON si.id = i.statut_id
  JOIN type_intervenant      ti ON ti.id = si.type_intervenant_id
  JOIN type_volume_horaire tvhp ON tvhp.code = 'PREVU'
  JOIN type_volume_horaire tvhr ON tvhr.code = 'REALISE'
  JOIN etat_volume_horaire  evh ON evh.code = 'saisi'
  JOIN utilisateur            u ON u.USERNAME = 'lecluse'
  LEFT JOIN formule_resultat fr ON fr.intervenant_id = s.intervenant_id
                               AND fr.etat_volume_horaire_id = evh.id
                               AND fr.type_volume_horaire_id = tvhr.id
WHERE
  1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  AND vh.heures <> 0
  AND NVL(fr.TOTAL,0) = 0
  AND i.annee_id = 2015
  AND ti.code = 'P'
ORDER BY
  i.id
;



SELECT
 'INSERT INTO VOLUME_HORAIRE_REF (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    SERVICE_REFERENTIEL_ID,
    HEURES,
    HISTO_CREATION,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATION,
    HISTO_MODIFICATEUR_ID
  ) VALUES (
    VOLUME_HORAIRE_id_seq.nextval,
    ' || tvhr.id || ',
    ' || sr.id || ',
    ' || replace( vhr.HEURES, ',', '.' ) || ',
    sysdate,
    ' || u.id || ',
    sysdate,
    ' || u.id || '
  );' isql
  
FROM
  volume_horaire_ref         vhr
  JOIN service_referentiel    sr ON sr.id =vhr.service_referentiel_id
  JOIN intervenant             i ON i.id = sr.intervenant_id
  JOIN statut_intervenant     si ON si.id = i.statut_id
  JOIN type_intervenant       ti ON ti.id = si.type_intervenant_id
  JOIN type_volume_horaire  tvhp ON tvhp.code = 'PREVU'
  JOIN type_volume_horaire  tvhr ON tvhr.code = 'REALISE'
  JOIN etat_volume_horaire   evh ON evh.code = 'saisi'
  JOIN utilisateur             u ON u.USERNAME = 'lecluse'
  LEFT JOIN formule_resultat  fr ON fr.intervenant_id = sr.intervenant_id
                                AND fr.etat_volume_horaire_id = evh.id
                                AND fr.type_volume_horaire_id = tvhr.id
WHERE
  1 = ose_divers.comprise_entre( vhr.histo_creation, vhr.histo_destruction )
  AND vhr.heures <> 0
  AND NVL(fr.TOTAL,0) = 0
  AND i.annee_id = 2015
  AND ti.code = 'P'
ORDER BY
  i.id
;





--triggers à désactiver pour accélérer le traitement en masse!!

alter trigger F_VOLUME_HORAIRE disable;
alter trigger F_VOLUME_HORAIRE_S disable;

alter trigger F_VOLUME_HORAIRE_REF disable;
alter trigger F_VOLUME_HORAIRE_REF_S disable;

alter trigger VOLUME_HORAIRE_CK disable;
alter trigger VOLUME_HORAIRE_REF_CK disable;

alter trigger WF_TRG_VOLUME_HORAIRE disable;
alter trigger WF_TRG_VOLUME_HORAIRE_S disable;




alter trigger F_VOLUME_HORAIRE enable;
alter trigger F_VOLUME_HORAIRE_S enable;

alter trigger F_VOLUME_HORAIRE_REF enable;
alter trigger F_VOLUME_HORAIRE_REF_S enable;

alter trigger VOLUME_HORAIRE_CK enable;
alter trigger VOLUME_HORAIRE_REF_CK enable;

alter trigger WF_TRG_VOLUME_HORAIRE enable;
alter trigger WF_TRG_VOLUME_HORAIRE_S enable;

/

BEGIN
  --ose_formule.CALCULER_TOUT();
  ose_workflow.update_all_intervenants_etapes();
END;
