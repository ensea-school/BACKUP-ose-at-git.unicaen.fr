SELECT
  ep.id                            element_pedagogique_id,
  MAX(m.ponderation_service_du)    ponderation_service_du,
  MAX(m.ponderation_service_compl) ponderation_service_compl
FROM
       element_pedagogique   ep
  JOIN element_modulateur    em ON em.element_id = ep.id
                               AND em.histo_destruction IS NULL
  JOIN modulateur             m ON m.id = em.modulateur_id
GROUP BY
  ep.id