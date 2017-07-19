WITH i_in_s AS (
  SELECT DISTINCT
    s.intervenant_id
  FROM
    service s
    JOIN element_pedagogique ep ON ep.id = S.ELEMENT_PEDAGOGIQUE_ID
    JOIN structure str ON str.id = ep.structure_id
  WHERE
    1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    AND STR.SOURCE_CODE = 'U01' -- UFR Droit
)
SELECT
  i.nom_usuel nom_usuel,
  i.prenom prenom,
  AI.NO_VOIE,
  AI.NOM_VOIE,
  AI.BATIMENT,
  CASE WHEN AI.LOCALITE <> ai.ville THEN ai.localite ELSE NULL END localite,
  AI.MENTION_COMPLEMENTAIRE,
  AI.CODE_POSTAL,
  AI.VILLE,
  AI.PAYS_LIBELLE
FROM
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
  JOIN i_in_s ON i_in_s.intervenant_id = i.id
  LEFT JOIN ADRESSE_INTERVENANT ai ON AI.INTERVENANT_ID = i.id
WHERE
  ti.code = 'E'
  AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  AND i.annee_id = 2016
;