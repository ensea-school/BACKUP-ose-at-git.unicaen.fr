CREATE OR REPLACE FORCE VIEW V_TBL_AGREMENT AS
WITH i_s AS (
SELECT
    s.intervenant_id intervenant_id,
    ep.structure_id  structure_id
  FROM
    volume_horaire vh
    JOIN service s ON s.id = vh.service_id
    JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id -- pas de service extérieur, que du local
    JOIN type_volume_horaire tvh ON tvh.code = 'PREVU' AND tvh.id = vh.type_volume_horaire_id
  WHERE
    vh.histo_destruction IS NULL
    /*@intervenant_id=s.intervenant_id*/
  GROUP BY
    s.intervenant_id, ep.structure_id
  HAVING
    sum(vh.heures) > 0
UNION
SELECT
    s.intervenant_id intervenant_id,
    s.structure_id  structure_id
  FROM
    volume_horaire_ref vh
    JOIN service_referentiel s ON s.id = vh.service_referentiel_id
    JOIN type_volume_horaire tvh ON tvh.code = 'PREVU' AND tvh.id = vh.type_volume_horaire_id
  WHERE
    vh.histo_destruction IS NULL
    /*@intervenant_id=s.intervenant_id*/
  GROUP BY
    s.intervenant_id, s.structure_id
  HAVING
    sum(vh.heures) > 0
),
avi AS (
 SELECT
        i.code                									intervenant_code,
        i.annee_id            									annee_id,
        a.type_agrement_id    									type_agrement_id,
        a.id                  									agrement_id,
        a.structure_id        								    structure_id,
        CASE WHEN ta.code = 'CONSEIL_ACADEMIQUE'
        	 THEN i.annee_id+s.conseil_aca_duree_vie
        	 ELSE i.annee_id+s.conseil_restreint_duree_vie END  annee_expiration,
    	CASE WHEN ta.code = 'CONSEIL_ACADEMIQUE'
        	 THEN s.conseil_aca_duree_vie
        	 ELSE s.conseil_restreint_duree_vie END  		    duree_vie
    FROM intervenant i
    	JOIN statut s ON s.id = i.statut_id
    	JOIN agrement a ON a.intervenant_id = i.id
    	JOIN type_agrement ta ON ta.id = a.type_agrement_id
    WHERE
    	a.histo_destruction IS NULL
)
SELECT DISTINCT "ANNEE_ID","ANNEE_AGREMENT","TYPE_AGREMENT_ID","INTERVENANT_ID","CODE_INTERVENANT","STRUCTURE_ID","AGREMENT_ID","DUREE_VIE" FROM (
    SELECT
      i.annee_id                     annee_id,
      CASE
        WHEN COALESCE (avi.agrement_id,0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END   annee_agrement,
      ta.id                                      type_agrement_id,
      i.id                                       intervenant_id,
      i.code                                     code_intervenant,
      null                                       structure_id,
      avi.agrement_id			                 agrement_id,
      COALESCE(avi.duree_vie,1)                  duree_vie,
      RANK() OVER(
        PARTITION BY i.code,i.annee_id ORDER BY
        CASE
        WHEN COALESCE (avi.agrement_id,0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END DESC
      ) rank
    FROM
      type_agrement                  ta
      JOIN intervenant                 i ON i.histo_destruction IS NULL
      JOIN statut                     si ON si.id = i.statut_id

      JOIN                           i_s ON i_s.intervenant_id = i.id

      LEFT JOIN                      avi ON i.code = avi.intervenant_code
      							                		AND avi.type_agrement_id = ta.id
                                                        AND i.annee_id < avi.annee_expiration
                                                        AND i.annee_id >= avi.annee_id


    WHERE
      ta.code = 'CONSEIL_ACADEMIQUE'
      AND si.conseil_aca = 1
      /*@intervenant_id=i.id*/
      /*@annee_id=i.annee_id*/
  )
WHERE
  rank = 1

UNION ALL
SELECT DISTINCT "ANNEE_ID","ANNEE_AGREMENT","TYPE_AGREMENT_ID","INTERVENANT_ID","CODE_INTERVENANT","STRUCTURE_ID","AGREMENT_ID","DUREE_VIE" FROM (
    SELECT
      i.annee_id                                  annee_id,
      CASE
        WHEN COALESCE (avi.agrement_id,0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END    annee_agrement,
      ta.id                                       type_agrement_id,
      i.id                                        intervenant_id,
      i.code                                      code_intervenant,
      i_s.structure_id		            					  structure_id,
      avi.agrement_id 			                  agrement_id,
      COALESCE(avi.duree_vie,1)                   duree_vie,
      RANK() OVER(
        PARTITION BY i.code,i.annee_id,i_s.structure_id ORDER BY
        CASE
        WHEN COALESCE (avi.agrement_id,0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END DESC
      ) rank
    FROM
      type_agrement                   ta
      JOIN intervenant                 i ON i.histo_destruction IS NULL
      JOIN statut                     si ON si.id = i.statut_id
      JOIN                           i_s ON i_s.intervenant_id = i.id

      LEFT JOIN                      avi ON i.code = avi.intervenant_code
        							                	AND avi.type_agrement_id = ta.id
										                AND COALESCE(avi.structure_id,0) = COALESCE(i_s.structure_id,0)
                                                        AND i.annee_id < avi.annee_expiration
                                                        AND i.annee_id >= avi.annee_id


    WHERE
      ta.code = 'CONSEIL_RESTREINT'
      AND si.conseil_restreint = 1
      /*@intervenant_id=i.id*/
      /*@annee_id=i.annee_id*/
  )
WHERE
  rank = 1