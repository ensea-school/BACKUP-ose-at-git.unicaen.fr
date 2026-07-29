SELECT
  i.annee_id      				                    annee_id,
  s.id  						                    structure_id,
  MAX(i.id)                                         intervenant_id,
  vhm.type_volume_horaire_id 			 			type_volume_horaire_id,
  ROUND(SUM(vhm.heures * trv.valeur)) 				heures
FROM mission m
JOIN intervenant i ON i.id = m.intervenant_id
JOIN structure s ON s.id = m.structure_id
JOIN volume_horaire_mission vhm ON vhm.mission_id = m.id AND vhm.type_volume_horaire_id = 1
JOIN type_volume_horaire tvh ON tvh.id = vhm.type_volume_horaire_id
JOIN taux_remu tr  ON tr.id = m.taux_remu_id
JOIN taux_remu_valeur trv ON trv.taux_remu_id = COALESCE(tr.taux_remu_id, tr.id)
 AND trv.date_effet = (
      SELECT MAX(trv2.date_effet)
      FROM taux_remu_valeur trv2
      WHERE trv2.taux_remu_id = COALESCE(tr.taux_remu_id, tr.id)
        AND trv2.date_effet <= m.date_debut
 )
GROUP BY
  i.annee_id,s.id, vhm.type_volume_horaire_id