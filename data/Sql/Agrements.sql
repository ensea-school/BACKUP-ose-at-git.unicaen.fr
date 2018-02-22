SELECT
  tas.id,
  si.libelle statut_intervenant,
  ta.code type_agrement,
  tas.obligatoire,
  tas.premier_recrutement,
  tas.histo_destruction
FROM
  type_agrement_statut tas
  JOIN type_agrement ta ON ta.id = tas.type_agrement_id
  JOIN statut_intervenant si ON si.id = tas.statut_intervenant_id
ORDER BY
  statut_intervenant,
  type_agrement;

SELECT source_code, libelle FROM statut_intervenant WHERE histo_destruction IS NULL;



INSERT INTO TYPE_AGREMENT_STATUT (
  ID,
  TYPE_AGREMENT_ID,
  STATUT_INTERVENANT_ID,
  OBLIGATOIRE,
  PREMIER_RECRUTEMENT,
  HISTO_CREATION,HISTO_CREATEUR_ID,
  HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID
) VALUES (
  TYPE_AGREMENT_STATUT_ID_SEQ.NEXTVAL,
  (SELECT id FROM type_agrement WHERE code = 'CONSEIL_RESTREINT'), -- CONSEIL_ACADEMIQUE | CONSEIL_RESTREINT
  (SELECT id FROM statut_intervenant WHERE source_code = 'INTERMITTENT'),
  1, -- OBLIGATOIRE
  NULL, -- PREMIER_RECRUTEMENT
  sysdate, (SELECT id FROM utilisateur WHERE username='lecluse'),
  sysdate, (SELECT id FROM utilisateur WHERE username='lecluse')
);


INSERT INTO TYPE_AGREMENT_STATUT (
  ID,
  TYPE_AGREMENT_ID,
  STATUT_INTERVENANT_ID,
  OBLIGATOIRE,
  PREMIER_RECRUTEMENT,
  HISTO_CREATION,HISTO_CREATEUR_ID,
  HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID
) VALUES (
  TYPE_AGREMENT_STATUT_ID_SEQ.NEXTVAL,
  (SELECT id FROM type_agrement WHERE code = 'CONSEIL_ACADEMIQUE'), -- CONSEIL_ACADEMIQUE | CONSEIL_RESTREINT
  (SELECT id FROM statut_intervenant WHERE source_code = 'INTERMITTENT'),
  1, -- OBLIGATOIRE
  1, -- PREMIER_RECRUTEMENT
  sysdate, (SELECT id FROM utilisateur WHERE username='lecluse'),
  sysdate, (SELECT id FROM utilisateur WHERE username='lecluse')
);