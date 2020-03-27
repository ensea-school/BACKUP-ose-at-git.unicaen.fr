CREATE OR REPLACE FORCE VIEW V_CHARGENS_GRANDS_LIENS AS
SELECT
  nsup.id noeud_sup_id,
  lsup.id lien_sup_id,
  nl.id   noeud_liste_id,
  linf.id lien_inf_id,
  ninf.id noeud_inf_id
FROM
       noeud            nsup

  JOIN lien             lsup   ON lsup.noeud_sup_id = nsup.id
                              AND lsup.histo_destruction IS NULL

  JOIN noeud              nl   ON nl.liste = 1
                              AND nl.histo_destruction IS NULL
                              AND nl.id = lsup.noeud_inf_id

  JOIN lien             linf   ON linf.noeud_sup_id = nl.id
                              AND linf.histo_destruction IS NULL

  JOIN noeud            ninf   ON ninf.id = linf.noeud_inf_id
                              AND ninf.histo_destruction IS NULL
                              AND ninf.liste = 0
WHERE
  nsup.histo_destruction IS NULL
  AND nsup.liste = 0