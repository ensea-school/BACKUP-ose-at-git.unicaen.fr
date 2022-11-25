SELECT
  nsup.id noeud_sup_id,
  lsup.id lien_sup_id,
  nl.id noeud_liste_id,
  linf.id lien_inf_id,
  ninf.id noeud_inf_id,

  nsup.etape_id etape_id,
  ninf.element_pedagogique_id element_pedagogique_id
FROM
  noeud nsup
  JOIN lien lsup ON lsup.histo_destruction IS NULL AND lsup.noeud_sup_id = nsup.id
  JOIN noeud nl ON nl.histo_destruction IS NULL AND nl.liste = 1 AND nl.id = lsup.noeud_inf_id
  JOIN lien linf ON linf.histo_destruction IS NULL AND linf.noeud_sup_id = nl.id
  JOIN noeud ninf ON ninf.histo_destruction IS NULL AND ninf.id = linf.noeud_inf_id AND ninf.liste = 0
WHERE
  nsup.histo_destruction IS NULL
  AND nsup.liste = 0