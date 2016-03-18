BEGIN
  ose_piece_jointe.calculer_tout( null, false);
  --OSE_PIECE_JOINTE_DEMANDE.calculer_tout(null,false);
  --OSE_PIECE_JOINTE_FOURNIE.calculer_tout(null,false);
END;

/


SELECT
  i.id i_id,
  i.source_code i_code,
  pj.annee_id,
  tpj.CODE type_piece_jointe,
  i.nom_usuel || ' ' || i.prenom intervenant,
  pj.DEMANDEE,
  pj.FOURNIE,
  pj.VALIDEE
FROM
  tbl_piece_jointe pj
  JOIN type_piece_jointe tpj ON tpj.id = pj.TYPE_PIECE_JOINTE_ID
  JOIN intervenant i ON i.id = pj.intervenant_id
WHERE
  1=1
  AND i.id = 548
;

SELECT
  *
FROM
  tbl_piece_jointe_demande pj
;